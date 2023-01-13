<?php

namespace App\Http\Controllers\Dashboard\Product\Master;

use App\Actions\Product\Variant\Create;
use App\Actions\Product\Variant\Delete;
use App\Actions\Product\Variant\Find;
use App\Actions\Product\Variant\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Variant\StoreRequest;
use App\Http\Requests\Dashboard\Product\Variant\UpdateRequest;
use App\Models\Product\ProductMaster;
use App\Models\Product\ProductVariant;
use Inertia\Inertia;

class VariantController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductVariant::class, 'variant');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductMaster $product, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Variant/Index',
            props: [
                'variants' => $find->all($product),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProductMaster $product)
    {
        return Inertia::render('Dashboard/Product/Variant/Index', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProductMaster $product, StoreRequest $request, Create $create)
    {
        return $create->handle(
            $product,
            $request->only(
                [
                    'name',
                    'description',
                    'price',
                ]
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductMaster $product, ProductVariant $variant)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Variant/Index',
            props: compact('variant')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductVariant $variant)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Variant/Edit',
            props: compact('variant')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ProductVariant $variant, Update $update)
    {
        $update->handle($variant, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVariant $variant, Delete $delete)
    {
        $delete->handle($variant);
    }
}
