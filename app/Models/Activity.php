<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'level'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class);
    }

    public function getDescendantsAndSelf(): Collection
    {
        $activities = collect([$this]);

        foreach ($this->children as $child) {
            if ($child->level <= 3) {
                $activities = $activities->merge($child->getDescendantsAndSelf());
            }
        }

        return $activities;
    }
}
