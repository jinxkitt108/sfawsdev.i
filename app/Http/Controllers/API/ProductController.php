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
        $products = auth('api')->user()->store->products;
        return $products;
    }

    public function showAll()
    {
        return Product::where('public', 1)->latest()->paginate(10);
    }

    public function showLatest()
    {
        return Product::where('public', 1)->limit(12)->get();
    }

    public function getVegetables()
    {
        return Product::where('public', 1)->where('category', 'Vegetables')->latest()->paginate(10);
    }

    public function getFruits()
    {
        return Product::where('public', 1)->where('category', 'Fruits')->latest()->paginate(10);
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
        foreach ($request['photos'] as $file) {
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
        $product = Product::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'category' => 'required',
            'stocks' => 'required',
            'unit' => 'required',
            'price' => 'required',
            'description' => 'required',
            'photos' => 'required',
        ]);

        $images = [];
        foreach ($request['photos'] as $file) {
            if (array_key_exists('id', $file)) {
                $store_id = auth('api')->user()->store->id;
                $name = 'store-' . $store_id . '-' . uniqid() . '.' . explode('/', explode(':', substr($file['url'], 0, strpos($file['url'], ';')))[1])[1];

                // Resized image
                $img = \Image::make($file['url']);
                $img->resize(500, 500, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Canvas image
                $img->resizeCanvas(500, 500, 'center', false, array(255, 255, 255, 0));
                $img->save(public_path('storage/product_photo/') . $name);
                $app = app();
                $object = $app->make('stdClass');
                $object->file = $name;
                array_push($images, $object);
            } else {
                if (array_key_exists('file', $file)) {
                    array_push($images, $file);
                }
            }
            if (array_key_exists('remove', $file)) {
                $remove = $file['remove'];
                $path = public_path('storage/product_photo/') . $remove;
                if (\File::exists($path)) {
                    \File::delete($path);
                }
            }
        }
        $request->merge(['photos' => $images]);
        $product->update($request->all());
        return ['message' => 'Product info updated!'];
    }

    public function togglePublic(Request $request)
    {

        if ($request['public']) {
            $product = Product::where('id', $request['id']);
            $product->update(['public' => false]);
            dd('Product is hidden to public');
        } else {
            $product = Product::where('id', $request['id']);
            $product->update(['public' => true]);
            dd('Product is shown to public');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $delete = $product->delete();
        if ($delete) {
            foreach ($product->photos as $photo) {
                $file = $photo['file'];
                $path = public_path('storage/product_photo/') . $file;
                if (\File::exists($path)) {
                    \File::delete($path);
                } else {
                    dd('File does not exist');
                }
            }
        }

        return ['message' => 'Product deleted!'];
    }
}
