<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\AssetType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function allItem()
    {

        $subcategories = SubCategory::all();
        //$items = Item::all();
        $items= Item::with('getSubCategory','assets')->get();
        $categories=Category::all();
        $assets=AssetType::all();

        //dd($items);
        //$catname= Category::where('id','=',$cid)->get();
        //dd($catname);
        $catid=[];
        foreach ($items as  $item) {
            $description  = substr($item->description, 0, 25);
            $item->description = $description;

            $catid[]= $item->getSubCategory->category_id;

            $itemcatname= Category::whereIn('id', $catid)->get();

            foreach ($itemcatname as  $itemcat) {
                   //dd($itemcat->name);
                $item->catname = $itemcat->name;

                }
        }
        //dd($items);

        
        return view('admin.product.item', compact('categories','subcategories','items','assets'));

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
            $asset_type= $request->asset_type_id;
            $image     = $request->asset;
            $image1    = $request->asset1;
            $image2    = $request->asset2;
            //dd($image);
            if ($image) {

                $image_name = hexdec(uniqid());
                $image_ext  = strtolower($image->getClientOriginalExtension());

                $image_full_name = $image_name . '.' . $image_ext;
                $image_upload_path2    = 'product/';
                $image_upload_path3    = 'images/product/';
                $image_url       = $image_upload_path2 . $image_full_name;
                $success        = $image->move($image_upload_path3, $image_full_name);
            }

            if ($image1) {

                $image1_name = hexdec(uniqid());
                $image1_ext  = strtolower($image1->getClientOriginalExtension());

                $image1_full_name = $image1_name . '.' . $image1_ext;
                $image_upload_path4     = 'product/';
                $image_upload_path5    = 'images/product/';
                $image1_url       = $image_upload_path4 . $image1_full_name;
                $success        = $image1->move($image_upload_path5, $image1_full_name);
            }

            if ($image2) {

                $image2_name = hexdec(uniqid());
                $image2_ext  = strtolower($image2->getClientOriginalExtension());

                $image2_full_name = $image2_name . '.' . $image2_ext;
                $image_upload_path6     = 'product/';
                $image_upload_path7    = 'images/product/';
                $image2_url       = $image_upload_path4 . $image2_full_name;
                $success        = $image2->move($image_upload_path5, $image2_full_name);
            }



            $pics=[$image_url,$image1_url,$image2_url];
            //dd($pics);

            if(!empty($pics)){
                foreach($pics as $pic){
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
            $data['asset_type_id']=$asset_type;
            $data['asset']=$pics;
            $data['description']=substr($item->description, 0, 25);
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
        $item = Item::with('getSubCategory','assets')->find($request->id);
        //dd($item);
        $cid= $item->getSubCategory->category_id;
        //dd($cid);
        $catname= Category::where('id','=',$cid)->first();
        //dd($catname->name);
        $item['category_name']=$catname->name;
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
            $item  = Item::with('assets')->find($request->hidden_id);
            dd($item);
            $item['sub_category_id'] = $request->sub_category_id;
            $item['name']         = $request->name;
            $item['description']  = $request->description;
            $item->update();


            $asset_type= $request->e_asset_type_id;
            $image = $request->e_asset;
            $image1 = $request->e_asset1;
            $image2 = $request->e_asset2;
            dd($image);
            if ($image) {
                File::delete($item->asset);
                $image_name = hexdec(uniqid());
                $image_ext  = strtolower($image->getClientOriginalExtension());

                $image_full_name = $image_name . '.' . $image_ext;
                $image_upload_path2     = 'product/';
                $image_upload_path3    = 'images/product/';
                $image_url       = $image_upload_path2 . $image_full_name;
                $success        = $image->move($image_upload_path3, $image_full_name);
            }

            if ($image1) {

                $image1_name = hexdec(uniqid());
                $image1_ext  = strtolower($image1->getClientOriginalExtension());

                $image1_full_name = $image1_name . '.' . $image1_ext;
                $image_upload_path4     = 'product/';
                $image_upload_path5    = 'images/product/';
                $image1_url       = $image_upload_path4 . $image1_full_name;
                $success        = $image1->move($image_upload_path5, $image1_full_name);
            }

            if ($image2) {

                $image2_name = hexdec(uniqid());
                $image2_ext  = strtolower($image2->getClientOriginalExtension());

                $image2_full_name = $image2_name . '.' . $image2_ext;
                $image_upload_path6     = 'product/';
                $image_upload_path7    = 'images/product/';
                $image2_url       = $image_upload_path4 . $image2_full_name;
                $success        = $image2->move($image_upload_path5, $image2_full_name);
            }



            //$pics=['1','1','1'];
            $pics=[$image_url,$image1_url,$image2_url];
            //dd($pics);

            if(!empty($pics)){
                foreach($pics as $pic){
                    $item->assets()->sync([$asset_type => [
                    'asset' => $pic,
                ]]);
                }
            }
            dd($pic);


            //dd($item);
            $data                = array();
            $data['message']     ='Item updated successfully';
            $data['sub_category_id']  = $item->getSubCategory->name;
            $data['name']       = $item->name;
           // $data['name']     = $assets->name;
            $data['description']=substr($item->description, 0, 25);
            $data['asset_type_id']=$asset_type;
            $data['asset']       = $pics;
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

    public function getDropDown(Request $request){
        
        $categories=SubCategory::where('category_id', $request->category_id)->get(); 
       //dd($categories);
        return response()->json($categories);

    }
}
