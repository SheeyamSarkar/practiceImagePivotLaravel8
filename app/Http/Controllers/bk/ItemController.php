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

        $subcategories = SubCategory::all();
        //$items = Item::all();
        $items = Item::with('getSubCategory', 'assets')->get();
        $categories = Category::all();
        $assets = AssetType::all();

        //dd($items);
        //$catname= Category::where('id','=',$cid)->get();
        //dd($catname);
        //dd($items);
        $catid = [];
        foreach ($items as  $item) {
            $description  = substr($item->description, 0, 25);
            $item->description = $description;

            $catid[] = $item->getSubCategory->category_id;

            $itemcatname = Category::whereIn('id', $catid)->get();

            foreach ($itemcatname as  $itemcat) {
                //dd($itemcat->name);
                $item->catname = $itemcat->name;
                //$item->new_img = 'product/1717665803368407.png';

            }
        }
        //dd($items);
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

            //"add_photo"    => "required|array",
            //"add_photo.*"  => "required",
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
                //'category_id' => $request->category_id,
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

           
            //dd($item);
            $data = array();
            $data['message'] = ' Item Added Successfully';
            //$data['category_id']  = $item->getCategory->name;
            $data['sub_category_id']  = $item->getSubCategory->name;
            $data['name']  = $item->name;
            $data['asset_type_id'] = $asset_type;
            $data['asset'] = $images;
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
        //dd($item);
        //$assetname = $item->assets;
        //dd($assetname);
        foreach ($item->assets as  $item1) {
            $typeid = $item1->id;
            $typename = $item1->name;
            $imgpath[] = $item1->pivot->asset;
            //dd($imgpath);
        }
        //dd($imgpath);
        $cid = $item->getSubCategory->category_id;
        //dd($cid);
        $catname = Category::where('id', '=', $cid)->first();
        //dd($catname->name);
        $item['category_name'] = $catname->name;
        $item['type_name'] = $typename;
        $item['type_id'] = $typeid;
        $item['images']=$imgpath;

        //$asset_typename= AssetType::where()
        //dd($typename);

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
        //dd($request->all());
        //image_old

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

            $asset_type = $request->asset_type_id;
            //$images=[];
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

            //dd($images);
            // if (!empty($images)) {
            //     foreach ($images as $pic) {
            //         $item->assets()->sync([$asset_type => [
            //             'asset' => $pic,
            //         ]]);
            //     }
            // }

            foreach ($images as $pic) {
                $item_pic[$pic] = [
                    'asset'         => $pic,
                    'asset_type_id' => $asset_type,
                ];
            }
            $item->assets()->sync($item_pic, true);

            //dd($item);

            $data                = array();
            $data['message']     = 'Item updated successfully';
            $data['sub_category_id']  = $item->getSubCategory->name;
            $data['name']       = $item->name;
            $data['description'] = substr($item->description, 0, 25);
            $data['asset_type_id'] = $asset_type;
            $data['image_list']=$images?? "no update";
            $data['id']          = $request->hidden_id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function itemDelete(Request $request)
    {

        //dd($request->id);
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
}
