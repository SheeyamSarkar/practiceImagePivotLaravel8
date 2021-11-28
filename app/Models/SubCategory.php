<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = ['category_id','name','description','status'];
    public function getCategory(){
         return $this->belongsTo(Category::class,'category_id');
     }
}
