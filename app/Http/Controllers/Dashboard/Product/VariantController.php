<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Actions\Product\Variant\Delete;
use App\Actions\Product\Variant\Find;
use App\Actions\Product\Variant\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Variant\UpdateRequest;
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
    public function index(Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Master/Index',
            props: [
                'products' => $find->all(),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductVariant $variant)
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
