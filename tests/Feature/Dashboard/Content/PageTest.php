<?php

namespace Tests\Feature\Dashboard\Content;

use App\Models\Content\ContentCategory;
use App\Models\Content\ContentPage;
use App\Models\Profile\ProfileAuthor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateScreenOfPageCanBeRendered()
    {
        $this->actingAs(ProfileAuthor::factory()->create()->user);

        $this

            ->get('/dashboard/content/page/create')
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfPage()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get('/dashboard/content/page/create')
            ->assertForbidden();
    }

    public function testOwnerOfPageCanOpenEditScreenOfPage()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this->actingAs($page->author->user);

        $this

            ->get(sprintf('/dashboard/content/page/%s/edit', $page->getKey()))
            ->assertSuccessful();
    }

    public function testAuthorizedUserCanOpenEditScreenOfPageIfNotOwnerOfPage()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'update.page']);

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/content/page/%s/edit', ContentPage::factory()->create()->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyAuthorizedUserCanOpenEditScreenOfPage()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this

            ->get(sprintf('/dashboard/content/page/%s/edit', $page->getKey()))
            ->assertForbidden();
    }

    public function testTheOwnerCanUpdateHisPage()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this->actingAs($page->author->user);

        $this

            ->patch(
                uri: sprintf('/dashboard/content/page/%s', $page->getKey()),
                data: $expectedData = [
                    'title' => $this->faker->words(10, true),
                    'content' => $this->faker->paragraph(10, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $actualData = $page->fresh();

        Log::error(json_encode([$actualData, $expectedData]));

        $this->assertEquals($expectedData['title'], $actualData['title']);
        $this->assertEquals($expectedData['description'], $actualData['description']);
    }

    public function testOnlyAuthorizedUserCanUpdatePage()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this

            ->patch(sprintf('/dashboard/content/page/%s', $page->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewPage()
    {
        $this->actingAs(ProfileAuthor::factory()->create()->user);

        $pageCountBeforeStore = ContentPage::count();

        $this
            ->post(
                uri: '/dashboard/content/page',
                data: $data = [
                    'title' => $this->faker->words(2, true),
                    'content' => $this->faker->paragraph(10, true),
                    'description' => $this->faker->words(20, true),
                    'category_id' => ContentCategory::factory()->create()->getKey(),
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('content_pages', ++$pageCountBeforeStore);
        $this->assertDatabaseHas('content_pages', $data);
    }

    public function testOnlyAuthorizedUserCanStoreNewPage()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post('/dashboard/content/page')
            ->assertForbidden();
    }

    public function testOwnerCanDestroyHisPage()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this->actingAs($page->author->user);

        $pageCountBeforeDestroy = ContentPage::count();

        $this

            ->delete(sprintf('/dashboard/content/page/%s', $page->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('content_pages', --$pageCountBeforeDestroy);
        $this->assertDatabaseMissing('content_pages', $page->toArray());
        $this->assertNull($page->fresh());
    }

    public function testAuthorizedUserCanDestroyPageIfNotOwnerOfPage()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'delete.page']);

        $this->actingAs($user);

        /** @var ContentPage */
        $page = ContentPage::factory()->create();
        $pageCountBeforeDestroy = ContentPage::count();

        $this

            ->delete(sprintf('/dashboard/content/page/%s', $page->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('content_pages', --$pageCountBeforeDestroy);
        $this->assertDatabaseMissing('content_pages', $page->toArray());
        $this->assertNull($page->fresh());
    }

    public function testOnlyAuthorizedUserCanDestroyPage()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $pageCountBeforeDestroy = ContentPage::count();

        $this

            ->delete(sprintf('/dashboard/content/page/%s', $page->getKey()))
            ->assertForbidden();

        $this->assertEquals($pageCountBeforeDestroy, actual: ContentPage::count());
    }
}
