<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
 
class COACategory extends Model
{
    protected $table = "chart_of_account_categories";
}