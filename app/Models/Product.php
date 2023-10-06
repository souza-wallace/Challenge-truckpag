<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{

    use Searchable;

    protected $fillable = [
        //'code',
        //'created_t',
        //'last_modified_t',
        //'imported_t',
        'status',
        'url',
        'creator',
        'product_name',
        'quantity',
        'brands',
        'categories',
        'labels',
        'cities',
        'purchase_places',
        'stores',
        'ingredients_text',
        'traces',
        'serving_size',
        'serving_quantity',
        'nutriscore_score',
        'nutriscore_grade',
        'main_category',
        'image_url'
    ];


    public function toSearchableArray(): array
    {
        return [
        'code' => $this->code,
        'status' => $this->status,
        'product_name' => $this->product_name,
        'categories' => $this->categories,
        ];
    }
}
