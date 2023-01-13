<?php

namespace App\Http\Controllers;

use App\CodeBook;
use App\Http\Requests\CodeBook\StoreCodeBookRequest;
use App\Http\Requests\CodeBook\UpdateCodeBookRequest;
use Illuminate\Support\Facades\Cache;

/**
 * Class CodeBookController
 *
 * @package App\Http\Controllers
 */
class CodeBookController extends Controller
{
    /**
     * @var CodeBook model
     */
    private $codeBook;
    
    /**
     * CodeBookController constructor.
     *
     * @param \App\CodeBook $codeBook
     */
    public function __construct(CodeBook $codeBook)
    {
        $this->codeBook = $codeBook;
		
        $this->middleware('auth');
        $this->middleware('acl:view-codebook', ['only' => ['index']]);
        $this->middleware('acl:create-codebook', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-codebook', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->codeBook->paginate = true;
        $this->codeBook->typeGroup = request('type');
        $this->codeBook->keywords = request('keywords');
        $items = $this->codeBook->getAll();
        
        return view('code-book.index')
            ->with('items', $items)
            ->with('type', $this->codeBook->typeGroup);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('code-book.form')
                ->with('item', $this->codeBook)
                ->with('method', 'post')
                ->with('form_url', route('code-book.store'))
                ->with('form_title', trans('codebook.actions.create'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CodeBook\StoreCodeBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreCodeBookRequest $request)
    {
        $input = $request->except(['_token', '_method', 'with_colors']);
        
        $codeBook = $this->codeBook->add($input);
        
        Cache::forget('code-book.'.$codeBook->type);
        
        return $this->getStoreJsonResponse($codeBook, 'code-book._row', trans('codebook.notifications.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->codeBook->getOne($id);

        return view('code-book.form')
                ->with('method', 'put')
                ->with('form_url', route('code-book.update', [$id]))
                ->with('form_title', trans('codebook.actions.edit'))
                ->with('item', $item);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\CodeBook\UpdateCodeBookRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateCodeBookRequest $request, $id)
    {
        $input = $request->except(['_token', '_method', 'with_colors']);
        
        $codeBook = $this->codeBook->edit($id, $input);
    
        Cache::forget('code-book.'.$codeBook->type);
        
        return $this->getUpdateJsonResponse($codeBook, 'code-book._row', trans('codebook.notifications.updated'));
    }
}
