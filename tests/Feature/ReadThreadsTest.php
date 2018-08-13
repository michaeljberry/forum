<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
     {
        parent::setUp();
        $this->thread = create(Thread::class);
     }
    public function test_a_user_can_view_all_threads()
    {
        $this->get(route('threads'))
            ->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    public function test_a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, [
            'attributes' => [
                'channel_id' => $channel->id
            ]
        ]);
        $threadNotInChannel = create(Thread::class);

        $this->get(route('channel', ['channel' => $channel->slug]))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    public function test_a_user_can_filter_by_any_username()
    {
        $this->signIn(create(User::class, [
            'attributes' => [
                'name' => 'JohnDoe'
            ]
        ]));

        $threadByJohn = create(Thread::class, [
            'attributes' => [
                'user_id' => auth()->id()
            ]
        ]);
        $threadNotByJohn = create(Thread::class);

        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    public function test_a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = create(Thread::Class);
        create(Reply::class, [
            'attributes' => [
                'thread_id' => $threadWithTwoReplies->id
            ],
            'times' => 2
        ]);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, [
            'attributes' => [
                'thread_id' => $threadWithThreeReplies->id
            ],
            'times' => 3
        ]);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }

    public function test_a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create(Thread::class);
        create(Reply::class, [
            'attributes' => [
                'thread_id' => $thread->id
            ],
            'times' => 2
        ]);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
