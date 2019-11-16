<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use stdClass;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = auth('api')->user()->store->products;
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!is_dir('storage/product_photo/')) {
            mkdir('storage/product_photo/');
        }

        $store_id = auth('api')->user()->store->id;
        $images = [];
        foreach ($request['files'] as $file) {
            $name = 'store-' . $store_id . '-' . uniqid() . '.' . explode('/', explode(':', substr($file['url'], 0, strpos($file['url'], ';')))[1])[1];
            // Resized image
            $img = \Image::make($file['url']);
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            });
            // Canvas image
            $canvas = \Image::canvas(500, 500);
            $canvas->insert($img, 'center');
            $canvas->save(public_path('storage/product_photo/') . $name);
            $app = app();
            $object = $app->make('stdClass');
            $object->file = $name;
            array_push($images, $object);
        }

        $store = Product::create([
            'store_id' => $store_id,
            'name' => $request['name'],
            'category' => $request['category'],
            'stocks' => $request['stocks'],
            'unit' => $request['unit'],
            'price' => $request['price'],
            'description' => $request['description'],
            'photos' => $images,
            'public' => $request['public'],
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
