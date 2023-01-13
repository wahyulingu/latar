<?php

namespace Tests\Feature\Dashboard\Product;

use App\Models\Product\ProductCategory;
use App\Models\Product\ProductMaster;
use App\Models\Profile\ProfileOwner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MasterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateScreenOfProductCanBeRendered()
    {
        $this->actingAs(ProfileOwner::factory()->create()->user);

        $this

            ->get('/dashboard/product/create')
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get('/dashboard/product/create')
            ->assertForbidden();
    }

    public function testOwnerOfProductCanOpenEditScreenOfProduct()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/edit', $product->getKey()))
            ->assertSuccessful();
    }

    public function testAuthorizedUserCanOpenEditScreenOfProductIfNotOwnerOfProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'update.product']);

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/%s/edit', ProductMaster::factory()->create()->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyAuthorizedUserCanOpenEditScreenOfProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this

            ->get(sprintf('/dashboard/product/%s/edit', $product->getKey()))
            ->assertForbidden();
    }

    public function testTheOwnerCanUpdateHisProduct()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $this

            ->patch(
                uri: sprintf('/dashboard/product/%s', $product->getKey()),
                data: $expectedData = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $actualData = $product->fresh();

        $this->assertEquals($expectedData['name'], $actualData['name']);
        $this->assertEquals($expectedData['description'], $actualData['description']);
    }

    public function testOnlyAuthorizedUserCanUpdateProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this

            ->patch(sprintf('/dashboard/product/%s', $product->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewProduct()
    {
        $this->actingAs(ProfileOwner::factory()->create()->user);

        $productCountBeforeStore = ProductMaster::count();

        $this
            ->post(
                uri: '/dashboard/product',
                data: $data = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'category_id' => ProductCategory::factory()->create()->getKey(),
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('product_masters', ++$productCountBeforeStore);
        $this->assertDatabaseHas('product_masters', $data);
    }

    public function testOnlyAuthorizedUserCanStoreNewProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post('/dashboard/product')
            ->assertForbidden();
    }

    public function testOwnerCanDestroyHisProduct()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $productCountBeforeDestroy = ProductMaster::count();

        $this

            ->delete(sprintf('/dashboard/product/%s', $product->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('product_masters', --$productCountBeforeDestroy);
        $this->assertDatabaseMissing('product_masters', $product->toArray());
        $this->assertNull($product->fresh());
    }

    public function testAuthorizedUserCanDestroyProductIfNotOwnerOfProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'delete.product']);

        $this->actingAs($user);

        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();
        $productCountBeforeDestroy = ProductMaster::count();

        $this

            ->delete(sprintf('/dashboard/product/%s', $product->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('product_masters', --$productCountBeforeDestroy);
        $this->assertDatabaseMissing('product_masters', $product->toArray());
        $this->assertNull($product->fresh());
    }

    public function testOnlyAuthorizedUserCanDestroyProduct()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $productCountBeforeDestroy = ProductMaster::count();

        $this

            ->delete(sprintf('/dashboard/product/%s', $product->getKey()))
            ->assertForbidden();

        $this->assertEquals($productCountBeforeDestroy, actual: ProductMaster::count());
    }
}
