<?php

namespace Tests\Feature\Dashboard\Product\Master;

use App\Models\Product\ProductMaster;
use App\Models\Product\ProductSpecification;
use App\Models\Profile\ProfileOwner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpecificationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateScreenOfProductSpecificationCanBeRendered()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/specification/create', $product->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfProductSpecification()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/%s/specification/create', $product->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfProductSpecification()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/specification/create', $product->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewProductSpecification()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $productVariantCountBeforeStore = ProductSpecification::count();

        $this

            ->post(
                uri: sprintf('/dashboard/product/%s/specification', $product->getKey()),
                data: $data = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('product_specifications', ++$productVariantCountBeforeStore);
        $this->assertDatabaseHas('product_specifications', $data);
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewProductSpecification()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/product/%s/specification', $product->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheProductCanStoreNewProductSpecification()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->post(sprintf('/dashboard/product/%s/specification', $product->getKey()))
            ->assertForbidden();
    }
}
