<?php

namespace App\Http\Controllers\Dashboard\Content;

use App\Actions\Content\Category\Create;
use App\Actions\Content\Category\Delete;
use App\Actions\Content\Category\Find;
use App\Actions\Content\Category\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Content\Category\StoreRequest;
use App\Http\Requests\Dashboard\Content\Category\UpdateRequest;
use App\Models\Content\ContentCategory;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContentCategory::class, 'category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Category/Index',
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
        return Inertia::render('Dashboard/Content/Category/Create');
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
    public function show(ContentCategory $category)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Category/Index',
            props: compact('category')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ContentCategory $category)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Category/Edit',
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
    public function update(UpdateRequest $request, ContentCategory $category, Update $update)
    {
        $update->handle($category, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContentCategory $category, Delete $delete)
    {
        $delete->handle($category);
    }
}
