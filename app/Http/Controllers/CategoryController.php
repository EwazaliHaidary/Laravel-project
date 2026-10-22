<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()
            ->with('books');

        // Filtering by name
        $query->when($request->query('name'), function ($q, $name) {
            $q->where('name', 'like', "%{$name}%");
        });

        // Searching
        $query->when($request->query('search'), function ($q, $term) {
            $q->where(function ($inner) use ($term) {
                $inner->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        });

        // Sorting
        $sortable = ['name', 'created_at'];

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

        return CategoryResource::collection(
            $query->paginate($perPage)->withQueryString()
        );
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Category $category)
    {
        return new CategoryResource(
            $category->load('books')
        );
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ) {
        $category->update($request->validated());

        return new CategoryResource(
            $category->load('books')
        );
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
