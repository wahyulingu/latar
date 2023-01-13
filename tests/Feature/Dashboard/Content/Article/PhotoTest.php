<?php

namespace Tests\Feature\Dashboard\Content\Article;

use App\Models\Content\ContentArticle;
use App\Models\Content\ContentPhoto;
use App\Models\File;
use App\Models\Profile\ProfileAuthor;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateScreenOfArticlePhotoCanBeRendered()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this->actingAs($article->author->user);

        $this

            ->get(sprintf('/dashboard/content/article/%s/photo/create', $article->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfArticlePhoto()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/content/article/%s/photo/create', $article->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfArticlePhoto()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        /** @var ProfileAuthor */
        $owner = ProfileAuthor::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->get(sprintf('/dashboard/content/article/%s/photo/create', $article->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewArticlePhoto()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this->actingAs($article->author->user);

        $articlePhotoCountBeforeStore = ContentPhoto::count();

        /** @var FilesystemAdapter */
        $fakeStorage = Storage::fake(File::fileDisk());

        $photo = UploadedFile::fake()->image('photo.jpg');

        $this

            ->post(
                uri: sprintf('/dashboard/content/article/%s/photo', $article->getKey()),
                data: [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'photo' => $photo,
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('content_photos', ++$articlePhotoCountBeforeStore);

        $fakeStorage->assertExists($photo->hashName('media/content/photos'));
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewArticlePhoto()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/content/article/%s/photo', $article->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheContentCanStoreNewArticlePhoto()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        /** @var ProfileAuthor */
        $owner = ProfileAuthor::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->post(sprintf('/dashboard/content/article/%s/photo', $article->getKey()))
            ->assertForbidden();
    }
}
