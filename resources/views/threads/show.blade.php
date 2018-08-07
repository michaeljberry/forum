@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ route('profile', $thread->creator) }}">
                                    {{  $thread->creator->name }} posted:
                                </a>
                                {{ $thread->title }}
                            </span>
                            @can('update', $thread)
                                <form action="{{ $thread->path() }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link">Delete</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <replies :data="{{  $thread->replies }}" @removed="repliesCount--"></replies>

                <!-- {{ $replies->links() }} -->

                @if (auth()->check())
                    <form method="POST" action="{{ $thread->path() . '/replies' }} ">
                        @csrf
                        <div class="form-group">
                            <textarea name="body" id="body" rows="5" class="form-control" placeholder="Have something to say?"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Post</button>
                        </div>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{  route('login') }}">sign in</a> to participate in this discussion.</p>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="">{{ $thread->creator->name }}</a>, and currently has <span v-text="repliesCount"></span> {{  str_plural('comment', $thread->replies_count) }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection
