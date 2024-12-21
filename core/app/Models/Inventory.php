<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class Inventory extends Model
{
    use Searchable;
    
    protected $table = "inventory";
    protected $fillable = ['product_id'];
    protected $casts = [
        'misc' => 'object',
    ];
    
    
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
}