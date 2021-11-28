<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    /*public function getAssetItem(){
         return $this->belongsToMany(AssetItem::class, 'asset_type_id');
    }*/
    public function getAssetItemtypes(){
         return $this->belongsToMany(AssetItem::class);
    }
}
