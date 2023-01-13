<?php

namespace Tests\Feature\Dashboard\Product\Variant;

use App\Models\File;
use App\Models\Product\ProductPhoto;
use App\Models\Product\ProductVariant;
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

    public function testCreateScreenOfProductVariantPhotoCanBeRendered()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        $this->actingAs($productVariant->master->owner->user);

        $this

            ->get(sprintf('/dashboard/product/variant/%s/photo/create', $productVariant->getKey()))
            ->assertSuccessful();
    }

    public function testOnlyUserWithOwnerProfileCanOpenCreateScreenOfProductVariantPhoto()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/product/variant/%s/photo/create', $productVariant->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerCanCanOpenCreateScreenOfProductVariantPhoto()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->get(sprintf('/dashboard/product/variant/%s/photo/create', $productVariant->getKey()))
            ->assertForbidden();
    }

    public function testCanStoreNewProductVariantPhoto()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        $this->actingAs($productVariant->master->owner->user);

        $productVariantPhotoCountBeforeStore = ProductPhoto::count();

        /** @var FilesystemAdapter */
        $fakeStorage = Storage::fake(File::fileDisk());

        $photo = UploadedFile::fake()->image('photo.jpg');

        $this

            ->post(
                uri: sprintf('/dashboard/product/variant/%s/photo', $productVariant->getKey()),
                data: [
                    'name' => $this->faker->words(2, true),
                    'description' => $this->faker->words(20, true),
                    'photo' => $photo,
                ]
            )

            ->assertSuccessful();

        $this->assertDatabaseCount('product_photos', ++$productVariantPhotoCountBeforeStore);

        $fakeStorage->assertExists($photo->hashName('media/product/photos'));
    }

    public function testOnlyUserWithOwnerProfileCanStoreNewProductVariantPhoto()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->post(sprintf('/dashboard/product/variant/%s/photo', $productVariant->getKey()))
            ->assertForbidden();
    }

    public function testOnlyOwnerOfTheProductVariantCanStoreNewProductVariantPhoto()
    {
        /** @var ProductVariant */
        $productVariant = ProductVariant::factory()->create();

        /** @var ProfileOwner */
        $owner = ProfileOwner::factory()->create();

        $this->actingAs($owner->user);

        $this

            ->post(sprintf('/dashboard/product/variant/%s/photo', $productVariant->getKey()))
            ->assertForbidden();
    }
}
