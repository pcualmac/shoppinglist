<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Shopping list</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f1a15;">
    <h2 style="margin: 0 0 6px;">{{ $listName }}</h2>
    <p style="margin: 0 0 16px; color: #6d6259;">
        @if ($searchTerm !== '')
            Filter: "{{ $searchTerm }}"
        @else
            Filter: none
        @endif
        · Picked only: {{ $pickedOnly ? 'yes' : 'no' }}
        · Sort: {{ $sort !== '' ? $sort : 'default' }}
    </p>

    @if ($items->isEmpty())
        <p>No items match the current filters.</p>
    @else
        <table cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background: #f5f3ee; text-align: left;">
                    <th style="border: 1px solid #e5ddd2;">Item</th>
                    <th style="border: 1px solid #e5ddd2;">Qty</th>
                    <th style="border: 1px solid #e5ddd2;">Price</th>
                    <th style="border: 1px solid #e5ddd2;">Currency</th>
                    <th style="border: 1px solid #e5ddd2;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td style="border: 1px solid #e5ddd2;">{{ $item->name }}</td>
                        <td style="border: 1px solid #e5ddd2;">{{ $item->quantity }}</td>
                        <td style="border: 1px solid #e5ddd2;">{{ number_format((float) $item->price, 2) }}</td>
                        <td style="border: 1px solid #e5ddd2;">{{ $item->currency }}</td>
                        <td style="border: 1px solid #e5ddd2;">{{ $item->is_purchased ? 'Picked up' : 'To buy' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3 style="margin: 20px 0 6px;">Totals</h3>
    <ul style="margin: 0; padding-left: 18px; color: #1f1a15;">
        @foreach (['USD', 'EUR', 'GBP'] as $currency)
            <li>
                {{ $currency }}: {{ number_format($totals[$currency] ?? 0, 2) }}
                (picked: {{ number_format($pickedTotals[$currency] ?? 0, 2) }})
            </li>
        @endforeach
    </ul>
</body>
</html>
