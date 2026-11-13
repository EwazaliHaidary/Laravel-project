<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_at',
        'returned_at',
    ];
    protected function casts(): array
{
    return [
        'borrowed_at' => 'date',
        'due_at' => 'date',
        'returned_at' => 'date',
    ];
}

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Filter borrowings by owner
    public function scopeOwnedBy(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Filter borrowings by book
    public function scopeForBook(Builder $query, int $bookId): Builder
    {
        return $query->where('book_id', $bookId);
    }
}
