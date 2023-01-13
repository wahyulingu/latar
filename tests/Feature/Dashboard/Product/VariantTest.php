<?php

namespace Tests\Feature\Dashboard\Product;

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

    public function testOwnerOfProductCanOpenEditScreenOfProductVariant()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        $this->actingAs($productVariant->master->owner->user);

        $this

            ->get(sprintf('/dashboard/product/variant/%s/edit', $productVariant->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyOwnerOfProductCanOpenEditScreenOfProductVariant()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/variant/%s/edit', $productVariant->getKey()))
            ->assertForbidden();
    }

    public function testTheOwnerCanUpdateHisProductVariant()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        $this->actingAs($productVariant->master->owner->user);

        $this

            ->patch(
                uri: sprintf('/dashboard/product/variant/%s', $productVariant->getKey()),
                data: $expectedData = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'price' => rand(200000, 900000),
                ]
            )

            ->assertSuccessful();

        $actualData = $productVariant->fresh();

        $this->assertEquals($expectedData['name'], $actualData['name']);
        $this->assertEquals($expectedData['description'], $actualData['description']);
    }

    public function testOnlyOwnerCanUpdateHisProductVariant()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        $this->actingAs(ProfileOwner::factory()->create()->user);

        $this

            ->patch(sprintf('/dashboard/product/variant/%s', $productVariant->getKey()))
            ->assertForbidden();
    }

    public function testOwnerCanDestroyHisProductVariant()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        $this->actingAs($productVariant->master->owner->user);

        $productVariantCountBeforeDestroy = ProductVariant::count();

        $this

            ->delete(sprintf('/dashboard/product/variant/%s', $productVariant->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('product_variants', --$productVariantCountBeforeDestroy);
        $this->assertDatabaseMissing('product_variants', $productVariant->toArray());
        $this->assertNull($productVariant->fresh());
    }

    public function testAuthorizedUserCanDestroyProductVariantIfNotOwnerOfVariantProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'delete.product.variant']);

        $this->actingAs($user);

        $productVariantCountBeforeDestroy = ProductVariant::count();

        $this

            ->delete(sprintf('/dashboard/product/variant/%s', $productVariant->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('product_variants', --$productVariantCountBeforeDestroy);
        $this->assertDatabaseMissing('product_variants', $productVariant->toArray());
        $this->assertNull($productVariant->fresh());
    }

    public function testOnlyAuthorizedUserCanDestroyProductVariant()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        $this

            ->delete(sprintf('/dashboard/product/variant/%s', $productVariant->getKey()))
            ->assertForbidden();
    }
}
