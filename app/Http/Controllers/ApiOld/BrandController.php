<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource as ModelResource;
use App\Http\Resources\Brand\BrandCollection as ModelCollection;
use App\Http\Requests\Brand\StoreApiBrandRequest;

/**
 * Class BrandController
 *
 * @package App\Http\Controllers\Api
 */
class BrandController extends Controller
{
    /**
     * @var \App\Brand
     */
    private $brand;

    /**
     * BrandController constructor.
     *
     * @param \App\Brand $brand
     * @param ProductBrand $productBrand
     */
    public function __construct(Brand $brand) {
        $this->brand = $brand;
    }


    public function updateBrands(Request $request){
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $brand = Brand::find($request->input('id'));

        $brand->name = $request->input('name');
        $brand->save();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function index()
    {
        $this->brand->paginate = true;
        $this->brand->limit = request('limit', 100);
        $this->brand->statusId = request('status');
        $this->brand->keywords = request('keywords');
        $items = $this->brand->getAll();

        return new ModelCollection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApiBrandRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreApiBrandRequest $request)
    {
        $input = $request->all();
        $input['slug'] = str_slug($input['name']);

        $brand = $this->brand->updateOrCreate(['name' => $input['name']], $input);

        return new ModelResource($brand);
    }

    /**
     * @param int $id
     * @return \App\Http\Resources\Client\ClientResource
     */
    public function show($id)
    {
        $item = $this->brand->getOne($id);

        return new ModelResource($item);
    }
}