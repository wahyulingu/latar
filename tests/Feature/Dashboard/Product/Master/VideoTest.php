<?php

namespace Tests\Feature\Dashboard\Product\Master;

use App\Models\File;
use App\Models\Product\ProductMaster;
use App\Models\Product\ProductVideo;
use App\Models\Profile\ProfileOwner;
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

    public function testCreateScreenOfProductVideoCanBeRendered()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/video/create', $product->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfProductVideo()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/%s/video/create', $product->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfProductVideo()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/video/create', $product->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewProductVideo()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $productVideoCountBeforeStore = ProductVideo::count();

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
                uri: sprintf('/dashboard/product/%s/video', $product->getKey()),
                data: [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'video' => $video,
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('product_videos', ++$productVideoCountBeforeStore);

        $fakeStorage->assertExists($video->hashName('media/product/videos'));
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewProductVideo()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/product/%s/video', $product->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheProductCanStoreNewProductVideo()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->post(sprintf('/dashboard/product/%s/video', $product->getKey()))
            ->assertForbidden();
    }
}
