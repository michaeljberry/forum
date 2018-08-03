<?php

namespace App;

use App\Thread;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    use RecordsActivity;

    use Favoritable;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }
}
