<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function allSubCategory()
    {

        $categories = Category::all();
        $subcategories = SubCategory::all();

        foreach ($categories as  $category) {
            $description  = substr($category->description, 0, 25);
            $category->description = $description;
        }

        return view('admin.product.subcategory', compact('categories','subcategories'));

    }

    public function subcategoryStore(Request $request)
    {
        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
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

            $subcategory = SubCategory::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status??0,

            ]);
            //dd($subcategory);
            $data = array();
            $data['message'] = ' SubCategory Added Successfully';
            $data['category_id']  = $subcategory->getCategory->name;
            $data['name']  = $subcategory->name;
            $data['description']=substr($subcategory->description, 0, 25);
            $data['status']  = $subcategory->status;
            $data['id'] = $subcategory->id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function subcategoryEdit(Request $request)
    {
        //dd($request->id);
        $subcategory = SubCategory::with('getCategory')->find($request->id);
        if ($subcategory) {
            return response()->json([
                'success' => true,
                'data'    => $subcategory,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data'    => 'No information found',
            ]);
        }
    }

    public function subcategoryUpdate(Request $request)
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
            $subcategory  = SubCategory::find($request->hidden_id);

            /*if($request->has('status')){
                $subcategory['status'] = 'Active';
            }else{
                $subcategory['status'] = 'InActive';
            }*/

            $subcategory['category_id'] = $request->category_id;
            $subcategory['name']         = $request->name;
            $subcategory['description']  = $request->description;
            $subcategory['status']       = $request->status??0;
            $subcategory->update();
            //dd($subcategory);
            $data                = array();
            $data['message']     ='SubCategory updated successfully';
            $data['category_id']  = $subcategory->getCategory->name;
            $data['name']       = $subcategory->name;
            $data['description']=substr($subcategory->description, 0, 25);
            $data['id']          = $request->hidden_id;
            /*$data['status']       = $subcategory->status;*/
            if($subcategory->status==1){
                $data['status']  = 'Active';
            }else{
                $data['status']  = 'InActive';
            }

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function subcategoryDelete(Request $request)
    {

        //dd($request->id);
        $subcategory = SubCategory::findOrFail($request->id);
        if ($subcategory) {
            $subcategory->delete();
            $data            = array();
            $data['message'] = 'SubCategory deleted successfully';
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            $data            = array();
            $data['message'] = 'SubCategory can not deleted!';
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        }
    }
}
