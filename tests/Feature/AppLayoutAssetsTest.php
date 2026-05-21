<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppLayoutAssetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_layout_uses_relative_vehicle_ref_assets_not_app_url(): void
    {
        config(['app.url' => 'http://wrong-host.example']);

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('/vehicle-ref/dist/css/adminlte.min.css', false);
        $response->assertDontSee('http://wrong-host.example/vehicle-ref', false);
        $response->assertDontSee('http://localhost/vehicle-ref', false);
    }

    public function test_authenticated_layout_uses_relative_app_js_paths(): void
    {
        config(['app.url' => 'http://wrong-host.example']);

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('/js/modal-loader.js', false);
        $response->assertSee('/js/search-suggest.js', false);
        $response->assertDontSee('http://wrong-host.example/js/modal-loader.js', false);
    }

    public function test_mechanic_portal_layout_uses_relative_assets(): void
    {
        config(['app.url' => 'http://wrong-host.example']);

        $mechanic = User::factory()->mechanic()->create();

        $response = $this->actingAs($mechanic)->get(route('mechanic.tasks'));

        $response->assertOk();
        $response->assertSee('/vehicle-ref/dist/css/adminlte.min.css', false);
    }

    public function test_owner_portal_layout_uses_relative_assets(): void
    {
        config(['app.url' => 'http://wrong-host.example']);

        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->get(route('customer.vehicles'));

        $response->assertOk();
        $response->assertSee('/vehicle-ref/dist/css/adminlte.min.css', false);
    }
}
