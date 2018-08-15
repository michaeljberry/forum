<?php

namespace Tests\Unit;

use App\User;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }

    public function test_a_thread_can_make_a_string_path()
    {
        $thread = create(Thread::class);
        $this->assertEquals(
            route('thread', ['channel' => $thread->channel->slug, 'thread' => $thread->id]),
            $thread->path()
        );
    }

    public function test_a_thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    public function test_a_thread_can_have_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);
        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_belongs_to_a_channel()
    {
        $thread = create(Thread::class);
        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());

    }

    public function test_a_thread_can_be_unsubscribed_from()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }
}
