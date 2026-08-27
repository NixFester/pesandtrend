<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'content', 'image',
        'read_minutes', 'views', 'published_at',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->orderByDesc('published_at');
    }

    public function getPublishedLabelAttribute(): string
    {
        return $this->published_at?->translatedFormat('j F Y') ?? '';
    }

    /** Ubah markdown ringan (##, **, -) menjadi HTML aman */
    public function getFormattedContentAttribute(): string
    {
        $escaped = e($this->content);

        // Judul ## ...
        $escaped = preg_replace('/^##\s+(.+)$/m', '<h2>$1</h2>', $escaped);
        // Bold **...**
        $escaped = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $escaped);
        // Baris baru menjadi paragraf
        $blocks = preg_split('/\n\n+/', trim($escaped));

        return collect($blocks)
            ->map(fn ($block) => str_starts_with($block, '<h2>')
                ? $block
                : '<p>'.nl2br($block).'</p>')
            ->implode("\n");
    }
}
