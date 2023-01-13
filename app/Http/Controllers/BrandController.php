<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\PhotoHelper;
use App\Brand;

/**
 * Class BrandController
 *
 * @package App\Http\Controllers
 */
class BrandController extends Controller
{
	use PhotoHelper;

    /**
     * @var \App\Brand
     */
    private $brand;
    
    /**
     * BrandController constructor.
     *
     * @param \App\Brand $Brand
     */
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
		
        $this->middleware('auth');
        $this->middleware('acl:view-brand', ['only' => ['index']]);
        $this->middleware('acl:create-brand', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-brand', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->brand->paginate = true;
        $this->brand->statusId = request('status');
        $this->brand->keywords = request('keywords');
        $items = $this->brand->relation(['rStatus'])->getAll();
        
        return view('brand.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('brand.form')
                ->with('item', $this->brand)
                ->with('method', 'post')
                ->with('form_url', route('brand.store'))
                ->with('form_title', trans('brand.actions.create'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Brand\StoreBrandRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreBrandRequest $request)
    {
        // Get form data
        $input = $request->except(['_token', '_method', 'logo']);
        $input['slug'] = str_slug($input['name']);
		
        $photo = $this->upload('logo', $input['slug'], config('picture.brand_path'), $request);
        
        if($photo != null)
        {
            $input['logo'] = basename($photo);
            
            $this->makePhotoThumbs(config('picture.brand_path') , $photo, config('picture.brand_thumbs'), 0);
        }
        
        // Save Brand
        $brand = $this->brand->add($input);
     
        return $this->getStoreJsonResponse($brand, 'brand._row', trans('brand.notifications.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->brand->getOne($id);
		
        return view('brand.form')
                ->with('method', 'put')
                ->with('form_url', route('brand.update', [$id]))
                ->with('form_title', trans('brand.actions.edit'))
                ->with('item', $item);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Brand\UpdateBrandRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateBrandRequest $request, $id)
    {
        $input = $request->except(['_token', '_method', 'logo']);
        $input['slug'] = str_slug($input['name']);

        $photo = $this->upload('logo', $input['slug'], config('picture.brand_path'), $request);
        
        if($photo != null)
        {
            $input['logo'] = basename($photo);
            
            $this->makePhotoThumbs(config('picture.brand_path') , $photo, config('picture.brand_thumbs'), 0);
        }
        
        // Change Brand data
        $brand = $this->brand->edit($id, $input);
        
        return $this->getUpdateJsonResponse($brand, 'brand._row', trans('brand.notifications.updated'));
        
    }
}
