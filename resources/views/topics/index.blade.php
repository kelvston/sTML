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
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Submit Research Topic</div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-3" id="topicStatusTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                        Pending <span class="badge bg-warning">{{ $pendingTopics->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">
                        Approved <span class="badge bg-success">{{ $approvedTopics->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">
                        Rejected <span class="badge bg-danger">{{ $rejectedTopics->count() }}</span>
                    </button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" id="topicStatusTabsContent">
                <!-- Pending Topics -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    @if($pendingTopics->isEmpty())
                        <div class="alert alert-info">You have no pending topics.</div>
                    @else
                        @foreach($pendingTopics as $topic)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $topic->title }}</h5>
                                    <p class="card-text">
                                        Submitted on: {{ $topic->created_at->format('M d, Y') }}<br>
                                        Current Status: <span class="badge bg-warning">Pending</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Approved Topics -->
                <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                    @if($approvedTopics->isEmpty())
                        <div class="alert alert-info">You have no approved topics.</div>
                    @else
                        @foreach($approvedTopics as $topic)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $topic->title }}</h5>
                                    <p class="card-text">
                                        Submitted on: {{ $topic->created_at->format('M d, Y') }}<br>
                                        Current Status: <span class="badge bg-success">Approved</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Rejected Topics -->
                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    @if($rejectedTopics->isEmpty())
                        <div class="alert alert-info">You have no rejected topics.</div>
                    @else
                        @foreach($rejectedTopics as $topic)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $topic->title }}</h5>
                                    <p class="card-text">
                                        Submitted on: {{ $topic->created_at->format('M d, Y') }}<br>
                                        Current Status: <span class="badge bg-danger">Rejected</span>
                                    </p>
                                    @if($topic->rejection_reason)
                                        <p class="card-text"><strong>Reason:</strong> {{ $topic->rejection_reason }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Submit Research Topic</div>

                <div class="card-body">
                    <form id="topicForm" action="{{ route('topics.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">Research Title</label>
                            <input id="title" type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   name="title" required autofocus
                                   oninput="checkSimilarTopics(this.value)">
                            @error('title')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                    </form>

                    <div id="similarTopicsContainer">
                        <h4>Similar Topics</h4>
                        <div class="alert alert-info" id="defaultSimilarMessage">
                            Start typing to see similar existing topics...
                        </div>
                        <div id="similarTopicsResults"></div>
                    </div>

                    <!-- Submit Button (hidden by default) -->
                    <button id="submitTopicButton" class="btn btn-primary mt-3" style="display: none;" onclick="submitTopic()">Submit Topic</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkSimilarTopics(query) {
            if (query.length < 3) {
                document.getElementById('similarTopicsResults').innerHTML = '';
                document.getElementById('defaultSimilarMessage').style.display = 'block';
                document.getElementById('submitTopicButton').style.display = 'none';
                return;
            }

            document.getElementById('defaultSimilarMessage').style.display = 'none';

            fetch(`{{ route('topics.search-similar') }}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(topics => {
                    let html = '';
                    // Determine if we need to show the submit button.
                    // We show the button if there are no topics with similarity >= 80%.
                    let showSubmit = true;

                    if (topics.length > 0) {
                        html = '<div class="list-group mt-3">';
                        topics.forEach(topic => {
                            const similarity = topic.similarity;
                            const badgeClass = similarity >= 60 ? 'bg-danger' : 'bg-warning';
                            html += `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>${topic.title}</strong>
                                            <div class="text-muted small">
                                                ${topic.user.name} • ${topic.status}
                                            </div>
                                        </div>
                                        <span class="badge ${badgeClass}">
                                            ${similarity.toFixed(1)}% similar
                                        </span>
                                    </div>
                                </div>
                            `;
                            // If any topic is 80% or more similar, then hide the submit button.
                            if (similarity >= 80) {
                                showSubmit = false;
                            }
                        });
                        html += '</div>';
                    } else {
                        html = '<div class="alert alert-warning mt-3">No similar topics found</div>';
                    }

                    document.getElementById('similarTopicsResults').innerHTML = html;
                    document.getElementById('submitTopicButton').style.display = showSubmit ? 'block' : 'none';
                })
                .catch(() => {
                    document.getElementById('similarTopicsResults').innerHTML =
                        '<div class="alert alert-danger mt-3">Error checking similar topics</div>';
                    document.getElementById('submitTopicButton').style.display = 'none';
                });
        }

        // Function to handle topic submission
        function submitTopic() {
            const title = document.getElementById('title').value;
            if (title) {
                document.getElementById('topicForm').submit();
            }
        }
    </script>
    <script>
        $(document).ready(function(){
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@endsection
