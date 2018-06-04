@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row col-md-8 col-md-offset-2">
            <div class="page-header">
                <h1>
                    {{ $profileUser->name }}
                </h1>
            </div>
            @foreach ($activities as $date => $activity)
                <h3 class="page-header">{{ $date }}</h3>
                @foreach($activity as $record)
                    @include("profiles.activities.{$record->type}", ['activity' => $record])
                @endforeach
            @endforeach
        </div>

        {{-- {{ $activity->links() }}--}}
    </div>
@endsection