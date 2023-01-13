<?php

namespace Tests\Feature\Dashboard\Profile;

use App\Models\Profile\ProfileOwner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testShowScreenOfOwnerProfileCanBeRendered()
    {
        /** @var ProfileOwner */
        $profile = ProfileOwner::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->get(sprintf('/dashboard/profile/owner/%s', $profile->user->username))
            ->assertSuccessful();
    }

    public function testEditScreenOfOwnerProfileCanBeRendered()
    {
        /** @var ProfileOwner */
        $profile = ProfileOwner::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->get(sprintf('/dashboard/profile/owner/%s/edit', $profile->user->username))
            ->assertSuccessful();
    }

    public function testCanUpdateOwnerProfile()
    {
        /** @var ProfileOwner */
        $profile = ProfileOwner::factory()->create();

        $this->actingAs($profile->user);

        $data = [
            'name' => $this->faker->name,
            'bio' => ucfirst($this->faker->words(20, true)),
        ];

        $this

            ->patch(sprintf('/dashboard/profile/owner/%s', $profile->user->username), $data)
            ->assertSuccessful();

        $actualData = $profile->fresh();

        $this->assertEquals($data['name'], $actualData->name);
        $this->assertEquals($data['bio'], $actualData->bio);
    }

    public function testCanStoreOwnerProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'brand' => $this->faker->words(4, true),
            'bio' => ucfirst($this->faker->words(20, true)),
            'address' => $this->faker->address,
        ];

        $this

            ->post(sprintf('/dashboard/profile/owner/%s', $user->username), $data)
            ->assertSuccessful();

        $this->assertDatabaseHas('profile_owners', $data);
    }

    public function testStoreRequestUnpreocessedIfRequestedUserHasOwnerProfile()
    {
        /** @var ProfileOwner */
        $profile = ProfileOwner::factory()->create();

        $this->actingAs($profile->user);

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'brand' => $this->faker->words(4, true),
            'bio' => ucfirst($this->faker->words(20, true)),
            'address' => $this->faker->address,
        ];

        $this

            ->post(sprintf('/dashboard/profile/owner/%s', $profile->user->username), $data)
            ->assertUnprocessable();
    }

    public function testUpdateRequestUnprocessedIfRequestedUserDontHasOwnerProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->patch(sprintf('/dashboard/profile/owner/%s', $user->username))
            ->assertUnprocessable();
    }

    public function testUserCanOpenShowScreenOfAnotherUserOwnerProfile()
    {
        /** @var ProfileOwner */
        $profile = ProfileOwner::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/owner/%s', $profile->user->username))
            ->assertSuccessful();
    }

    public function testShowNotfoundScreenIfRequestedUserDontHasOwnerProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/owner/%s', $user->username))
            ->assertNotFound();
    }

    public function testShowNotfoundScreenIfRequestedUserNotExists()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $missingUser = User::factory()->create();

        $missingUser->delete();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/owner/%s', $missingUser->username))
            ->assertNotFound();
    }

    public function testUpdateShowNotfoundScreenIfRequestedUserNotExists()
    {
        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $missingUser = User::factory()->create();

        $missingUser->delete();

        $this->actingAs($user);

        $this

            ->patch(sprintf('/dashboard/profile/owner/%s', $missingUser->username))
            ->assertNotFound();
    }
}
