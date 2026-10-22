<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        "id" => $this->id,
        "title" => $this->title,
        "description" => $this->description,
        "isbn" => $this->isbn,
        // "published_year" => $this->published_year,
        "author_id"=> $this->author_id,
        "category_id" => $this->category_id,
        ];
    }
}
