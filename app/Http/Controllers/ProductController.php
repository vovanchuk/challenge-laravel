<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category'])->get();

        return response()->json(ProductResource::collection($products));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json(new ProductResource($product), Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        return response()->json(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->fill($request->validated());

        $product->save();

        return response()->json(new ProductResource($product));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function total()
    {
        $sum = Product::all()->sum('price');
        $rounded = round($sum, 2);

        return response()->json([
            'total' => $rounded
        ]);
    }
}
