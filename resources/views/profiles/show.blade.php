@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row col-md-8 col-md-offset-2">
            <div class="page-header">
                <h1>
                    {{ $profileUser->name }}
                    <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                </h1>
            </div>
            @foreach ($threads as $thread)
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="#">
                                    {{  $thread->creator->name }} posted:
                                </a>
                                {{ $thread->title }}
                            </span>
                            <span>
                                {{ $thread->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>
            @endforeach
        </div>

        {{ $threads->links() }}
    </div>
@endsection