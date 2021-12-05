<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['sub_category_id','name','description'];

    public function getSubCategory(){
         return $this->belongsTo(SubCategory::class,'sub_category_id');
    }
    public function getCategory(){
         return $this->belongsTo(Category::class,'category_id');
    }
    
    public function assets(){
         return $this->belongsToMany(AssetType::class,'asset_items','item_id','asset_type_id')->withPivot('id','asset');
    }

    
}
