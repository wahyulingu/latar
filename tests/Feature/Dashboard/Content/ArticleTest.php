<?php

namespace Tests\Feature\Dashboard\Content;

use App\Models\Content\ContentArticle;
use App\Models\Content\ContentCategory;
use App\Models\Profile\ProfileAuthor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateScreenOfArticleCanBeRendered()
    {
        $this->actingAs(ProfileAuthor::factory()->create()->user);

        $this

            ->get('/dashboard/content/article/create')
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfArticle()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get('/dashboard/content/article/create')
            ->assertForbidden();
    }

    public function testOwnerOfArticleCanOpenEditScreenOfArticle()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this->actingAs($article->author->user);

        $this

            ->get(sprintf('/dashboard/content/article/%s/edit', $article->getKey()))
            ->assertSuccessful();
    }

    public function testAuthorizedUserCanOpenEditScreenOfArticleIfNotOwnerOfArticle()
    {
        /** @var User */
        $user = User::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'update.article']);

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/content/article/%s/edit', ContentArticle::factory()->create()->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyAuthorizedUserCanOpenEditScreenOfArticle()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this

            ->get(sprintf('/dashboard/content/article/%s/edit', $article->getKey()))
            ->assertForbidden();
    }

    public function testTheOwnerCanUpdateHisArticle()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this->actingAs($article->author->user);

        $this

            ->patch(
                uri: sprintf('/dashboard/content/article/%s', $article->getKey()),
                data: $expectedData = [
                    'title' => $this->faker->words(10, true),
                    'content' => $this->faker->paragraph(10, true),
                    'description' => $this->faker->words(20, true),
                ]
            )

            ->assertSuccessful();

        $actualData = $article->fresh();

        Log::error(json_encode([$actualData, $expectedData]));

        $this->assertEquals($expectedData['title'], $actualData['title']);
        $this->assertEquals($expectedData['description'], $actualData['description']);
    }

    public function testOnlyAuthorizedUserCanUpdateArticle()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this

            ->patch(sprintf('/dashboard/content/article/%s', $article->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewArticle()
    {
        $this->actingAs(ProfileAuthor::factory()->create()->user);

        $articleCountBeforeStore = ContentArticle::count();

        $this
            ->post(
                uri: '/dashboard/content/article',
                data: $data = [
                    'title' => $this->faker->words(2, true),
                    'content' => $this->faker->paragraph(10, true),
                    'description' => $this->faker->words(20, true),
                    'category_id' => ContentCategory::factory()->create()->getKey(),
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('content_articles', ++$articleCountBeforeStore);
        $this->assertDatabaseHas('content_articles', $data);
    }

    public function testOnlyAuthorizedUserCanStoreNewArticle()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post('/dashboard/content/article')
            ->assertForbidden();
    }

    public function testOwnerCanDestroyHisArticle()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this->actingAs($article->author->user);

        $articleCountBeforeDestroy = ContentArticle::count();

        $this

            ->delete(sprintf('/dashboard/content/article/%s', $article->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('content_articles', --$articleCountBeforeDestroy);
        $this->assertDatabaseMissing('content_articles', $article->toArray());
        $this->assertNull($article->fresh());
    }

    public function testAuthorizedUserCanDestroyArticleIfNotOwnerOfArticle()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $user

            ->permissions()
            ->firstOrCreate(['name' => 'delete.article']);

        $this->actingAs($user);

        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();
        $articleCountBeforeDestroy = ContentArticle::count();

        $this

            ->delete(sprintf('/dashboard/content/article/%s', $article->getKey()))
            ->assertSuccessful();

        $this->assertDatabaseCount('content_articles', --$articleCountBeforeDestroy);
        $this->assertDatabaseMissing('content_articles', $article->toArray());
        $this->assertNull($article->fresh());
    }

    public function testOnlyAuthorizedUserCanDestroyArticle()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $articleCountBeforeDestroy = ContentArticle::count();

        $this

            ->delete(sprintf('/dashboard/content/article/%s', $article->getKey()))
            ->assertForbidden();

        $this->assertEquals($articleCountBeforeDestroy, actual: ContentArticle::count());
    }
}
