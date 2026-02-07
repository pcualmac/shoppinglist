<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    /**
     * Show the authenticated user's shopping list.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $list = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->first();

        $items = [];
        if ($list) {
            $itemsQuery = DB::table('shopping_items')
                ->where('shopping_list_id', $list->id);

            $search = trim((string) $request->query('q'));
            if ($search !== '') {
                $itemsQuery->where('name', 'like', "%{$search}%");
            }

            $sort = $request->query('sort');

            if ($sort === 'name') {
                $itemsQuery->orderBy('name');
            } elseif ($sort === 'picked') {
                $itemsQuery->orderByDesc('is_purchased')->orderBy('sort_order');
            } else {
                $itemsQuery->orderBy('sort_order');
            }

            $items = $itemsQuery->get();
        }

        return view('shopping.list', [
            'list' => $list,
            'items' => $items,
            'search' => $request->query('q', ''),
            'sort' => $request->query('sort', ''),
        ]);
    }

    /**
     * Add a new item to the authenticated user's list.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        $user = Auth::user();

        $list = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->first();

        if (!$list) {
            $listId = DB::table('shopping_lists')->insertGetId([
                'user_id' => $user->id,
                'name' => "{$user->name}'s list",
                'spending_limit' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $list = (object) ['id' => $listId];
        }

        $nextOrder = (int) DB::table('shopping_items')
            ->where('shopping_list_id', $list->id)
            ->max('sort_order');

        DB::table('shopping_items')->insert([
            'shopping_list_id' => $list->id,
            'name' => $validated['name'],
            'quantity' => $validated['quantity'] ?? 1,
            'price' => $validated['price'] ?? 0,
            'currency' => $validated['currency'],
            'is_purchased' => false,
            'sort_order' => $nextOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('shopping.list');
    }

    /**
     * Toggle the purchased state for an item on the user's list.
     */
    public function toggle(Request $request, int $item)
    {
        $user = Auth::user();

        $listId = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->value('id');

        if (!$listId) {
            return redirect()->route('shopping.list');
        }

        $current = DB::table('shopping_items')
            ->where('id', $item)
            ->where('shopping_list_id', $listId)
            ->value('is_purchased');

        if ($current === null) {
            return redirect()->route('shopping.list');
        }

        DB::table('shopping_items')
            ->where('id', $item)
            ->where('shopping_list_id', $listId)
            ->update([
                'is_purchased' => !$current,
                'updated_at' => now(),
            ]);

        return redirect()->route('shopping.list', [
            'q' => $request->query('q', ''),
        ]);
    }

    /**
     * Set all items to picked up or to buy for the user's list.
     */
    public function setAll(Request $request)
    {
        $validated = $request->validate([
            'state' => ['required', 'in:picked,to-buy'],
        ]);

        $user = Auth::user();

        $listId = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->value('id');

        if (!$listId) {
            return redirect()->route('shopping.list');
        }

        $isPurchased = $validated['state'] === 'picked';

        DB::table('shopping_items')
            ->where('shopping_list_id', $listId)
            ->update([
                'is_purchased' => $isPurchased,
                'updated_at' => now(),
            ]);

        return redirect()->route('shopping.list', [
            'q' => $request->query('q', ''),
            'sort' => $request->query('sort', ''),
        ]);
    }
}
