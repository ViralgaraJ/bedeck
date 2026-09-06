<?php

namespace Tests\Feature\Admin;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guests_cannot_access_partner_admin(): void
    {
        $this->get(route('admin.partners.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_partners_list(): void
    {
        $admin = User::where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.partners.index'))
            ->assertOk()
            ->assertSee('ISOIL Impianti S.p.A.');
    }

    public function test_admin_can_create_partner(): void
    {
        $admin = User::where('is_admin', true)->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.partners.store'), [
            'name' => 'New Global Partner',
            'country' => 'Germany',
            'website' => 'https://example.de',
            'sort_order' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseHas('partners', [
            'name' => 'New Global Partner',
            'country' => 'Germany',
            'website' => 'https://example.de',
            'sort_order' => 10,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_partner(): void
    {
        $admin = User::where('is_admin', true)->firstOrFail();
        $partner = Partner::firstOrFail();

        $response = $this->actingAs($admin)->put(route('admin.partners.update', $partner), [
            'name' => 'Updated Partner Name',
            'country' => 'France',
            'website' => 'https://example.fr',
            'sort_order' => 5,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'name' => 'Updated Partner Name',
            'country' => 'France',
        ]);
    }

    public function test_admin_can_delete_partner(): void
    {
        $admin = User::where('is_admin', true)->firstOrFail();
        $partner = Partner::firstOrFail();

        $response = $this->actingAs($admin)->delete(route('admin.partners.destroy', $partner));

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);
    }
}
