<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(CategoryResource::collection(Category::all()));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json(new CategoryResource($category), 201);
    }

    public function show(Category $category)
    {
        return response()->json(new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->fill($request->validated());
        $category->save();

        return response()->json(new CategoryResource($category));
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response('', 204);
    }
}
