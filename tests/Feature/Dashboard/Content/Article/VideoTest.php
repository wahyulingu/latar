<?php

namespace Tests\Feature\Dashboard\Content\Article;

use App\Models\Content\ContentArticle;
use App\Models\Content\ContentVideo;
use App\Models\File;
use App\Models\Profile\ProfileAuthor;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateScreenOfArticleVideoCanBeRendered()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this->actingAs($article->author->user);

        $this

            ->get(sprintf('/dashboard/content/article/%s/video/create', $article->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfArticleVideo()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/content/article/%s/video/create', $article->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfArticleVideo()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        /** @var ProfileAuthor */
        $author = ProfileAuthor::factory()->create();

        $this->actingAs($author->user);

        $this

            ->get(sprintf('/dashboard/content/article/%s/video/create', $article->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewArticleVideo()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        $this->actingAs($article->author->user);

        $articleVideoCountBeforeStore = ContentVideo::count();

        /** @var FilesystemAdapter */
        $fakeStorage = Storage::fake(File::fileDisk());

        $video = UploadedFile::fake()

            ->create(
                name: 'video.mp4',
                mimeType: 'video/mpeg',
                kilobytes: 2480
            );
        $this

            ->post(
                uri: sprintf('/dashboard/content/article/%s/video', $article->getKey()),
                data: [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'video' => $video,
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('content_videos', ++$articleVideoCountBeforeStore);

        $fakeStorage->assertExists($video->hashName('media/content/videos'));
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewArticleVideo()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/content/article/%s/video', $article->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheContentCanStoreNewArticleVideo()
    {
        /** @var ContentArticle */
        $article = ContentArticle::factory()->create();

        /** @var ProfileAuthor */
        $author = ProfileAuthor::factory()->create();

        $this->actingAs($author->user);

        $this

            ->post(sprintf('/dashboard/content/article/%s/video', $article->getKey()))
            ->assertForbidden();
    }
}
