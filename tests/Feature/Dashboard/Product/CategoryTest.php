<?php

namespace Tests\Feature\Dashboard\Product;

use App\Models\Product\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndexScreenOfProductCategoryCanBeRendered()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'index.product.category']);

        $this->actingAs($user);

        $this

            ->get('/dashboard/product/category')
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page

                    ->component('Dashboard/Product/Category/Index')
                    ->has('categories')
            );
    }

    public function testCreateScreenOfProductCategoryCanBeRendered()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'create.product.category']);

        $this->actingAs($user);

        $this

            ->get('/dashboard/product/category/create')
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page->component('Dashboard/Product/Category/Create')
            );
    }

    public function testOnlyAuthorizedUserCanOpenCreateScreenOfProductCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get('/dashboard/product/category/create')
            ->assertForbidden();
    }

    public function testEditSreenOfProductCategoryCanBeRendered()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'update.product.category']);

        $this->actingAs($user);

        /** @var ProductCategory */
        $productCategory = ProductCategory::factory()->create();

        $this

            ->get(sprintf('/dashboard/product/category/%s/edit', $productCategory->getKey()))
            ->assertSuccessful()->assertInertia(
                fn (AssertableInertia $page) => $page

                    ->component('Dashboard/Product/Category/Edit')
                    ->has('category')
            );
    }

    public function testOnlyAuthorizedUserCanOpenEditSscreenOfProductCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ProductCategory */
        $productCategory = ProductCategory::factory()->create();

        $this

            ->get(sprintf('/dashboard/product/category/%s/edit', $productCategory->getKey()))
            ->assertForbidden();
    }

    public function testCanUpdateProductCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'update.product.category']);

        $this->actingAs($user);

        /** @var ProductCategory */
        $productCategory = ProductCategory::factory()->create();

        $this

            ->patch(
                uri: sprintf('/dashboard/product/category/%s', $productCategory->getKey()),
                data: $expectedData = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $actualData = $productCategory->fresh();

        $this->assertEquals($expectedData['name'], $actualData['name']);
        $this->assertEquals($expectedData['description'], $actualData['description']);
    }

    public function testOnlyAuthorizedUserCanUpdateProductCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ProductCategory */
        $productCategory = ProductCategory::factory()->create();

        $this

            ->patch(sprintf('/dashboard/product/category/%s', $productCategory->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewProductCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'create.product.category']);

        $this->actingAs($user);

        $productCategoryCountBeforeStore = ProductCategory::count();

        $this

            ->post(
                '/dashboard/product/category',
                data: $data = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('product_categories', ++$productCategoryCountBeforeStore);
        $this->assertDatabaseHas('product_categories', $data);
    }

    public function testOnlyAuthorizedUserCanStoreNewProductCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post('/dashboard/product/category')
            ->assertForbidden();
    }

    public function testCanDestroyProductCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'delete.product.category']);

        $this->actingAs($user);

        /** @var ProductCategory */
        $productCategory = ProductCategory::factory()->create();
        $productCategoryCountBeforeDestroy = ProductCategory::count();

        $this

            ->delete(sprintf('/dashboard/product/category/%s', $productCategory->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('product_categories', --$productCategoryCountBeforeDestroy);
        $this->assertDatabaseMissing('product_categories', $productCategory->toArray());
        $this->assertNull($productCategory->fresh());
    }

    public function testOnlyAuthorizedUserCanDestroyProductCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ProductCategory */
        $productCategory = ProductCategory::factory()->create();

        $this

            ->delete(sprintf('/dashboard/product/category/%s', $productCategory->getKey()))
            ->assertForbidden();
    }
}
