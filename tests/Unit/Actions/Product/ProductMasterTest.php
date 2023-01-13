<?php

namespace Tests\Unit\Actions\Product;

use App\Actions\Product\Master\Create;
use App\Actions\Product\Master\Delete;
use App\Actions\Product\Master\Update;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductMaster;
use App\Models\Profile\ProfileOwner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductMasterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testProductCreateActionIsWorking()
    {
        /** @var Create */
        $action = app(Create::class);

        $productCountBeforeStore = ProductMaster::count();

        $action->handle(
            owner: ProfileOwner::factory()->create(),
            input: $data = [
                'name' => $this->faker->words(2, true),
                'description' => $this->faker->words(20, true),
                'category_id' => ProductCategory::factory()->create()->getKey(),
            ]
        );

        $this->assertDatabaseCount('product_masters', ++$productCountBeforeStore);
        $this->assertDatabaseHas('product_masters', $data);
    }

    public function testProductUpdateActionIsWorking()
    {
        /** @var Update */
        $action = app(Update::class);

        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $action->handle(
            model: $product,
            input: $expectedData = [
                'name' => $this->faker->words(2, true),
                'description' => $this->faker->words(20, true),
            ]
        );
        $actualData = $product->fresh();

        $this->assertEquals($expectedData['name'], $actualData['name']);
        $this->assertEquals($expectedData['description'], $actualData['description']);
    }

    public function testProductDeleteActionIsWorking()
    {
        /** @var Delete */
        $action = app(Delete::class);

        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $productCountBeforeDestroy = ProductMaster::count();

        $action->handle($product);

        $this->assertDatabaseCount('product_masters', --$productCountBeforeDestroy);
        $this->assertDatabaseMissing('product_masters', $product->toArray());
        $this->assertNull($product->fresh());
    }
}
