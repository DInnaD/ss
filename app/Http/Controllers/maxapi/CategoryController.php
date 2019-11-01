<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
//use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\JsonResponse;//????????


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)//: JsonResponse
    {
        $categories = Category::where('parent_id', null)->with('subCategories')->get();

        //return $this->success(CategoryCollection::make($category));
        return new CategoryCollection($categories); 
        //return response()->json(CategoryResource::collection($categories));//parent::
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CategoryStoreRequest  $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return $this->created(CategoryResource::make($category));
    }

    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        $category = $category->load('subCategories');

        $category = $category->load('products');
        //return new CategoryResource($category);
        return response()->json($category);//???????like product????
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CategoryUpdateRequest  $request
     * @param  Category  $category
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return $this->response()->json(CategoryResource::make($category));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['success' => true], 200);

        //return $this->successDelete(CategoryResource::make($category));
    }
}
