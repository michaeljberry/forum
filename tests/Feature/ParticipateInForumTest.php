<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function test_an_unauthenticated_user_may_not_participate_in_forum_threads()
    {
        $this->withExceptionHandling()
            ->post(route('replies', [
                    'channel' => 'some-channel',
                    'thread' => 1
            ]),[])
            ->assertRedirect('/login');
    }

    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class);
        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    public function test_a_reply_requires_a_body()
    {
        $this->withExceptionHandling()
            ->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class, [
            'attributes' => [
                'body' => null
            ]
        ]);
        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors();
    }

    public function test_unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect(route('login'));

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(302);
    }

    public function test_authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create(Reply::class, [
            'attributes' => [
                'user_id' => auth()->id()
            ]
        ]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    public function test_authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create(Reply::class, [
            'attributes' => [
                'user_id' => auth()->id()
            ]
        ]);
        $updatedReply = "You are changed.";
        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    public function test_unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect(route('login'));

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(302);
    }
}
