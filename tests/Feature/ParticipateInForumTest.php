<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function test_an_unauthenticated_user_may_not_participate_in_forum_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads/1/replies', []);
    }

    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();
        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->get($thread->path())
            ->assertSee($reply->body);

    }
}
