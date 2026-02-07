<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Shopping List</title>
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

        .search-form {
            display: grid;
            grid-template-columns: 1fr auto auto auto auto auto;
            gap: 12px;
            margin: 0 0 12px;
        }

        .search-form input {
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

        .empty {
            padding: 16px;
            border-radius: 12px;
            background: #fff5e7;
            border: 1px solid #f3d9b6;
            color: #7a5a2b;
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
        }

        .toggle-button {
            border: 1px solid var(--border);
            background: #f7f2e9;
            color: var(--ink);
        }

        .toggle-button.done {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }

        .sort-button.active {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }
    </style>
</head>
<body>
    <header>
        <h1>My shopping list</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </header>

    <section class="shell">
        @if ($list)
            <div class="meta">{{ $list->name }} · {{ count($items) }} items</div>
            @if ($errors->any())
                <div class="empty">{{ $errors->first() }}</div>
            @endif
            <form class="search-form" method="GET" action="{{ route('shopping.list') }}">
                <input name="q" placeholder="Search products" value="{{ $search }}">
                <input type="hidden" name="sort" value="{{ $sort }}">
                <button type="submit">Search</button>
                <button class="sort-button {{ $sort === 'name' ? 'active' : '' }}" name="sort" value="name" type="submit">
                    Sort by name
                </button>
                <button class="sort-button {{ $sort === 'picked' ? 'active' : '' }}" name="sort" value="picked" type="submit">
                    Picked up on top
                </button>
                <button class="sort-button" form="set-all-picked" type="submit">All picked up</button>
                <button class="sort-button" form="set-all-buy" type="submit">All to buy</button>
            </form>
            <form id="set-all-picked" method="POST" action="{{ route('shopping.items.set-all') }}">
                @csrf
                <input type="hidden" name="state" value="picked">
                <input type="hidden" name="q" value="{{ $search }}">
                <input type="hidden" name="sort" value="{{ $sort }}">
            </form>
            <form id="set-all-buy" method="POST" action="{{ route('shopping.items.set-all') }}">
                @csrf
                <input type="hidden" name="state" value="to-buy">
                <input type="hidden" name="q" value="{{ $search }}">
                <input type="hidden" name="sort" value="{{ $sort }}">
            </form>
            <form class="add-form" method="POST" action="{{ route('shopping.items.store') }}">
                @csrf
                <input name="name" placeholder="Add an item" value="{{ old('name') }}" required>
                <input name="quantity" type="number" min="1" placeholder="Qty" value="{{ old('quantity', 1) }}">
                <input name="price" type="number" step="0.01" min="0" placeholder="Price" value="{{ old('price') }}">
                <select name="currency" required>
                    <option value="USD" {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP</option>
                </select>
                <button type="submit">Add</button>
            </form>
            <ul>
                @forelse ($items as $item)
                    <li>
                        <span class="badge {{ $item->is_purchased ? 'done' : '' }}"></span>
                        <div>
                            <div class="item-name">{{ $item->name }}</div>
                            <div class="item-meta">
                                Qty {{ $item->quantity }} · {{ $item->currency }} {{ number_format($item->price, 2) }}
                            </div>
                        </div>
                        <form method="POST" action="{{ route('shopping.items.toggle', $item->id) }}">
                            @csrf
                            <button type="submit" class="toggle-button {{ $item->is_purchased ? 'done' : '' }}">
                                {{ $item->is_purchased ? 'Picked up' : 'Buy' }}
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="empty">No items match your search.</li>
                @endforelse
            </ul>
        @else
            <div class="empty">No shopping list found for your account.</div>
        @endif
    </section>
</body>
</html>
