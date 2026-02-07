<?php

namespace App\Livewire;

use App\Models\ShoppingItem;
use App\Models\ShoppingList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ShoppingListPage extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public string $search = '';
    public string $sort = '';
    public string $name = '';
    public int $quantity = 1;
    public ?string $price = null;
    public string $currency = 'USD';
    public ?string $limitUsd = null;
    public ?string $limitEur = null;
    public ?string $limitGbp = null;
    public bool $pickedOnly = false;

    protected array $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => ''],
        'pickedOnly' => ['except' => false],
    ];

    protected array $rules = [
        'name' => 'required|string|max:255',
        'quantity' => 'nullable|integer|min:1',
        'price' => 'nullable|numeric|min:0',
        'currency' => 'required|string|size:3',
        'limitUsd' => 'nullable|numeric|min:0',
        'limitEur' => 'nullable|numeric|min:0',
        'limitGbp' => 'nullable|numeric|min:0',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function updatingPickedOnly(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->loadLimits();
    }

    protected function loadLimits(): void
    {
        $user = Auth::user();

        $list = ShoppingList::query()
            ->where('user_id', $user->id)
            ->first();

        if (!$list) {
            return;
        }

        $usd = data_get($list, 'spending_limit_usd');
        $eur = data_get($list, 'spending_limit_eur');
        $gbp = data_get($list, 'spending_limit_gbp');

        $this->limitUsd = $usd !== null ? (string) $usd : null;
        $this->limitEur = $eur !== null ? (string) $eur : null;
        $this->limitGbp = $gbp !== null ? (string) $gbp : null;
    }

    public function addItem(): void
    {
        $validated = $this->validate();

        $user = Auth::user();

        $list = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->first();

        if (!$list) {
            $list = ShoppingList::create([
                'user_id' => $user->id,
                'name' => "{$user->name}'s list",
                'spending_limit' => null,
            ]);
        }

        $limitMap = [
            'USD' => $this->limitUsd,
            'EUR' => $this->limitEur,
            'GBP' => $this->limitGbp,
        ];

        $limitValue = $limitMap[$validated['currency']] ?? null;
        if ($limitValue !== null && (float) $limitValue > 0) {
            $currentTotal = (float) DB::table('shopping_items')
                ->where('shopping_list_id', $list->id)
                ->where('currency', $validated['currency'])
                ->sum(DB::raw('price * quantity'));

            $incomingTotal = ($validated['price'] ?? 0) * ($validated['quantity'] ?? 1);

            if (($currentTotal + $incomingTotal) > (float) $limitValue) {
                $remaining = (float) $limitValue - $currentTotal;
                $remaining = $remaining < 0 ? 0 : $remaining;
                $remainingFormatted = number_format($remaining, 2);
                $this->addError(
                    'currency',
                    "Limit reached for {$validated['currency']}. Remaining allowance: {$validated['currency']} {$remainingFormatted}."
                );
                return;
            }
        }

        $nextOrder = (int) ShoppingItem::query()
            ->where('shopping_list_id', $list->id)
            ->max('sort_order');

        ShoppingItem::create([
            'shopping_list_id' => $list->id,
            'name' => $validated['name'],
            'quantity' => $validated['quantity'] ?? 1,
            'price' => $validated['price'] ?? 0,
            'currency' => $validated['currency'],
            'is_purchased' => false,
            'sort_order' => $nextOrder + 1,
        ]);

        $this->reset(['name', 'price']);
        $this->quantity = 1;
        $this->currency = 'USD';
    }

    public function toggleItem(int $itemId): void
    {
        $user = Auth::user();

        $listId = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->value('id');

        if (!$listId) {
            return;
        }

        $current = DB::table('shopping_items')
            ->where('id', $itemId)
            ->where('shopping_list_id', $listId)
            ->value('is_purchased');

        if ($current === null) {
            return;
        }

        DB::table('shopping_items')
            ->where('id', $itemId)
            ->where('shopping_list_id', $listId)
            ->update([
                'is_purchased' => !$current,
                'updated_at' => now(),
            ]);
    }

    public function updateItem(int $itemId, string $field, $value): void
    {
        if (!in_array($field, ['quantity', 'price'], true)) {
            return;
        }

        $user = Auth::user();

        $listId = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->value('id');

        if (!$listId) {
            return;
        }

        $item = DB::table('shopping_items')
            ->where('id', $itemId)
            ->where('shopping_list_id', $listId)
            ->first();

        if (!$item) {
            return;
        }

        if ($field === 'quantity') {
            $qty = (int) $value;
            if ($qty < 1) {
                return;
            }

            DB::table('shopping_items')
                ->where('id', $itemId)
                ->update([
                    'quantity' => $qty,
                    'updated_at' => now(),
                ]);
            return;
        }

        $price = (float) $value;
        if ($price < 0) {
            return;
        }

        DB::table('shopping_items')
            ->where('id', $itemId)
            ->update([
                'price' => $price,
                'updated_at' => now(),
            ]);
    }

    public function setAll(string $state): void
    {
        if (!in_array($state, ['picked', 'to-buy'], true)) {
            return;
        }

        $user = Auth::user();

        $listId = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->value('id');

        if (!$listId) {
            return;
        }

        DB::table('shopping_items')
            ->where('shopping_list_id', $listId)
            ->update([
                'is_purchased' => $state === 'picked',
                'updated_at' => now(),
            ]);
    }

    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }

    public function togglePickedOnly(): void
    {
        $this->pickedOnly = !$this->pickedOnly;
    }

    public function saveLimits(): void
    {
        $this->validateOnly('limitUsd');
        $this->validateOnly('limitEur');
        $this->validateOnly('limitGbp');

        $user = Auth::user();

        $list = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->first();

        if (!$list) {
            $listId = DB::table('shopping_lists')->insertGetId([
                'user_id' => $user->id,
                'name' => "{$user->name}'s list",
                'spending_limit' => null,
                'spending_limit_usd' => $this->limitUsd,
                'spending_limit_eur' => $this->limitEur,
                'spending_limit_gbp' => $this->limitGbp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $list = (object) ['id' => $listId];
        } else {
            DB::table('shopping_lists')
                ->where('id', $list->id)
                ->update([
                    'spending_limit_usd' => $this->limitUsd,
                    'spending_limit_eur' => $this->limitEur,
                    'spending_limit_gbp' => $this->limitGbp,
                    'updated_at' => now(),
                ]);
        }
    }

    public function render()
    {
        $user = Auth::user();

        $list = DB::table('shopping_lists')
            ->where('user_id', $user->id)
            ->first();

        $items = [];
        $totalItems = 0;
        $totals = [
            'USD' => 0.0,
            'EUR' => 0.0,
            'GBP' => 0.0,
        ];
        $pickedTotals = [
            'USD' => 0.0,
            'EUR' => 0.0,
            'GBP' => 0.0,
        ];
        $pickedLimitOver = [
            'USD' => false,
            'EUR' => false,
            'GBP' => false,
        ];
        if ($list) {
            $itemsQuery = DB::table('shopping_items')
                ->where('shopping_list_id', $list->id);

            $searchTerm = trim($this->search);
            if ($searchTerm !== '') {
                $itemsQuery->where('name', 'like', "%{$searchTerm}%");
            }

            if ($this->pickedOnly) {
                $itemsQuery->where('is_purchased', true);
            }

            if ($this->sort === 'name') {
                $itemsQuery->orderBy('name');
            } elseif ($this->sort === 'picked') {
                $itemsQuery->orderByDesc('is_purchased')->orderBy('sort_order');
            } else {
                $itemsQuery->orderBy('sort_order');
            }

            $totalItems = (clone $itemsQuery)->count();
            $items = $itemsQuery->paginate($this->perPage);

            $totalsQuery = DB::table('shopping_items')
                ->select('currency', DB::raw('SUM(price * quantity) as total'))
                ->where('shopping_list_id', $list->id)
                ->groupBy('currency')
                ->get();

            foreach ($totalsQuery as $row) {
                $totals[$row->currency] = (float) $row->total;
            }

            $pickedQuery = DB::table('shopping_items')
                ->select('currency', DB::raw('SUM(price * quantity) as total'))
                ->where('shopping_list_id', $list->id)
                ->where('is_purchased', true)
                ->groupBy('currency')
                ->get();

            foreach ($pickedQuery as $row) {
                $pickedTotals[$row->currency] = (float) $row->total;
            }

            $limits = [
                'USD' => $this->limitUsd,
                'EUR' => $this->limitEur,
                'GBP' => $this->limitGbp,
            ];

            foreach ($limits as $currency => $limit) {
                if ($limit !== null && (float) $limit > 0) {
                    $pickedLimitOver[$currency] = $pickedTotals[$currency] >= (float) $limit;
                }
            }
        }

        return view('livewire.shopping-list', [
            'list' => $list,
            'items' => $items,
            'totalItems' => $totalItems,
            'totals' => $totals,
            'pickedTotals' => $pickedTotals,
            'pickedLimitOver' => $pickedLimitOver,
        ]);
    }
}
