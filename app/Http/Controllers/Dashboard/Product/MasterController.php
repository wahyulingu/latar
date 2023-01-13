<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Actions\Product\Master\Create;
use App\Actions\Product\Master\Delete;
use App\Actions\Product\Master\Find;
use App\Actions\Product\Master\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Master\StoreRequest;
use App\Http\Requests\Dashboard\Product\Master\UpdateRequest;
use App\Models\Product\ProductMaster;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MasterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductMaster::class, 'product');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Master/Index',
            props: [
                'products' => $find->handle($request->input('query')),
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
        return Inertia::render('Dashboard/Product/Master/Index');
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
        return $create->handle(
            $request->user()->ownerProfile,
            $request->only(
                keys: [
                    'name',
                    'description',
                    'category_id',
                ]
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductMaster $product)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Master/Index',
            props: compact('product')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductMaster $product)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Master/Edit',
            props: compact('product')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ProductMaster $product, Update $update)
    {
        $update->handle($product, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductMaster $product, Delete $delete)
    {
        $delete->handle($product);
    }
}
