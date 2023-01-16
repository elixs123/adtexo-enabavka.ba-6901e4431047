<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\CategoryTranslation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryTranslationRequest;
use App\Http\Resources\Category\CategoryResource as ModelResource;
use App\Http\Resources\Category\CategoryCollection as ModelCollection;
use App\Http\Requests\Category\StoreApiCategoryRequest;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api
 */
class CategoryController extends Controller
{
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
     * @param \App\Category $category
     * @param \App\CategoryTranslation $categoryTranslation
     */
    public function __construct(Category $category, CategoryTranslation $categoryTranslation) {
        $this->category = $category;
        $this->categoryTranslation = $categoryTranslation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function index()
    {
        $categoryId = request('father_id', 1);

        $this->category->statusId = request('status');
        $this->category->langId = request('lang_id', 'bs');

        return $this->category->getCategoryTree($categoryId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApiCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreApiCategoryRequest $request)
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
            'description' =>  $input['description'] ?? null
        ];

        // Save Category
        $category = $this->category->add($categoryInput);

        // Save Translation
        $category->translations()->save(new CategoryTranslation($translationInput));

        // Update slug and path, rebuild tree
        $this->category->updateListOfParents($category);
        $this->category->rebuildTree();

        return $this->category->getCatInfo($category->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApiCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreApiCategoryRequest $request, $id)
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
            'description' =>  $input['description'] ?? null,
        ];

        $category = $this->category->edit($id, $categoryInput);

        $this->categoryTranslation->editTranslation($translationInput);

        $this->category->updateListOfParents($category);

        $this->category->rebuildTree();

        $this->category->langId = $input['lang_id'];

        return $this->category->getCatInfo($category->id);
    }

    /**
     * Handle a POST request to translate.
     *
     * @param \App\Http\Request\Category\StoreCategoryTranslationRequest $request
     * @param int $categoryId
     *
     * @return Response
     */
    public function translate(StoreCategoryTranslationRequest $request, $categoryId)
    {
        $input = $request->all();

        $data = [
            'category_id' => $categoryId,
            'lang_id' => $input['lang_id'],
            'name' => $input['name'],
            'description' =>  $input['description'] ?? null,
        ];

        $this->categoryTranslation->add($data);

        $this->category->rebuildTree();

        $this->category->langId = $input['lang_id'];
        return $this->category->getCatInfo($categoryId);

    }

    /**
     * @param int $id
     * @return \App\Http\Resources\Client\ClientResource
     */
    public function show($id)
    {
        $this->category->langId = request('lang_id', 'bs');

        return $this->category->getCatInfo($id);
    }
}
