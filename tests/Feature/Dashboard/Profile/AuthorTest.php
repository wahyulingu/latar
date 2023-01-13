<?php

namespace Tests\Feature\Dashboard\Profile;

use App\Models\Profile\ProfileAuthor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testShowScreenOfAuthorProfileCanBeRendered()
    {
        /** @var ProfileAuthor */
        $profile = ProfileAuthor::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->get(sprintf('/dashboard/profile/author/%s', $profile->user->username))
            ->assertSuccessful();
    }

    public function testEditScreenOfAuthorProfileCanBeRendered()
    {
        /** @var ProfileAuthor */
        $profile = ProfileAuthor::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->get(sprintf('/dashboard/profile/author/%s/edit', $profile->user->username))
            ->assertSuccessful();
    }

    public function testCanUpdateAuthorProfile()
    {
        /** @var ProfileAuthor */
        $profile = ProfileAuthor::factory()->create();

        $this->actingAs($profile->user);

        $data = [
            'name' => $this->faker->name,
        ];

        $this

            ->patch(sprintf('/dashboard/profile/author/%s', $profile->user->username), $data)
            ->assertSuccessful();

        $actualData = $profile->fresh();

        $this->assertEquals($data['name'], $actualData->name);
    }

    public function testCanStoreAuthorProfile()
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

            ->post(sprintf('/dashboard/profile/author/%s', $user->username), $data)
            ->assertSuccessful();

        $this->assertDatabaseHas('profile_authors', $data);
    }

    public function testStoreRequestUnpreocessedIfRequestedUserHasAuthorProfile()
    {
        /** @var ProfileAuthor */
        $profile = ProfileAuthor::factory()->create();

        $this->actingAs($profile->user);

        $this

            ->post(sprintf('/dashboard/profile/author/%s', $profile->user->username),
                data: [
                    'name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'phone' => $this->faker->phoneNumber,
                    'bio' => $this->faker->words(8, true),
                    'address' => $this->faker->address,
                ])

            ->assertUnprocessable();
    }

    public function testUpdateRequestUnprocessedIfRequestedUserDontHasAuthorProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->patch(sprintf('/dashboard/profile/author/%s', $user->username),
                data: [
                    'name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'phone' => $this->faker->phoneNumber,
                    'bio' => $this->faker->words(8, true),
                    'address' => $this->faker->address,
                ])

            ->assertUnprocessable();
    }

    public function testUserCanOpenShowScreenOfAnotherUserAuthorProfile()
    {
        /** @var ProfileAuthor */
        $profile = ProfileAuthor::factory()->create();

        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/author/%s', $profile->user->username))
            ->assertSuccessful();
    }

    public function testShowNotfoundScreenIfRequestedUserDontHasAuthorProfile()
    {
        /** @var User */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this

            ->get(sprintf('/dashboard/profile/author/%s', $user->username))
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

            ->get(sprintf('/dashboard/profile/author/%s', $missingUser->username))
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

            ->patch(sprintf('/dashboard/profile/author/%s', $missingUser->username))
            ->assertNotFound();
    }
}
