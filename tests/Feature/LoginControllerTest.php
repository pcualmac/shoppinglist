<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_logs_out_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret-pass'),
        ]);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'secret-pass')
            ->call('authenticate')
            ->assertRedirect(route('shopping.list'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret-pass'),
        ]);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-pass')
            ->call('authenticate')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }
}
