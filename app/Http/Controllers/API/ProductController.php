<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!is_dir('storage/product_photo/')) {
            mkdir('storage/product_photo/');
        }
            foreach($request->photos as $photo){
                $url = trim( $photo['url'], 'blob:');
                $image = file_get_contents($url);
                $img = str_replace('data:image/png;base64,', '', $image);
            }
            
            return $image ;
        // $store = Store::create([
        //     'store_id' => auth('api')->user()->store->id,
        //     'name' => $request['name'],
        //     'category' => $request['country'],
        //     'stocks' => $request['stocks'],
        //     'unit' => $request['unit'],
        //     'price' => $request['price'],
        //     'measure' => $request['measure'],
        //     'description' => $request['description'],
        //     'photos' => $request['photos'],
        //     'public' => $request['public'],
        // ]);
        
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
