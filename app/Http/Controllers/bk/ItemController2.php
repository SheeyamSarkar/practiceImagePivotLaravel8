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
            

            //dd($item);
            $data = array();
            $data['message'] = ' Item Added Successfully';
            //$data['category_id']  = $item->getCategory->name;
            $data['sub_category_id']  = $item->getSubCategory->name;
            $data['name']  = $item->name;
            $data['asset_type_id'] = $asset_type;
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
        //dd($request->id);
        $item = Item::with('getSubCategory', 'assets')->find($request->id);
        //dd($item);
        //$assetname = $item->assets;
        //dd($assetname);
        foreach ($item->assets as  $item1) {
            $typeid = $item1->id;
            $typename = $item1->name;
            //$imgpath[] = $item1->pivot->asset;
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
            //$item  = Item::find($request->hidden_id);
            $item  = Item::with('assets')->find($request->hidden_id);
            //$img=[];
            foreach ($item->assets as  $item1) {
                $img[] = $item1->pivot->asset;
                //dd($img);
            }
            //dd($img);
            //dd($item->image);

            $item['sub_category_id'] = $request->sub_category_id;
            $item['name']            = $request->name;
            $item['description']     = $request->description;
            $item->update();


            $asset_type = $request->asset_type_id;
            
            
            $data                = array();
            $data['message']     = 'Item updated successfully';
            $data['sub_category_id']  = $item->getSubCategory->name;
            $data['name']       = $item->name;
            $data['description'] = substr($item->description, 0, 25);
            $data['asset_type_id'] = $asset_type;
            
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
