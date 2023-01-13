<?php

namespace Tests\Feature\Dashboard\Product\Master;

use App\Models\File;
use App\Models\Product\ProductMaster;
use App\Models\Product\ProductPhoto;
use App\Models\Profile\ProfileOwner;
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

    public function testCreateScreenOfProductPhotoCanBeRendered()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/photo/create', $product->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfProductPhoto()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/%s/photo/create', $product->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfProductPhoto()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->get(sprintf('/dashboard/product/%s/photo/create', $product->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewProductPhoto()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        $this->actingAs($product->owner->user);

        $productPhotoCountBeforeStore = ProductPhoto::count();

        /** @var FilesystemAdapter */
        $fakeStorage = Storage::fake(File::fileDisk());

        $photo = UploadedFile::fake()->image('photo.jpg');

        $this

            ->post(
                uri: sprintf('/dashboard/product/%s/photo', $product->getKey()),
                data: [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'photo' => $photo,
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('product_photos', ++$productPhotoCountBeforeStore);

        $fakeStorage->assertExists($photo->hashName('media/product/photos'));
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewProductPhoto()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/product/%s/photo', $product->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheProductCanStoreNewProductPhoto()
    {
        /** @var ProductMaster */
        $product = ProductMaster::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->post(sprintf('/dashboard/product/%s/photo', $product->getKey()))
            ->assertForbidden();
    }
}
