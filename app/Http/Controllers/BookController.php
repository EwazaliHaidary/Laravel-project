<?php
// Fixed: this opening tag must be the first bytes in the file. The previous
// leading blank line was sent as output, so PHP rejected the namespace below.
namespace App\Http\Controllers;
use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query()
            ->with(['author', 'category']);

        // Filtering
        $query->when($request->query('category_id'), function ($q, $categoryId) {
            $q->where('category_id', $categoryId);
        });

        $query->when($request->query('author_id'), function ($q, $authorId) {
            $q->where('author_id', $authorId);
        });

        // Searching
        $query->when($request->query('search'), function ($q, $term) {
            $q->where(function ($inner) use ($term) {
                $inner->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('isbn', 'like', "%{$term}%");
            });
        });

        // Sorting
        $sortable = ['title', 'published_year', 'created_at'];

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
            max(1, (int) $request->query('per_page', 15))
        );

        return BookResource::collection(
            $query->paginate($perPage)->withQueryString()
        );
    }

    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        return (new BookResource($book))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Book $book)
    {
        return new BookResource(
            $book->load(['author', 'category'])
        );
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return new BookResource(
            $book->load(['author', 'category'])
        );
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->noContent();
    }
}
