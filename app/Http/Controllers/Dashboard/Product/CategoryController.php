<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Actions\Product\Category\Create;
use App\Actions\Product\Category\Delete;
use App\Actions\Product\Category\Find;
use App\Actions\Product\Category\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Category\StoreRequest;
use App\Http\Requests\Dashboard\Product\Category\UpdateRequest;
use App\Models\Product\ProductCategory;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductCategory::class, 'category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Category/Index',
            props: [
                'categories' => $find->onlyRootAll(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Dashboard/Product/Category/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Create $create)
    {
        return $create->handle($request->only(['name', 'description']));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $category)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Category/Index',
            props: compact('category')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategory $category)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Category/Edit',
            props: compact('category')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ProductCategory $category, Update $update)
    {
        $update->handle($category, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $category, Delete $delete)
    {
        $delete->handle($category);
    }
}
