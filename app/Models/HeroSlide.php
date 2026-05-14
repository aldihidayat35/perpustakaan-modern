<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string $image_url
 * @property string|null $link_url
 * @property string $link_text
 * @property int $order
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_url',
        'illustration_image',
        'illustration_type',
        'link_url',
        'link_text',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getIllustrationUrlAttribute(): ?string
    {
        if ($this->illustration_type === 'image' && $this->illustration_image) {
            return asset('storage/' . $this->illustration_image);
        }
        return null;
    }

    /**
     * Scope: hanya slide aktif, diurutkan berdasarkan order asc.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order', 'asc');
    }
}