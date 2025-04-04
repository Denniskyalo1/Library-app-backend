<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function addBook(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'image_url' => 'required|url',
            'categories' => 'required|string',
            'overview' => 'required|string',
            
        ]);

        $book = Book::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Book added successfully',
            'book' => $book,
        ], 200);
    }
}
