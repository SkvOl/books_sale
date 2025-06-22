<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Author;
use App\Models\Sale;

class Book extends Model{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title', 'description', 'cover_url', 'price', 'quantity'];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }
}