<?php

namespace Tests\Feature\Dashboard\Content\Page;

use App\Models\Content\ContentPage;
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

    public function testCreateScreenOfPagePhotoCanBeRendered()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this->actingAs($page->author->user);

        $this

            ->get(sprintf('/dashboard/content/page/%s/photo/create', $page->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfPagePhoto()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/content/page/%s/photo/create', $page->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfPagePhoto()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        /** @var ProfileAuthor */
        $owner = ProfileAuthor::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->get(sprintf('/dashboard/content/page/%s/photo/create', $page->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewPagePhoto()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        $this->actingAs($page->author->user);

        $pagePhotoCountBeforeStore = ContentPhoto::count();

        /** @var FilesystemAdapter */
        $fakeStorage = Storage::fake(File::fileDisk());

        $photo = UploadedFile::fake()->image('photo.jpg');

        $this

            ->post(
                uri: sprintf('/dashboard/content/page/%s/photo', $page->getKey()),
                data: [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'photo' => $photo,
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('content_photos', ++$pagePhotoCountBeforeStore);

        $fakeStorage->assertExists($photo->hashName('media/content/photos'));
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewPagePhoto()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/content/page/%s/photo', $page->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheContentCanStoreNewPagePhoto()
    {
        /** @var ContentPage */
        $page = ContentPage::factory()->create();

        /** @var ProfileAuthor */
        $owner = ProfileAuthor::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->post(sprintf('/dashboard/content/page/%s/photo', $page->getKey()))
            ->assertForbidden();
    }
}
