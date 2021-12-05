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



            $pics = [$image_url, $image1_url, $image2_url];
            //dd($pics);

            if (!empty($pics)) {
                foreach ($pics as $pic) {

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
            $data['asset'] = $pics;
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
            $imgpath[] = $item1->pivot->asset;
            //dd($imgpath);
        }
        //dd($imgpath);
        $cid = $item->getSubCategory->category_id;
        //dd($cid);
        $catname = Category::where('id', '=', $cid)->first();
        //dd($catname->name);
        $item['category_name'] = $catname->name;
        $item['img_path'] = $imgpath[0];
        $item['img_path1'] = $imgpath[1];
        $item['img_path2'] = $imgpath[2];
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
            //dd($asset_type);
            // $image  = $request->e_asset;
            // $image1 = $request->e_asset1;
            // $image2 = $request->e_asset2;
             //dd($request->image );
            foreach (array($request->image[0],$request->image[1],$request->image[2])   as $im) {
                 //dd($im);
                //$image2 = $im['image']?? 'sdf.png';
                //dd($image2);
                if ($request->file( $im ) ) {
                    File::delete($img[0]);
                    $image2_name = hexdec(uniqid());
                    $image2_ext  = strtolower($request->file( $im )->getClientOriginalExtension());

                    $image2_full_name = $image2_name . '.' . $image2_ext;
                    $image_upload_path6     = 'product/';
                    $image_upload_path7    = 'images/product/';
                    $image2_url       = $image_upload_path6 . $image2_full_name;
                    $success        =  $request->file( $im )->move($image_upload_path7, $image2_full_name);
                } else {
                    $image2_url = 'product/default.png';
                }
                //dd($image2);
                $item_pic[$im['id']] = [
                    'asset'       => $image2_url,
                    'asset_type_id'     => $asset_type,
                ];
                $item->assets()->sync($item_pic, true);
                //dd($image2);
            }
            // foreach ($request->image as $im) {
            //      //dd($im);
            //     $image2 = $im['image']?? 'sdf.png';
            //     //dd($image2);
            //     if ($image2) {
            //         File::delete($img[0]);
            //         $image2_name = hexdec(uniqid());
            //         $image2_ext  = strtolower($image2->getClientOriginalExtension());

            //         $image2_full_name = $image2_name . '.' . $image2_ext;
            //         $image_upload_path6     = 'product/';
            //         $image_upload_path7    = 'images/product/';
            //         $image2_url       = $image_upload_path6 . $image2_full_name;
            //         $success        = $image2->move($image_upload_path7, $image2_full_name);
            //     } else {
            //         //$image2_url = 'ABC';
            //     }
            //     //dd($image2);
            //     $item_pic[$im['id']] = [
            //         'asset'       => $image2_url ?? 'ABCD',
            //         'asset_type_id'     => $asset_type,
            //     ];
            //     $item->assets()->sync($item_pic, true);
            //     //dd($image2);
            // }
            //dd($im);
            /*if ($image) {
                File::delete($img[0]);
                $image_name = hexdec(uniqid());
                $image_ext  = strtolower($image->getClientOriginalExtension());

                $image_full_name = $image_name . '.' . $image_ext;
                $image_upload_path2     = 'product/';
                $image_upload_path3    = 'images/product/';
                $image_url       = $image_upload_path2 . $image_full_name;
                $success        = $image->move($image_upload_path3, $image_full_name);
            }else {
                $image_url = $img;
            }

            if ($image1) {
                File::delete($img[1]);
                $image1_name = hexdec(uniqid());
                $image1_ext  = strtolower($image1->getClientOriginalExtension());

                $image1_full_name = $image1_name . '.' . $image1_ext;
                $image_upload_path4     = 'product/';
                $image_upload_path5    = 'images/product/';
                $image1_url       = $image_upload_path4 . $image1_full_name;
                $success        = $image1->move($image_upload_path5, $image1_full_name);
            }else {
                $image1_url = $img;
            }

            if ($image2) {
                File::delete($img[1]);
                $image2_name = hexdec(uniqid());
                $image2_ext  = strtolower($image2->getClientOriginalExtension());

                $image2_full_name = $image2_name . '.' . $image2_ext;
                $image_upload_path6     = 'product/';
                $image_upload_path7    = 'images/product/';
                $image2_url       = $image_upload_path6 . $image2_full_name;
                $success        = $image2->move($image_upload_path7, $image2_full_name);
            }else {
                $image2_url = $img;
            }*/

            /*$pics=[$image_url,$image1_url,$image2_url];
            

            if(!empty($pics)){
                foreach($pics as $key=> $pic){
                    $item_pic[] = [
                    'asset'       => $pic,
                    'asset_type_id'     => $asset_type,
                
                ];
                //dd($item_pic);
                    

                    /*$item->assets()->sync([$asset_type => [
                    'asset' => $pic,
                ]]);*/
            //}
            //$item->assets()->sync($item_pic,true);
            //return $item_pic;
            //}*/
            //dd($pic);
//  foreach ($images as $pic) {
                
// $item_pic[$pic['']] = [
//     'asset'       => $images,
//     'asset_type_id'     => $asset_type,
// ];

// }
// $item->assets()->sync($item_pic, true);

            //dd($item);
            $data                = array();
            $data['message']     = 'Item updated successfully';
            $data['sub_category_id']  = $item->getSubCategory->name;
            $data['name']       = $item->name;
            // $data['name']     = $assets->name;
            $data['description'] = substr($item->description, 0, 25);
            $data['asset_type_id'] = $asset_type;
            // $data['asset']       = $pics[0];
            // $data['asset1']       = $pics[1];
            // $data['asset2']       = $pics[2];
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
