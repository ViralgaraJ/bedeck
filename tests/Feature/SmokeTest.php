<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_seed_loads_the_full_catalogue(): void
    {
        $this->assertSame(92, Product::count());
        $this->assertDatabaseCount('categories', 11);
        $this->assertDatabaseCount('partners', 8);
        $this->assertTrue(User::where('is_admin', true)->exists());
    }

    public function test_public_pages_load(): void
    {
        foreach (['/', '/about', '/services', '/products', '/partners', '/contact'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_product_detail_and_category_pages_load(): void
    {
        $product = Product::with('category')->firstWhere('category_id', '!=', null);

        $this->get(route('products.show', $product))->assertOk()->assertSee($product->name);
        $this->get(route('products.category', $product->category))->assertOk();
    }

    public function test_catalogue_search_and_pagination(): void
    {
        $this->get('/products?q=meter&page=2')->assertOk();
        $this->get('/products/category/valves?q=ball')->assertOk();
    }

    public function test_contact_form_stores_an_enquiry(): void
    {
        $this->post('/contact', [
            'name' => 'Test Buyer',
            'email' => 'buyer@example.com',
            'message' => 'Please quote 2x ISOIL SBM 32 PD Meter.',
        ])->assertRedirect();

        $this->assertDatabaseHas('enquiries', ['email' => 'buyer@example.com']);
    }

    public function test_admin_area_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get('/admin/products')->assertRedirect(route('admin.login'));
    }

    public function test_seeded_admin_can_sign_in(): void
    {
        $admin = User::where('is_admin', true)->firstOrFail();

        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'ChangeMe!2010', // AdminUserSeeder default (no ADMIN_PASSWORD in test env)
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_web_installer_is_inert_without_a_token(): void
    {
        $this->get('/__install/whatever')->assertNotFound();
    }
}
