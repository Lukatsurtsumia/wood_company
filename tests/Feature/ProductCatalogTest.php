<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_public_catalog_lists_published_products(): void
    {
        $shown = Product::factory()->create(['name' => 'Visible Oak', 'is_published' => true]);
        $hidden = Product::factory()->unpublished()->create(['name' => 'Hidden Pine']);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Visible Oak')
            ->assertDontSee('Hidden Pine');
    }

    public function test_public_catalog_filters_by_category(): void
    {
        Product::factory()->create(['name' => 'Oak Board', 'category' => 'Lumber', 'is_published' => true]);
        Product::factory()->create(['name' => 'Parquet Tile', 'category' => 'Flooring', 'is_published' => true]);

        Volt::test('pages.products.index')
            ->set('category', 'Lumber')
            ->assertSee('Oak Board')
            ->assertDontSee('Parquet Tile');
    }

    public function test_product_detail_page_renders(): void
    {
        $product = Product::factory()->create(['name' => 'Walnut Slab']);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Walnut Slab');
    }

    public function test_unpublished_product_detail_is_not_found(): void
    {
        $product = Product::factory()->unpublished()->create();

        $this->get(route('products.show', $product))->assertNotFound();
    }

    public function test_admin_pages_require_authentication(): void
    {
        $this->get(route('admin.products.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_product_list(): void
    {
        Product::factory()->create(['name' => 'Managed Product']);

        $this->actingAs($this->admin())
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('Managed Product');
    }

    public function test_admin_can_create_a_product(): void
    {
        $this->actingAs($this->admin());

        Volt::test('pages.admin.products.create')
            ->set('form.name', 'New Cedar Beam')
            ->set('form.category', 'Lumber')
            ->set('form.price', '42.50')
            ->set('form.stock', 10)
            ->set('form.description', 'A sturdy beam.')
            ->call('save')
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'New Cedar Beam',
            'slug' => 'new-cedar-beam',
            'category' => 'Lumber',
        ]);
    }

    public function test_creating_a_product_validates_input(): void
    {
        $this->actingAs($this->admin());

        Volt::test('pages.admin.products.create')
            ->set('form.name', '')
            ->set('form.category', 'NotARealCategory')
            ->set('form.price', 'abc')
            ->call('save')
            ->assertHasErrors(['form.name', 'form.category', 'form.price']);
    }

    public function test_admin_can_edit_a_product(): void
    {
        $product = Product::factory()->create(['name' => 'Old Name', 'price' => 10]);

        $this->actingAs($this->admin());

        Volt::test('pages.admin.products.edit', ['product' => $product])
            ->set('form.name', 'Updated Name')
            ->set('form.price', '99.99')
            ->call('save')
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
            'price' => '99.99',
        ]);
    }

    public function test_admin_can_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin());

        Volt::test('pages.admin.products.index')
            ->call('delete', $product->id)
            ->assertDispatched('product-deleted');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
