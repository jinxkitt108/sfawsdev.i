<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;

class StoreController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store = auth('api')->user()->store; 
        return $store;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'country' => 'required',
            'region' => 'nullable',
            'city' => 'required',
            'street' => 'required',
            'description' => 'required',
            'photo' => 'nullable'
        ]);

        if ($request->photo) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            $dir = 'storage/store_photo/';
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            \Image::make($request->cover_image)->save(public_path('storage/store_photo/') . $name);
        } else {
            $name = 'store.png';
        }

        $store = Store::create([
            'owner_id' => auth('api')->user()->id,
            'name' => $request['name'],
            'country' => $request['country'],
            'region' => $request['region'],
            'city' => $request['city'],
            'street' => $request['street'],
            'description' => $request['description'],
            'photo' => $name,
        ]);
        return $store;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    // UPDATING THE PROFILE PHOTO
    public function updatePhoto(Request $request)
    {
        $store = auth('api')->user()->store;
        $currentPhoto = $store->photo;
        $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
        if(!is_dir('storage/store_photo/')) {
            mkdir('storage/store_photo/');
        }
            \Image::make($request->photo)->save(public_path('storage/store_photo/').$name);
            $request->merge(['photo' => $name]);

             //Deleting current store photo
            if($currentPhoto != 'store.png'){
                $store_photo = public_path('storage/store_photo/').$currentPhoto;
                if(file_exists($store_photo)){
                    @unlink($store_photo);
                }
            }
        $store->update($request->all());

        return $name;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
