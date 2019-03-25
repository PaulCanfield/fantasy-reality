<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_owners_can_not_invite_users() {
        //$this->withoutExceptionHandling();

        $max = $this->signIn();
        $sam = factory(User::class)->create();

        $season = SeasonFactory::create();

        $this->actingAs($max)
            ->post($season->path().'/invite', [
              'email' => $sam->email
            ])
            ->assertStatus(403);
    }

    /** @test */
    public function a_season_can_invite_users()
    {
        $this->withoutExceptionHandling();
        $season = SeasonFactory::create();

        $james = factory(User::class)->create();

        $this->actingAs($season->owner)
            ->post($season->path().'/invite', [
                'email' => $james->email
            ]);

        $this->assertTrue(
            $season->members->contains($james)
        );
    }

    /** @test */
    public function the_invited_email_address_must_be_associated_with_a_valid_account() {
        $season = SeasonFactory::create();

        $this->actingAs($season->owner)
            ->post($season->path().'/invite', [
                'email' => 'invalid@email.com'
            ])
            ->assertSessionHasErrors([
                'email' => 'The invited user must have an account.'
            ]);
    }

    /** @test */
    public function an_invited_user_can_add_bakers() {
        $season = SeasonFactory::create();

        $season->invite($newUser = factory(User::class)->create());

        $this->signIn($newUser);

        $this->post(action('SeasonBakersController@store', $season), ['name' => 'Baker']);

        $this->assertDatabaseHas('bakers', ['name' => 'Baker']);
    }
}
