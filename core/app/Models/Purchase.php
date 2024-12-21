<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class Purchase extends Model
{
    use Searchable;
    
    protected $casts = [
        'misc' => 'object',
    ];
    
    protected $fillable = ['status'];
    
    public function supplier() {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function invoice() {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    
    
}