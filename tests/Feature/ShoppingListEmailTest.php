<?php

namespace Tests\Feature;

use App\Livewire\ShoppingListPage;
use App\Mail\ShoppingListSummary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ShoppingListEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_list_email_sends_summary_to_user(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $listId = DB::table('shopping_lists')->insertGetId([
            'user_id' => $user->id,
            'name' => "{$user->name}'s list",
            'spending_limit' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('shopping_items')->insert([
            'shopping_list_id' => $listId,
            'name' => 'Milk',
            'quantity' => 2,
            'price' => 2.50,
            'currency' => 'USD',
            'is_purchased' => false,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(ShoppingListPage::class)
            ->call('sendListEmail')
            ->assertSet('emailNotice', "List sent to {$user->email}.");

        Mail::assertSent(ShoppingListSummary::class, function (ShoppingListSummary $mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->listName === "{$user->name}'s list";
        });
    }
}
