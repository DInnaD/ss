<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductSearchRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Services\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends Controller
{
    private $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->service->index();

        return response()->json(ProductResource::collection($products));
        //return new ProductCollection(Product::make($products));
        //return $this->success(ProductCollection::make($products));
    }

    /**
     * Display a filtering of the resource.
     *
     * @param ProductSearchRequest $request
     * @return JsonResponse
     */
    public function filter(ProductSearchRequest $request): JsonResponse
    {
        $product = $this->service->filter($request->validated());

        return response()->json(ProductCollection::make($product));
        //return $this->success(ProductCollection::make($product));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductStoreRequest  $request
     * @return JsonResponse
     */
    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = $this->service->store($request->validated());
        return $this->created(ProductResource::make($product));
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        // $data = $this->service->show($product);
        // return $this->success($data);
        $product = $product->load('comments');//hasMany

        return response()->json(ProductResource::make($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductUpdateRequest  $request
     * @param  Product  $product
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        $product = $this->service->update($request->validated(), $product);
        return $this->success(ProductResource::make($product));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return JsonResponse
     * @throws /Exception
     */
    public function destroy(Product $product): JsonResponse
    {
        $product = $this->service->destroy($product);

        return response()->json(['success' => true], 200);
        //return $this->success(ProductResource::make($product));
    }
}
