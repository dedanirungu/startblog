<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['category_id', 'title', 'slug', 'excerpt', 'body', 'published_at'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Generate a unique slug from the title when the post is first created.
     */
    protected static function booted(): void
    {
        static::creating(function (Post $post): void {
            if (blank($post->slug)) {
                $post->slug = static::uniqueSlugFor($post->title);
            }
        });
    }

    /**
     * Build a slug for the given title that is not already taken.
     */
    public static function uniqueSlugFor(string $title): string
    {
        $baseSlug = Str::slug($title) ?: 'post';
        $slug = $baseSlug;
        $suffix = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the category the post belongs to.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope the query to posts whose publish date has passed.
     */
    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Determine whether the post is publicly visible.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }
}
