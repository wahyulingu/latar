<?php

namespace Tests\Feature\Dashboard\Product\Variant;

use App\Models\Product\ProductSpecification;
use App\Models\Product\ProductVariant;
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
        /** @var ProductVariant */
        $variant = ProductVariant::factory()->create();

        $this->actingAs($variant->master->owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/specification/create', $variant->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfProductSpecification()
    {
        /** @var ProductVariant */
        $variant = ProductVariant::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/%s/specification/create', $variant->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfProductSpecification()
    {
        /** @var ProductVariant */
        $variant = ProductVariant::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/specification/create', $variant->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewProductSpecification()
    {
        /** @var ProductVariant */
        $variant = ProductVariant::factory()->create();

        $this->actingAs($variant->master->owner->user);

        $variantVariantCountBeforeStore = ProductSpecification::count();

        $this

            ->post(
                uri: sprintf('/dashboard/product/%s/specification', $variant->getKey()),
                data: $data = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('product_specifications', ++$variantVariantCountBeforeStore);
        $this->assertDatabaseHas('product_specifications', $data);
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewProductSpecification()
    {
        /** @var ProductVariant */
        $variant = ProductVariant::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/product/%s/specification', $variant->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheProductCanStoreNewProductSpecification()
    {
        /** @var ProductVariant */
        $variant = ProductVariant::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->post(sprintf('/dashboard/product/%s/specification', $variant->getKey()))
            ->assertForbidden();
    }
}
