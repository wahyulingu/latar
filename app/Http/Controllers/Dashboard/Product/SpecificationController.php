<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Actions\Product\Specification\Delete;
use App\Actions\Product\Specification\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Specification\UpdateRequest;
use App\Models\Product\ProductSpecification;
use Inertia\Inertia;

class SpecificationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductSpecification::class, 'specification');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSpecification $specification)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Specification/Index',
            props: compact('specification')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSpecification $specification)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Specification/Edit',
            props: compact('specification')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ProductSpecification $specification, Update $update)
    {
        $update->handle($specification, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSpecification $specification, Delete $delete)
    {
        $delete->handle($specification);
    }
}
