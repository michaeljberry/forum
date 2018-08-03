<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Activity;

class ManageThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get(route('create-thread'))
            ->assertRedirect(route('login'));

        $this->post(route('threads'))
            ->assertRedirect(route('login'));
    }

    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);

        $response = $this->post(route('threads'), $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    public function test_a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function test_a_thread_requires_a_valid_channel()
    {
        create(Channel::class, ['times' => 2]);

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function test_unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create(Thread::class);

        $this->delete($thread->path())->assertRedirect(route('login'));

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }

    public function test_authorized_users_can_delete_threads()
    {
        $this->signIn();
        $thread = create(Thread::class, [
            'attributes' => [
                'user_id' => auth()->id()
            ]
        ]);
        $reply = create(Reply::class, [
            'attributes' => [
                'thread_id' => $thread->id
            ]
        ]);

        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    public function publishThread($overrides = [])
    {

        $this->withExceptionHandling()->signIn();

        $thread = make(Thread::class, ['attributes' => $overrides]);

        return $this->post(route('threads'), $thread->toArray());
    }

}
