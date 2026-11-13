<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BorrowingController extends Controller
{

   
public function index(Request $request)
{
    $query = Borrowing::query()
        ->with(['user', 'book'])
        ->ownedBy($request->user()->id);

    // Filtering using local scopes
    $query->when($request->query('book_id'), function ($q, $bookId) {
        $q->forBook((int) $bookId);
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

    // Pagination
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
        $data = $request->validated();

        // Assign the borrowing to the authenticated user
        $data['user_id'] = $request->user()->id;

        $borrowing = Borrowing::create($data);

        return (new BorrowingResource(
            $borrowing->load(['user', 'book'])
        ))->response()->setStatusCode(201);
    }

    public function show(Borrowing $borrowing)
    {
        Gate::authorize('view', $borrowing);

        return new BorrowingResource(
            $borrowing->load(['user', 'book'])
        );
    }

    public function update(
        UpdateBorrowingRequest $request,
        Borrowing $borrowing
    ) {
        Gate::authorize('update', $borrowing);

        $data = $request->validated();

        // Prevent changing the borrowing owner
        unset($data['user_id']);

        $borrowing->update($data);

        return new BorrowingResource(
            $borrowing->load(['user', 'book'])
        );
    }

    public function destroy(Borrowing $borrowing)
    {
        Gate::authorize('delete', $borrowing);

        $borrowing->delete();

        return response()->noContent();
    }
}
