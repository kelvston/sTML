@extends('layouts.dashboard')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h4>Import Research Topics</h4>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h5>Download Template:</h5>
                <a href="{{ route('supervisor.import.template') }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download Excel Template
                </a>
            </div>

            <form action="{{ route('supervisor.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Select Excel File</label>
                    <input type="file" class="form-control-file" id="file" name="file" required>
                    @error('file')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success mt-3">
                    <i class="fas fa-upload"></i> Import Topics
                </button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@endsection
