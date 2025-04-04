<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BorrowedBook;

class books extends Model
{
    public function borrowedBy(): HasMany{
        return $this->hasMany(BorrowedBook::class, 'book_id');
    }
}
