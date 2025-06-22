<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Author extends Model{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['first_name', 'last_name', 'rank', 'avatar_url'];


    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }
}