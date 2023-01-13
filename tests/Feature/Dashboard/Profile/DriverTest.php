<?php

namespace Tests\Feature\Dashboard\Profile;

use App\Models\Profile\ProfileDriver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testShowScreenOfDriverProfileCanBeRendered()
    {
        /** @var ProfileDriver */
        $profile = ProfileDriver::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->get(sprintf('/dashboard/profile/driver/%s', $profile->user->username))
            ->assertSuccessful();
    }

    public function testEditScreenOfDriverProfileCanBeRendered()
    {
        /** @var ProfileDriver */
        $profile = ProfileDriver::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->get(sprintf('/dashboard/profile/driver/%s/edit', $profile->user->username))
            ->assertSuccessful();
    }

    public function testCanUpdateDriverProfile()
    {
        /** @var ProfileDriver */
        $profile = ProfileDriver::factory()->create();

        $this->actingAs($profile->user);

        $data = [
            'name' => $this->faker->name,
        ];

        $this

            ->patch(sprintf('/dashboard/profile/driver/%s', $profile->user->username), $data)
            ->assertSuccessful();

        $actualData = $profile->fresh();

        $this->assertEquals($data['name'], $actualData->name);
    }

    public function testCanStoreDriverProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'bio' => $this->faker->words(8, true),
            'address' => $this->faker->address,
        ];

        $this

            ->post(sprintf('/dashboard/profile/driver/%s', $user->username), $data)
            ->assertSuccessful();

        $this->assertDatabaseHas('profile_drivers', $data);
    }

    public function testStoreRequestUnpreocessedIfRequestedUserHasDriverProfile()
    {
        /** @var ProfileDriver */
        $profile = ProfileDriver::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->post(sprintf('/dashboard/profile/driver/%s', $profile->user->username),
                data: [
                    'name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'phone' => $this->faker->phoneNumber,
                    'bio' => $this->faker->words(8, true),
                    'address' => $this->faker->address,
                ])

            ->assertUnprocessable();
    }

    public function testUpdateRequestUnprocessedIfRequestedUserDontHasDriverProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->patch(sprintf('/dashboard/profile/driver/%s', $user->username),
                data: [
                    'name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'phone' => $this->faker->phoneNumber,
                    'bio' => $this->faker->words(8, true),
                    'address' => $this->faker->address,
                ])

            ->assertUnprocessable();
    }

    public function testUserCanOpenShowScreenOfAnotherUserDriverProfile()
    {
        /** @var ProfileDriver */
        $profile = ProfileDriver::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/driver/%s', $profile->user->username))
            ->assertSuccessful();
    }

    public function testShowNotfoundScreenIfRequestedUserDontHasDriverProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/driver/%s', $user->username))
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

            ->get(sprintf('/dashboard/profile/driver/%s', $missingUser->username))
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

            ->patch(sprintf('/dashboard/profile/driver/%s', $missingUser->username))
            ->assertNotFound();
    }
}
