<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProductCategory;
use App\Models\ProductGlobalPrice;
use App\Models\ProductOutletPrice;
use App\Models\ProductOutletStock;
use App\Models\TreatmentOutlet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\TreatmentPatient;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'product_category_id',
        'product_code',
        'product_name',
        'product_groups',
        'type',
        'description',
        'image',
        'is_active',
        'need_recipe_status',
    ];

    public function product_category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function scopeProduct(Builder $query): Builder
    {
        return $query->whereNotNull('product_category_id')->where('type', 'Product');
    }

    public function scopeTreatment(Builder $query): Builder
    {
        return $query->whereNull('product_category_id')->where('type', 'Treatment');
    }

    public function global_price(): HasOne
    {
        return $this->hasOne(ProductGlobalPrice::class);
    }

    public function outlet_price(): HasMany
    {
        return $this->hasMany(ProductOutletPrice::class);
    }

    public function outlet_stock(): HasMany
    {
        return $this->hasMany(ProductOutletStock::class);
    }

    public function outlet_treatment(): HasMany
    {
        return $this->hasMany(TreatmentOutlet::class, 'treatment_id', 'id');
    }

    public function treatment_patients(): HasMany
    {
        return $this->hasMany(TreatmentPatient::class, 'treatment_id', 'id');
    }

    public function product_package(): HasMany
    {
        return $this->hasMany(ProductPackage::class, 'package_id', 'id');
    }
}
