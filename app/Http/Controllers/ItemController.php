<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\AssetType;
use App\Models\AssetItem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function allItem()
    {
        
        $items = Item::with('getSubCategory', 'assets')->get();
        $subcategories = SubCategory::all();
        $categories = Category::all();
        $assets = AssetType::all();
        //dd($items);
        $catid = [];
        
        foreach ($items as  $item) {
            
            $description  = substr($item->description, 0, 25);
            $item->description = $description;

            $catid[] = $item->getSubCategory->category_id;

            $itemcatname = Category::whereIn('id', $catid)->get();
            //Category Name Fetch
            $cat_name=[];
            foreach ($itemcatname as  $itemcat) {
                $item->catname = $itemcat->name;
                $item['item_name']=$item->catname;

            }
            //dd($cat_name);

            
            //Image Fetch
            foreach($item->assets as $assets_pivot){
               $image_path=$assets_pivot->pivot->asset;
            }
            $item['image_path']=$image_path;
        }
        //dd($item->catname);
        


        return view('admin.product.item', compact('categories', 'subcategories', 'items', 'assets'));
    }

    public function itemStore(Request $request)
    {
        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'asset_type_id' => 'required',
            'name' => 'required|max:100',

            "add_photo"    => "required|array",
            "add_photo.*"  => "required",
        ]);

        if ($validator->fails()) {

            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {

            $item = Item::create([
                'sub_category_id' => $request->sub_category_id,
                'name' => $request->name,
                'description' => $request->description,
            ]);
            //dd($item);
            $asset_type = $request->asset_type_id;
            
            if($files=$request->file('add_photo')){
                foreach($files as $file){

                    $image_name = hexdec(uniqid());
                    $image_ext=strtolower($file->getClientOriginalName());
                    $image_full_name = $image_name . '.' . $image_ext;
                    $image_upload_path2     = 'product/';
                    $image_upload_path3    = 'images/product/';
                    $images[]       = $image_upload_path2 . $image_full_name;
                    $success = $file->move($image_upload_path3, $image_full_name);
                }
            }


            if (!empty($images)) {
                foreach ($images as $pic) {

                    $item->assets()->attach([$asset_type => [
                        'asset' => $pic,
                    ]]);
                }
            }
            $cid=$request->category_id;

            $catname = Category::where('id', '=', $cid)->first();

    
            //dd($item);
            $data = array();
            $data['message'] = ' Item Added Successfully';  
            $data['category_name'] = $catname->name;
            $data['sub_category_id']  = $item->getSubCategory->name;
            $data['name']  = $item->name;
            $data['asset_type_id'] = $asset_type;
            $data['asset'] = $images[0];
            $data['description'] = substr($item->description, 0, 25);
            $data['id'] = $item->id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function itemEdit(Request $request)
    {
        //dd($request->all());
        $item = Item::with('getSubCategory', 'assets')->find($request->id);

        foreach ($item->assets as  $item1) {
            $typeid = $item1->id;
            $typename = $item1->name;
        }

        //dd($item);
        //category Id
        $cid = $item->getSubCategory->category_id;
        //dd($cid);

        $sub_cat=SubCategory:: where('category_id', $cid)->get();
        //dd($sub_cat);
        $catname = Category::where('id', '=', $cid)->first();

        $image_infos=AssetItem::where('item_id',$request->id)->get();
        //dd($image_infos);
        $item['category_name'] = $catname->name;
        $item['type_name'] = $typename;
        $item['type_id'] = $typeid;
        $item['Image_assets']=$image_infos;
        $item['sub_cat']=$sub_cat;

        
        if ($item) {
            return response()->json([
                'success' => true,
                'data'    => $item,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data'    => 'No information found',
            ]);
        }
    }

    public function itemUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name'       => 'required|max:100',
        ]);
        if ($validator->fails()) {
            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {

            $item  = Item::with('assets')->find($request->hidden_id);

            $item['sub_category_id'] = $request->sub_category_id;
            $item['name']            = $request->name;
            $item['description']     = $request->description;
            $item->update();

            foreach ($item->assets as  $items) {
                $item['image']= $items->pivot->asset;
                $abc[]=$item['image'];
            }
            //dd($abc);

            $asset_type = $request->asset_type_id;
            $images=[];
            if($files=$request->file('edit_photo')){
                foreach($files as $file){

                    $image_name = hexdec(uniqid());
                    $image_ext=strtolower($file->getClientOriginalName());
                    $image_full_name = $image_name . '.' . $image_ext;
                    $image_upload_path2     = 'product/';
                    $image_upload_path3    = 'images/product/';
                    $images[]       = $image_upload_path2 . $image_full_name;
                    $success = $file->move($image_upload_path3, $image_full_name);
                }
            }

            if($request->edit_photo!=null){
                foreach ($images as $pic) {
                    $array[$pic] = [
                        'asset'         => $pic,
                        'asset_type_id' => $asset_type,
                    ];
                }
            
                // $item->assets()->sync($array);
                $item->assets()->attach($array);

            }else{
                $images=$abc;
            }
    

           $itemcatname = Category::where('id', $request->category_id)->get();
            //Category Name Fetch
            foreach ($itemcatname as  $itemcat) {
                $item->catname = $itemcat->name;

            }     

            //dd($images);
            $data                = array();
            $data['message']     = 'Item updated successfully';
            $data['sub_category_id']  = $item->getSubCategory->name;
            $data['name']       = $item->name;
            $data['category_name']       = $item->catname;
            $data['description'] = substr($item->description, 0, 25);
            $data['asset_type_id'] = $asset_type;
            $data['image_list']=$images;
            $data['id']          = $request->hidden_id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function itemDelete(Request $request)
    {
        $item = Item::findOrFail($request->id);
        if ($item) {
            $item->delete();
            $data            = array();
            $data['message'] = 'Item deleted successfully';
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            $data            = array();
            $data['message'] = 'Item can not deleted!';
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        }
    }

    public function getDropDown(Request $request)
    {

        $categories = SubCategory::where('category_id', $request->category_id)->get();
        //dd($categories);
        return response()->json($categories);
    }

    public function deleteImage(Request $request)
    {
        //dd($request->all());
        $item = AssetItem::findOrFail($request->id);
        //dd($item);

        if ($item) {
            $item->delete();
            File::delete('images/' . $item->asset);
            $data            = array();
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }else {
        }
    }
}
