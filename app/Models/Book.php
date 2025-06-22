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
    
    public function authors_popular(): BelongsToMany
    {
        return $this->authors()->where('rank', '>=', '75');
    }

    public function authors_with_photo(): BelongsToMany
    {
        return $this->authors()->whereNotNull('avatar_url');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function book_popular_today(): HasMany
    {
        return $this->sales()->whereDate('created_at', date('Y-m-d 00:00:00'))->groupBy('book_id')->havingRaw('COUNT(sales.id) > ?', [3]);
    }
}