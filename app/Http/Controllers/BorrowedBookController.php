<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\books;
use App\Models\BorrowedBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; 

class BorrowedBookController extends Controller
{
   
    public function borrowBook(Request $request){
       //Get the authenticated user
       $user = Auth::user();

       //Validate the request
       $validated = $request->validate([
        'book_id' => ['required', 'exists:books,id'],
       ]);

       //Check if book exists
       $book = books::find($validated['book_id']);
       if (!$book) {
           return response()->json([
               'status' => 404,
               'message' => 'Book not found',
           ], 404);
       }

       //Check if book is already borrowed
       $existingBorrow = BorrowedBook::where('book_id', $validated['book_id'])
       ->whereNull('returned_at')
       ->first();

       if ($existingBorrow) {
       return response()->json([
           'status' => 400,
           'message' => 'This book is currently borrowed by another user and not available.',
       ], 400);}

       //Create a new borrowed book record
       $borrowedBook = BorrowedBook::create([
        'book_id' => $validated['book_id'],
        'user_id' => $user->id,
        'borrowed_at' => now(),
        'returned_at' => null,
         ]);

         return response()->json([
        'status' => '200',
        'message' => 'Book borrowed successfully',
        'borrowed_book' => $borrowedBook,
    ],   200);
}
}
