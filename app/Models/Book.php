<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'isbn',
        'published_year',
        'author_id',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'published_year' => 'integer',
        ];
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Filter books by author
    public function scopeByAuthor(Builder $query, int $authorId): Builder
    {
        return $query->where('author_id', $authorId);
    }

    // Filter books by category
    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }
}
