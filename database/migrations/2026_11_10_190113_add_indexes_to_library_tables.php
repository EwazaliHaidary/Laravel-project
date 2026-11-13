<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Authors indexes
        Schema::table('authors', function (Blueprint $table) {
            $table->index('created_at');
        });

        // Categories indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('name');
        });

        // Books indexes
        Schema::table('books', function (Blueprint $table) {
            $table->index('author_id');
            $table->index('category_id');
            $table->index('title');
            $table->index('published_year');
            $table->index('created_at');
        });

        // Borrowings indexes
        Schema::table('borrowings', function (Blueprint $table) {
            $table->index('book_id');

            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'borrowed_at']);
            $table->index(['user_id', 'due_at']);
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['name']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['author_id']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['title']);
            $table->dropIndex(['published_year']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropIndex(['book_id']);

            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['user_id', 'borrowed_at']);
            $table->dropIndex(['user_id', 'due_at']);
        });
    }
};
