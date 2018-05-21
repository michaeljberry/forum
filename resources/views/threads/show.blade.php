@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="#">
                        {{  $thread->creator->name }} posted:
                    </a>
                    {{ $thread->title }}
                </div>
                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($thread->replies as $reply)
                @include('threads.reply')
            @endforeach
        </div>
    </div>
    @if (auth()->check())
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ $thread->path() . '/replies' }} ">
                @csrf
                <div class="form-group">
                    <textarea name="body" id="body" rows="5" class="form-control" placeholder="Have something to say?"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Post</button>
                </div>
            </form>
        </div>
    </div>
    @else
        <p class="text-center">Please <a href="{{  route('login') }}">sign in</a> to participate in this discussion.</p>
    @endif
</div>
@endsection
