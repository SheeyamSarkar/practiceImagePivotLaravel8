<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetType;
use Illuminate\Support\Facades\Validator;

class AssetTypeController extends Controller
{
    public function allAssetType()
    {

        $assets = AssetType::all();

        return view('admin.product.assettype', compact('assets'));

    }

    public function assetStore(Request $request)
    {
        //dd($request->all());

        $validator = Validator::make($request->all(), [
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

            $asset = AssetType::create([
                //db column name =>filed name
                'name' => $request->name,

            ]);
            //dd($asset);
            $data = array();
            $data['message'] = ' AssetType Added Successfully';
            $data['name']  = $asset->name;
            $data['id'] = $asset->id;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function assetEdit(Request $request)
    {
        //dd($request->id);
        $asset = AssetType::find($request->id);
        if ($asset) {
            return response()->json([
                'success' => true,
                'data'    => $asset,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data'    => 'No information found',
            ]);
        }
    }

    public function assetUpdate(Request $request)
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
            $asset  = AssetType::find($request->hidden_id);

            $asset['name']         = $request->name;
            $asset->update();

            $data                = array();
            $data['message']     = 'AssetType updated successfully';
            $data['name']       = $asset->name;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }

    public function assetDelete(Request $request)
    {

        //dd($request->id);
        $asset = AssetType::findOrFail($request->id);
        if ($asset) {
            $asset->delete();
            $data            = array();
            $data['message'] = ' AssetType deleted successfully';
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            $data            = array();
            $data['message'] = ' AssetType can not deleted!';
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        }
    }
}
