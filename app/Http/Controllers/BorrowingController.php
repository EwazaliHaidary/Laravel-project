<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Models\Borrowing;
use App\Http\Resources\BorrowingResource;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::query()
            ->with(['user', 'book']);

        // Filtering
        $query->when($request->query('user_id'), function ($q, $userId) {
            $q->where('user_id', $userId);
        });

        $query->when($request->query('book_id'), function ($q, $bookId) {
            $q->where('book_id', $bookId);
        });

        // Searching by book title
        $query->when($request->query('search'), function ($q, $term) {
            $q->whereHas('book', function ($bookQuery) use ($term) {
                $bookQuery->where('title', 'like', "%{$term}%");
            });
        });

        // Sorting
        $sortable = ['borrowed_at', 'due_at', 'created_at'];

        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        if (!in_array($sort, $sortable, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $query->orderBy($sort, $direction);

        // Pagination control
        $perPage = min(
            100,
            max(1, (int) $request->query('per_page', 10))
        );

        return BorrowingResource::collection(
            $query->paginate($perPage)->withQueryString()
        );
    }

    public function store(StoreBorrowingRequest $request)
    {
        $borrowing = Borrowing::create($request->validated());

        return (new BorrowingResource(
            $borrowing->load(['user', 'book'])
        ))->response()->setStatusCode(201);
    }

    public function show(Borrowing $borrowing)
    {
        return new BorrowingResource(
            $borrowing->load(['user', 'book'])
        );
    }

    public function update(
        UpdateBorrowingRequest $request,
        Borrowing $borrowing
    ) {
        $borrowing->update($request->validated());

        return new BorrowingResource(
            $borrowing->load(['user', 'book'])
        );
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();

        return response()->noContent();
    }
}
