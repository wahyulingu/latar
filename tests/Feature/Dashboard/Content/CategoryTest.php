<?php

namespace Tests\Feature\Dashboard\Content;

use App\Models\Content\ContentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndexScreenOfContentCategoryCanBeRendered()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'index.content.category']);

        $this->actingAs($user);

        $this

            ->get('/dashboard/content/category')
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page

                    ->component('Dashboard/Content/Category/Index')
                    ->has('categories')
            );
    }

    public function testCreateScreenOfContentCategoryCanBeRendered()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'create.content.category']);

        $this->actingAs($user);

        $this

            ->get('/dashboard/content/category/create')
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page->component('Dashboard/Content/Category/Create')
            );
    }

    public function testOnlyAuthorizedUserCanOpenCreateScreenOfContentCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get('/dashboard/content/category/create')
            ->assertForbidden();
    }

    public function testEditSreenOfContentCategoryCanBeRendered()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'update.content.category']);

        $this->actingAs($user);

        /** @var ContentCategory */
        $contentCategory = ContentCategory::factory()->create();

        $this

            ->get(sprintf('/dashboard/content/category/%s/edit', $contentCategory->getKey()))
            ->assertSuccessful()->assertInertia(
                fn (AssertableInertia $page) => $page

                    ->component('Dashboard/Content/Category/Edit')
                    ->has('category')
            );
    }

    public function testOnlyAuthorizedUserCanOpenEditSscreenOfContentCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentCategory */
        $contentCategory = ContentCategory::factory()->create();

        $this

            ->get(sprintf('/dashboard/content/category/%s/edit', $contentCategory->getKey()))
            ->assertForbidden();
    }

    public function testCanUpdateContentCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'update.content.category']);

        $this->actingAs($user);

        /** @var ContentCategory */
        $contentCategory = ContentCategory::factory()->create();

        $this

            ->patch(
                uri: sprintf('/dashboard/content/category/%s', $contentCategory->getKey()),
                data: $expectedData = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $actualData = $contentCategory->fresh();

        $this->assertEquals($expectedData['name'], $actualData['name']);
        $this->assertEquals($expectedData['description'], $actualData['description']);
    }

    public function testOnlyAuthorizedUserCanUpdateContentCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentCategory */
        $contentCategory = ContentCategory::factory()->create();

        $this

            ->patch(sprintf('/dashboard/content/category/%s', $contentCategory->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewContentCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'create.content.category']);

        $this->actingAs($user);

        $contentCategoryCountBeforeStore = ContentCategory::count();

        $this

            ->post(
                '/dashboard/content/category',
                data: $data = [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('content_categories', ++$contentCategoryCountBeforeStore);
        $this->assertDatabaseHas('content_categories', $data);
    }

    public function testOnlyAuthorizedUserCanStoreNewContentCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post('/dashboard/content/category')
            ->assertForbidden();
    }

    public function testCanDestroyContentCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'delete.content.category']);

        $this->actingAs($user);

        /** @var ContentCategory */
        $contentCategory = ContentCategory::factory()->create();
        $contentCategoryCountBeforeDestroy = ContentCategory::count();

        $this

            ->delete(sprintf('/dashboard/content/category/%s', $contentCategory->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('content_categories', --$contentCategoryCountBeforeDestroy);
        $this->assertDatabaseMissing('content_categories', $contentCategory->toArray());
        $this->assertNull($contentCategory->fresh());
    }

    public function testOnlyAuthorizedUserCanDestroyContentCategory()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentCategory */
        $contentCategory = ContentCategory::factory()->create();

        $this

            ->delete(sprintf('/dashboard/content/category/%s', $contentCategory->getKey()))
            ->assertForbidden();
    }
}
