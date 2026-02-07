<?php

namespace Tests\Feature;

use App\Http\Controllers\ShoppingListController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ShoppingListControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_adds_item_to_list(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
        $listId = $this->createList($user->id);

        $request = Request::create('/list/items', 'POST', [
            'name' => 'Apples',
            'quantity' => 2,
            'price' => 3.50,
            'currency' => 'USD',
        ]);
        $request->setUserResolver(fn () => $user);

        $controller = app(ShoppingListController::class);
        $response = $controller->store($request);

        $this->assertDatabaseHas('shopping_items', [
            'shopping_list_id' => $listId,
            'name' => 'Apples',
            'quantity' => 2,
            'currency' => 'USD',
        ]);

        $this->assertSame(302, $response->getStatusCode());
    }

    public function test_toggle_flips_item_state(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
        $listId = $this->createList($user->id);
        $itemId = $this->createItem($listId, [
            'is_purchased' => false,
        ]);

        $request = Request::create('/list/items/'.$itemId.'/toggle', 'POST');
        $request->setUserResolver(fn () => $user);

        $controller = app(ShoppingListController::class);
        $controller->toggle($request, $itemId);

        $this->assertDatabaseHas('shopping_items', [
            'id' => $itemId,
            'is_purchased' => 1,
        ]);
    }

    public function test_set_all_marks_items_picked(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
        $listId = $this->createList($user->id);
        $this->createItem($listId, ['is_purchased' => false]);
        $this->createItem($listId, ['is_purchased' => false]);

        $request = Request::create('/list/items/set-all', 'POST', [
            'state' => 'picked',
        ]);
        $request->setUserResolver(fn () => $user);

        $controller = app(ShoppingListController::class);
        $controller->setAll($request);

        $this->assertSame(2, DB::table('shopping_items')->where('shopping_list_id', $listId)->where('is_purchased', true)->count());
    }

    private function createList(int $userId): int
    {
        return DB::table('shopping_lists')->insertGetId([
            'user_id' => $userId,
            'name' => 'Test list',
            'spending_limit' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createItem(int $listId, array $overrides = []): int
    {
        return DB::table('shopping_items')->insertGetId(array_merge([
            'shopping_list_id' => $listId,
            'name' => 'Milk',
            'quantity' => 1,
            'price' => 1.20,
            'currency' => 'USD',
            'is_purchased' => false,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }
}
