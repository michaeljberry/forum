<?php

namespace Tests\Feature;

use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{

    use DatabaseMigrations;

    public function test_a_channel_consists_of_threads()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, [
            'attributes' => [
                'channel_id' => $channel->id
            ]
        ]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
