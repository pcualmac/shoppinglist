<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ShoppingListSummary extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $listName,
        public Collection $items,
        public array $totals,
        public array $pickedTotals,
        public string $searchTerm,
        public bool $pickedOnly,
        public string $sort,
    ) {
    }

    public function build(): self
    {
        return $this->subject("Shopping list: {$this->listName}")
            ->view('emails.shopping-list-summary');
    }
}
