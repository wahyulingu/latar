<?php

namespace Tests\Feature\Dashboard\Content\Page;

use App\Models\Content\ContentPage;
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

    public function testCreateScreenOfPageVideoCanBeRendered()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this->actingAs($page->author->user);

        $this

            ->get(sprintf('/dashboard/content/page/%s/video/create', $page->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfPageVideo()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/content/page/%s/video/create', $page->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfPageVideo()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        /** @var ProfileAuthor */
        $author = ProfileAuthor::factory()->create();

        $this->actingAs($author->user);

        $this

            ->get(sprintf('/dashboard/content/page/%s/video/create', $page->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewPageVideo()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this->actingAs($page->author->user);

        $pageVideoCountBeforeStore = ContentVideo::count();

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
                uri: sprintf('/dashboard/content/page/%s/video', $page->getKey()),
                data: [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'video' => $video,
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('content_videos', ++$pageVideoCountBeforeStore);

        $fakeStorage->assertExists($video->hashName('media/content/videos'));
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewPageVideo()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/content/page/%s/video', $page->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheContentCanStoreNewPageVideo()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        /** @var ProfileAuthor */
        $author = ProfileAuthor::factory()->create();

        $this->actingAs($author->user);

        $this

            ->post(sprintf('/dashboard/content/page/%s/video', $page->getKey()))
            ->assertForbidden();
    }
}
