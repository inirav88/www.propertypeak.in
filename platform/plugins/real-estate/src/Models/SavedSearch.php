<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedSearch extends BaseModel
{
    protected $table = 're_saved_searches';

    protected $fillable = [
        'account_id',
        'name',
        'search_criteria',
        'alert_frequency',
        'is_active',
    ];

    protected $casts = [
        'search_criteria' => 'array',
        'is_active' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function getMatchingProperties()
    {
        $query = Property::query()->published();

        foreach ($this->search_criteria as $key => $value) {
            if (empty($value)) {
                continue;
            }

            switch ($key) {
                case 'location':
                    $query->where('location', 'like', "%{$value}%");
                    break;
                case 'min_price':
                    $query->where('price', '>=', $value);
                    break;
                case 'max_price':
                    $query->where('price', '<=', $value);
                    break;
                case 'type':
                    $query->where('type', $value);
                    break;
                case 'category_id':
                    $query->where('category_id', $value);
                    break;
                case 'bedrooms':
                    $query->where('number_bedroom', '>=', $value);
                    break;
                case 'bathrooms':
                    $query->where('number_bathroom', '>=', $value);
                    break;
            }
        }

        return $query;
    }
}
