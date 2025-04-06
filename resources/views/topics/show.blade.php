@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>{{ $topic->title }}</h1>
        <p class="text-muted">Status: {{ $topic->status }}</p>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Approval Progress</h5>
                <ul class="list-group">
                    @foreach($topic->approvals as $approval)
                        <li class="list-group-item">
                            {{ $approval->stage }}:
                            <span class="badge bg-{{ $approval->status === 'approved' ? 'success' : 'warning' }}">
                        {{ $approval->status }}
                    </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
