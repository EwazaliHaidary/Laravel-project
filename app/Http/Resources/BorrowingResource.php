<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'book_id' => $this->book_id,
            'borrowed_at' => $this->borrowed_at,
            'due_at' => $this->due_at,
            'returned_at' => $this->returned_at,
        ];
    }
}
