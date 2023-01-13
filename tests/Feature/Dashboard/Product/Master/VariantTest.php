<?php

namespace Tests\Feature\Dashboard\Product\Master;

use App\Models\Product\ProductMaster;
use App\Models\Product\ProductVariant;
use App\Models\Profile\ProfileOwner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VariantTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateScreenOfProductVariantCanBeRendered()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/variant/create', $product->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfProductVariant()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/%s/variant/create', $product->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfProductVariant()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/variant/create', $product->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewProductVariant()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $productVariantCountBeforeStore = ProductVariant::count();

        $this

            ->post(
                uri: sprintf('/dashboard/product/%s/variant', $product->getKey()),
                data: $data = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'price' => rand(200000, 900000),
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('product_variants', ++$productVariantCountBeforeStore);
        $this->assertDatabaseHas('product_variants', $data);
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewProductVariant()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/product/%s/variant', $product->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheProductCanStoreNewProductVariant()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->post(sprintf('/dashboard/product/%s/variant', $product->getKey()))
            ->assertForbidden();
    }
}
