<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'item_description',
        'amount',
        'status',
        'linked_asset_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    /**
     * Get the asset linked to the procurement.
     */
    public function linkedAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'linked_asset_id');
    }
}
