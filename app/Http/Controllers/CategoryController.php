<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Category\StoreCategoryTranslationRequest;
use App\Category;
use App\CategoryTranslation;
use App\PhotoHelper;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    use PhotoHelper;

    /**
     * @var \App\Category
     */
    private $category;

    /**
     * @var \App\CategoryTranslation
     */
    private $categoryTranslation;
    
    /**
     * CategoryController constructor.
     *
     * @param \App\Category $Category
     */
    public function __construct(Category $category, CategoryTranslation $categoryTranslation)
    {
        $this->category = $category;
        $this->categoryTranslation = $categoryTranslation;
		
        $this->middleware('auth');
        $this->middleware('acl:view-category', ['only' => ['index']]);
        $this->middleware('acl:create-category', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-category', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $langId = request()->get('lang_id', config('app.locale'));
        $statusId = request()->get('status');

        $this->category->langId = $langId;
        $this->category->statusId = $statusId;
        $items = $this->category->relation(['rStatus'])->getCategoryTree();
        
        return view('category.index', array(
            'items' => $items,
            'lang_id' => $langId
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->category->langId = 'bs';
        $categories = $this->category->getCategoryTree();
        
        return view('category.form')
                ->with('item', $this->category)
                ->with('categories', $categories)
                ->with('method', 'post')
                ->with('form_url', route('category.store'))
                ->with('form_title', trans('category.actions.create'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Category\StoreCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreCategoryRequest $request)
    {
        // Get form data
        $input = $request->all();

        $categoryInput = [
            'father_id' => $input['father_id'],
            'list_of_parents' => 1,
            'priority' => $input['priority'],
            'status' => $input['status']
        ];

        $translationInput = [
            'category_id' => null,
            'lang_id' => $input['lang_id'],
            'name' => $input['name'],
            'description' =>  $input['description']
        ];

        $photo = $this->upload('photo', $translationInput['name'], config('picture.category_path'), $request);

        if($photo != null)
        {
            $categoryInput['photo'] = basename($photo);
            
            $this->makePhotoThumbs(config('picture.category_path'), $photo, config('picture.category_thumbs'), 0);
        }
        
        // Save Category
        $category = $this->category->add($categoryInput);

        // Save Translation
        $category->translations()->save(new CategoryTranslation($translationInput));

        // Update slug and path, rebuild treee
        $this->category->updateListOfParents($category);
        //$this->categoryTranslation->updatePathAndSlug($category, $translationInput, $this->category);
        $this->category->rebuildTree();

        return $this->getStoreJsonResponse($category, 'category._row', trans('category.notifications.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->category->getCatInfo($id);
        
        $this->category->langId = 'bs';
        $this->category->state = null;
        $categories = $this->category->getCategoryTree();
        
        return view('category.form')
                ->with('method', 'put')
                ->with('form_url', route('category.update', [$id]))
                ->with('form_title', trans('category.actions.edit'))
                ->with('categories', $categories)
                ->with('item', $item);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Category\UpdateCategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        // Get form data
        $input = $request->all();

        $categoryInput = [
            'father_id' => $input['father_id'],
            'list_of_parents' => 1,
            'priority' => $input['priority'],
            'status' => $input['status']
        ];

        $translationInput = [
            'category_id' => $id,
            'lang_id' => $input['lang_id'],
            'name' => $input['name'],
            'description' =>  $input['description']
        ];
		
        $photo = $this->upload('photo', $translationInput['name'], config('picture.category_path'), $request);

        if($photo != null)
        {
            $categoryInput['photo'] = basename($photo);
            
            $this->makePhotoThumbs(config('picture.category_path'), $photo, config('picture.category_thumbs'), 0);
        }

        $category = $this->category->edit($id, $categoryInput);

        $this->categoryTranslation->editTranslation($translationInput);

        $this->category->updateListOfParents($category);

        //$this->categoryTranslation->updatePathAndSlug($category, $translationInput, $this->category);

        $this->category->rebuildTree();
        
        return $this->getUpdateJsonResponse($category, 'category._row', trans('category.notifications.updated'));
    }

    /**
     * Display translate form
     * @param int $categoryId
     * @param string $langId
     * @return Response
     */
    public function getTranslate($categoryId, $langId)
    {
        $this->category->langId = $langId;
        $item = $this->category->getCatInfo($categoryId);

        return view('category.form_translate')
            ->with('method', 'post')
            ->with('form_url', '/category/translate/' . $categoryId)
            ->with('form_title', trans('category.actions.translate'))
            ->with('item', $item);
    }

    /**
     * Handle a POST request to translate.
     *
     * @param \App\Http\Request\Category\StoreCategoryTranslationRequest $request
     * @param int $categoryId
     *
     * @return Response
     */
    public function postTranslate(StoreCategoryTranslationRequest $request, $categoryId)
    {
        $category = $this->category->getOne($categoryId);

        $input = $request->all();

        $data = [
            'category_id' => $categoryId,
            'lang_id' => $input['lang_id'],
            'name' => $input['name'],
            'description' =>  $input['description']
        ];
        
        $this->categoryTranslation->add($data);

        //$this->categoryTranslation->updatePathAndSlug($category, $data, $this->category);

        $this->category->rebuildTree();

        return $this->getUpdateJsonResponse($category, 'category._row', trans('category.notifications.updated'));
    }
}
