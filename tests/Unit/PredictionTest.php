<?php

namespace Tests\Unit;

use App\User;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PredictionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_prediction_has_an_owner() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(1)
            ->withMembers(1)
            ->withEpisodes(1)
            ->withResults(1)
            ->withPredictions(1)
            ->create();

        $episode = $season->episodes->first();
        $user = $episode->predictions->first()->owner;

        $this->assertInstanceOf(
            User::class,
            $episode->userPredictions($user->id)
                ->first()
                ->owner
        );
    }
}