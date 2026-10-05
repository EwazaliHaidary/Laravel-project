<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Borrowing::with(["user", "book"])
        ->latest()
        ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowingRequest $request)
    {
        $borrowing = Borrowing::create($request->validated());
        return response()->json($borrowing, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        return $borrowing->load(["user", "book"]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBorrowingRequest $request, Borrowing $borrowing)
    {
        $borrowing->update($request->validated());
        return response()->json($borrowing,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)

    {
        $borrowing->delete();
        return response()->noContent();
    }
}
