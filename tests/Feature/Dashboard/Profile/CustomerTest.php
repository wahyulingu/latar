<?php

namespace Tests\Feature\Dashboard\Profile;

use App\Models\Profile\ProfileCustomer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testShowScreenOfCustomerProfileCanBeRendered()
    {
        /** @var ProfileCustomer */
        $profile = ProfileCustomer::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->get(sprintf('/dashboard/profile/customer/%s', $profile->user->username))
            ->assertSuccessful();
    }

    public function testEditScreenOfCustomerProfileCanBeRendered()
    {
        /** @var ProfileCustomer */
        $profile = ProfileCustomer::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->get(sprintf('/dashboard/profile/customer/%s/edit', $profile->user->username))
            ->assertSuccessful();
    }

    public function testCanUpdateCustomerProfile()
    {
        /** @var ProfileCustomer */
        $profile = ProfileCustomer::factory()->create();

        $this->actingAs($profile->user);

        $data = [
            'name' => $this->faker->name,
        ];

        $this

            ->patch(sprintf('/dashboard/profile/customer/%s', $profile->user->username), $data)
            ->assertSuccessful();

        $actualData = $profile->fresh();

        $this->assertEquals($data['name'], $actualData->name);
    }

    public function testCanStoreCustomerProfile()
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

            ->post(sprintf('/dashboard/profile/customer/%s', $user->username), $data)
            ->assertSuccessful();

        $this->assertDatabaseHas('profile_customers', $data);
    }

    public function testStoreRequestUnpreocessedIfRequestedUserHasCustomerProfile()
    {
        /** @var ProfileCustomer */
        $profile = ProfileCustomer::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->post(sprintf('/dashboard/profile/customer/%s', $profile->user->username),
                data: [
                    'name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'phone' => $this->faker->phoneNumber,
                    'bio' => $this->faker->words(8, true),
                    'address' => $this->faker->address,
                ])

            ->assertUnprocessable();
    }

    public function testUpdateRequestUnprocessedIfRequestedUserDontHasCustomerProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->patch(sprintf('/dashboard/profile/customer/%s', $user->username),
                data: [
                    'name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'phone' => $this->faker->phoneNumber,
                    'bio' => $this->faker->words(8, true),
                    'address' => $this->faker->address,
                ])

            ->assertUnprocessable();
    }

    public function testUserCanOpenShowScreenOfAnotherUserCustomerProfile()
    {
        /** @var ProfileCustomer */
        $profile = ProfileCustomer::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/customer/%s', $profile->user->username))
            ->assertSuccessful();
    }

    public function testShowNotfoundScreenIfRequestedUserDontHasCustomerProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/customer/%s', $user->username))
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

            ->get(sprintf('/dashboard/profile/customer/%s', $missingUser->username))
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

            ->patch(sprintf('/dashboard/profile/customer/%s', $missingUser->username))
            ->assertNotFound();
    }
}
