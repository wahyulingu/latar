<?php

namespace Tests\Feature\Dashboard\Product;

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

    public function testOwnerOfProductCanOpenEditScreenOfProductSpecification()
    {
        /* @var ProductSpecification */
        $productSpecification = ProductSpecification::factory()->create();

        $this->actingAs($productSpecification->product->owner->user);

        $this

            ->get(sprintf('/dashboard/product/specification/%s/edit', $productSpecification->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyOwnerOfProductCanOpenEditScreenOfProductSpecification()
    {
        /* @var ProductSpecification */
        $productSpecification = ProductSpecification::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/specification/%s/edit', $productSpecification->getKey()))
            ->assertForbidden();
    }

    public function testTheOwnerCanUpdateHisProductSpecification()
    {
        /* @var ProductSpecification */
        $productSpecification = ProductSpecification::factory()->create();

        $this->actingAs($productSpecification->product->owner->user);

        $this

            ->patch(
                uri: sprintf('/dashboard/product/specification/%s', $productSpecification->getKey()),
                data: $expectedData = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'price' => rand(200000, 900000),
                ]
            )

            ->assertSuccessful();

        $actualData = $productSpecification->fresh();

        $this->assertEquals($expectedData['name'], $actualData['name']);
        $this->assertEquals($expectedData['description'], $actualData['description']);
    }

    public function testOnlyOwnerCanUpdateHisProductSpecification()
    {
        /* @var ProductSpecification */
        $productSpecification = ProductSpecification::factory()->create();

        $this->actingAs(ProfileOwner::factory()->create()->user);

        $this

            ->patch(sprintf('/dashboard/product/specification/%s', $productSpecification->getKey()))
            ->assertForbidden();
    }

    public function testOwnerCanDestroyHisProductSpecification()
    {
        /* @var ProductSpecification */
        $productSpecification = ProductSpecification::factory()->create();

        $this->actingAs($productSpecification->product->owner->user);

        $productSpecificationCountBeforeDestroy = ProductSpecification::count();

        $this

            ->delete(sprintf('/dashboard/product/specification/%s', $productSpecification->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('product_specifications', --$productSpecificationCountBeforeDestroy);
        $this->assertDatabaseMissing('product_specifications', $productSpecification->toArray());
        $this->assertNull($productSpecification->fresh());
    }

    public function testAuthorizedUserCanDestroyProductSpecificationIfNotOwnerOfVariantProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        /* @var ProductSpecification */
        $productSpecification = ProductSpecification::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'delete.product.specification']);

        $this->actingAs($user);

        $productSpecificationCountBeforeDestroy = ProductSpecification::count();

        $this

            ->delete(sprintf('/dashboard/product/specification/%s', $productSpecification->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('product_specifications', --$productSpecificationCountBeforeDestroy);
        $this->assertDatabaseMissing('product_specifications', $productSpecification->toArray());
        $this->assertNull($productSpecification->fresh());
    }

    public function testOnlyAuthorizedUserCanDestroyProductSpecification()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /* @var ProductSpecification */
        $productSpecification = ProductSpecification::factory()->create();

        $this

            ->delete(sprintf('/dashboard/product/specification/%s', $productSpecification->getKey()))
            ->assertForbidden();
    }
}
