<div class="shopping-page">
    <style>
        :root {
            color-scheme: light;
            --bg: #f5f3ee;
            --card: #ffffff;
            --ink: #1f1a15;
            --muted: #6d6259;
            --accent: #1e7a64;
            --border: #e5ddd2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Avenir Next", "Segoe UI", Tahoma, sans-serif;
            background: radial-gradient(1100px 600px at 85% -10%, #e9f6f1, transparent),
                radial-gradient(900px 500px at 10% 0%, #fff1da, transparent),
                var(--bg);
            color: var(--ink);
            min-height: 100vh;
            padding: 32px 16px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 860px;
            margin: 0 auto 24px;
        }

        h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: -0.02em;
        }

        .shell {
            max-width: 860px;
            margin: 0 auto;
            background: var(--card);
            border-radius: 18px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 50px rgba(31, 26, 21, 0.12);
        }

        .meta {
            color: var(--muted);
            margin: 6px 0 18px;
        }

        .search-row {
            display: grid;
            grid-template-columns: 1fr auto auto auto auto auto;
            gap: 12px;
            margin: 0 0 12px;
        }

        .limits {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 0 0 18px;
            position: sticky;
            top: 12px;
            z-index: 5;
        }

        .limit-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px;
            background: #fffdf9;
        }

        .limit-card h3 {
            margin: 0 0 8px;
            font-size: 14px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .limit-flag {
            padding: 2px 6px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: #f2c6c2;
            color: #7a2c26;
        }

        .limit-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
            align-items: center;
            margin-bottom: 8px;
        }

        .limit-row input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 14px;
            background: #ffffff;
        }

        .limit-status {
            font-size: 13px;
            color: var(--muted);
        }

        .limit-status.ok {
            color: #1e7a64;
            font-weight: 600;
        }

        .limit-status.warn {
            color: #b86b12;
            font-weight: 600;
        }

        .limit-status.over {
            color: #a1352b;
            font-weight: 600;
        }

        .search-row input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            background: #fffdf9;
        }

        .add-form {
            display: grid;
            grid-template-columns: 1fr 110px 120px 120px auto;
            gap: 12px;
            margin: 0 0 18px;
        }

        .add-form input,
        .add-form select {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            background: #fffdf9;
        }

        .add-form button {
            border: none;
            background: var(--accent);
            color: #fff;
        }

        .add-form button:hover {
            background: #165e4d;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 12px;
        }

        .pagination {
            margin-top: 14px;
            display: flex;
            justify-content: center;
        }

        .pagination nav {
            font-size: 12px;
        }

        .pagination nav > div {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            padding: 6px;
            border-radius: 999px;
            background: #fff7ec;
            border: 1px solid var(--border);
            box-shadow: 0 10px 24px rgba(31, 26, 21, 0.08);
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 10px;
            border: 1px solid transparent;
            border-radius: 999px;
            background: #ffffff;
            color: var(--ink);
            text-decoration: none;
            font-size: 12px;
            line-height: 1;
        }

        .pagination a:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .pagination [aria-current="page"] span {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }

        .pagination [aria-disabled="true"] span {
            background: transparent;
            color: var(--muted);
        }

        li {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 12px;
            align-items: center;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fffdf9;
        }

        .badge {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #d8c8b1;
        }

        .badge.done {
            background: var(--accent);
        }

        .item-name {
            font-weight: 600;
        }

        .item-meta {
            font-size: 13px;
            color: var(--muted);
        }

        .item-inline-input {
            width: 52px;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 2px 6px;
            font-size: 12px;
            line-height: 16px;
            height: 20px;
            vertical-align: middle;
            background: #ffffff;
        }

        .empty {
            padding: 16px;
            border-radius: 12px;
            background: #fff5e7;
            border: 1px solid #f3d9b6;
            color: #7a5a2b;
        }

        .notice {
            padding: 12px 14px;
            border-radius: 12px;
            background: #fff2f0;
            border: 1px solid #f2c6c2;
            color: #7a2c26;
            font-size: 13px;
            margin: 0 0 12px;
        }

        form {
            margin: 0;
        }

        button {
            border: 1px solid var(--border);
            background: #ffffff;
            padding: 8px 12px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
        }

        button::before {
            content: "+";
            display: inline-block;
            margin-right: 6px;
            font-weight: 700;
        }

        .no-icon::before {
            content: "";
            margin-right: 0;
        }

        .toggle-button {
            border: 1px solid var(--border);
            background: #f7f2e9;
            color: var(--ink);
        }

        .toggle-button.done,
        .sort-button.active {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }
    </style>
    <header>
        <h1>My shopping list</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </header>

    <section class="shell">
        @if ($list)
            <div class="meta">{{ $list->name }} · {{ $totalItems }} items</div>
            @if ($errors->any())
                <div class="empty">{{ $errors->first() }}</div>
            @endif
            <div class="search-row">
                <input
                    type="text"
                    placeholder="Search products"
                    wire:model.live.debounce.300ms="search"
                >
                <button class="sort-button no-icon" wire:click="sendListEmail" type="button">
                    Email list
                </button>
                <button class="sort-button no-icon {{ $pickedOnly ? 'active' : '' }}" wire:click="togglePickedOnly" type="button">
                    Picked up only
                </button>
                <button class="sort-button no-icon {{ $sort === 'name' ? 'active' : '' }}" wire:click="setSort('name')" type="button">
                    Sort by name
                </button>
                <button class="sort-button no-icon {{ $sort === 'picked' ? 'active' : '' }}" wire:click="setSort('picked')" type="button">
                    Picked up on top
                </button>
            </div>
            @if ($emailNotice)
                <div class="notice">{{ $emailNotice }}</div>
            @endif
            <div class="limits">
                <div class="limit-card">
                    <h3>
                        USD Limit
                        @if ($pickedLimitOver['USD'] ?? false)
                            <span class="limit-flag">Limit reached</span>
                        @endif
                    </h3>
                    <div class="limit-row">
                        <input type="number" step="0.01" min="0" wire:model.defer="limitUsd" placeholder="Max spend">
                        <button type="button" wire:click="saveLimits">Save</button>
                    </div>
                    @php
                        $usdLimit = $limitUsd !== null ? (float) $limitUsd : null;
                        $usdRatio = $usdLimit && $usdLimit > 0 ? ($totals['USD'] / $usdLimit) : null;
                        $usdClass = $usdRatio === null ? '' : ($usdRatio < 0.5 ? 'ok' : ($usdRatio < 0.8 ? 'warn' : 'over'));
                    @endphp
                    <div class="limit-status {{ $usdClass }}">
                        Total: USD {{ number_format($totals['USD'], 2) }}
                        @if ($usdRatio !== null)
                            ({{ number_format($usdRatio * 100, 0) }}%)
                        @endif
                        · Picked up: USD {{ number_format($pickedTotals['USD'], 2) }}
                    </div>
                </div>
                <div class="limit-card">
                    <h3>
                        EUR Limit
                        @if ($pickedLimitOver['EUR'] ?? false)
                            <span class="limit-flag">Limit reached</span>
                        @endif
                    </h3>
                    <div class="limit-row">
                        <input type="number" step="0.01" min="0" wire:model.defer="limitEur" placeholder="Max spend">
                        <button type="button" wire:click="saveLimits">Save</button>
                    </div>
                    @php
                        $eurLimit = $limitEur !== null ? (float) $limitEur : null;
                        $eurRatio = $eurLimit && $eurLimit > 0 ? ($totals['EUR'] / $eurLimit) : null;
                        $eurClass = $eurRatio === null ? '' : ($eurRatio < 0.5 ? 'ok' : ($eurRatio < 0.8 ? 'warn' : 'over'));
                    @endphp
                    <div class="limit-status {{ $eurClass }}">
                        Total: EUR {{ number_format($totals['EUR'], 2) }}
                        @if ($eurRatio !== null)
                            ({{ number_format($eurRatio * 100, 0) }}%)
                        @endif
                        · Picked up: EUR {{ number_format($pickedTotals['EUR'], 2) }}
                    </div>
                </div>
                <div class="limit-card">
                    <h3>
                        GBP Limit
                        @if ($pickedLimitOver['GBP'] ?? false)
                            <span class="limit-flag">Limit reached</span>
                        @endif
                    </h3>
                    <div class="limit-row">
                        <input type="number" step="0.01" min="0" wire:model.defer="limitGbp" placeholder="Max spend">
                        <button type="button" wire:click="saveLimits">Save</button>
                    </div>
                    @php
                        $gbpLimit = $limitGbp !== null ? (float) $limitGbp : null;
                        $gbpRatio = $gbpLimit && $gbpLimit > 0 ? ($totals['GBP'] / $gbpLimit) : null;
                        $gbpClass = $gbpRatio === null ? '' : ($gbpRatio < 0.5 ? 'ok' : ($gbpRatio < 0.8 ? 'warn' : 'over'));
                    @endphp
                    <div class="limit-status {{ $gbpClass }}">
                        Total: GBP {{ number_format($totals['GBP'], 2) }}
                        @if ($gbpRatio !== null)
                            ({{ number_format($gbpRatio * 100, 0) }}%)
                        @endif
                        · Picked up: GBP {{ number_format($pickedTotals['GBP'], 2) }}
                    </div>
                </div>
            </div>
            <form class="add-form" wire:submit.prevent="addItem">
                <input name="name" placeholder="Add an item" wire:model.defer="name" required>
                <input name="quantity" type="number" min="1" placeholder="Qty" wire:model.defer="quantity">
                <input name="price" type="number" step="0.01" min="0" placeholder="Price" wire:model.defer="price">
                <select name="currency" wire:model.defer="currency" required>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
                <button type="submit">Add</button>
            </form>
            @error('currency')
                <div class="empty">{{ $message }}</div>
            @enderror
            <ul>
                @forelse ($items as $item)
                    <li wire:key="item-{{ $item->id }}">
                        <span class="badge {{ $item->is_purchased ? 'done' : '' }}"></span>
                        <div>
                            <div class="item-name">{{ $item->name }}</div>
                            <div class="item-meta">
                                Qty
                                <input
                                    class="item-inline-input"
                                    type="number"
                                    min="1"
                                    value="{{ $item->quantity }}"
                                    wire:change="updateItem({{ $item->id }}, 'quantity', $event.target.value)"
                                >
                                · {{ $item->currency }}
                                <input
                                    class="item-inline-input"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value="{{ $item->price }}"
                                    wire:change="updateItem({{ $item->id }}, 'price', $event.target.value)"
                                >
                            </div>
                        </div>
                        <button
                            type="button"
                            class="toggle-button {{ $item->is_purchased ? 'done' : '' }}"
                            wire:click="toggleItem({{ $item->id }})"
                            {{ !$item->is_purchased && ($pickedLimitOver[$item->currency] ?? false) ? 'disabled' : '' }}
                        >
                            {{ $item->is_purchased ? 'Picked up' : 'Buy' }}
                        </button>
                    </li>
                @empty
                    <li class="empty">No items match your search.</li>
                @endforelse
            </ul>
            <div class="pagination">
                {{ $items->links('vendor.pagination.simple-text') }}
            </div>
        @else
            <div class="empty">No shopping list found for your account.</div>
        @endif
    </section>

    </div>
