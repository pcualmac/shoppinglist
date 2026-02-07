<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingItem extends Model
{
    protected $fillable = [
        'shopping_list_id',
        'name',
        'quantity',
        'price',
        'currency',
        'is_purchased',
        'sort_order',
    ];

    protected $casts = [
        'is_purchased' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function shoppingList(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class);
    }
}
