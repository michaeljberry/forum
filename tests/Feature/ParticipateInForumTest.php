<?php

namespace Tests\Feature;

use Tests\TestCase;
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
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->get($thread->path())
            ->assertSee($reply->body);

    }
}
