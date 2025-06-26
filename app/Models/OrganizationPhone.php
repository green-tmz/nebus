<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationPhone extends Model
{
    protected $fillable = [
        'organization_id',
        'phone'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'organization_id'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
