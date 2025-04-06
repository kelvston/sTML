@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h2>Supervisor Dashboard</h2>

        <div class="card mt-4">
            <div class="card-header">Pending Topic Reviews</div>

            <div class="card-body">
                @foreach($pendingTopics as $topic)
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- Topic Details -->
                            <h5 class="card-title">{{ $topic->title }}</h5>
                            <p class="card-text">
                                Submitted by: {{ $topic->user->name }}<br>
                                Registration Number: {{$topic->user->registration_number}}<br>
                                School: {{$topic->user->school}}<br>
                                Submitted on: {{ $topic->created_at->format('M d, Y') }}
                            </p>

                            <!-- Similar Topics Section --><hr>
                            <div class="similar-topics mb-3"
                                 data-topic-id="{{ $topic->id }}"
                                 data-topic-title="{{ $topic->title }}">
                                <h6>Similar Topics</h6>
                                <div class="alert alert-info">Loading similar topics...</div>
                            </div>

                            <!-- Approval/Reject Forms -->
                            <div class="mt-3">
                                <form action="{{ route('supervisor.topics.approve', $topic) }}" method="POST" class="d-inline">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="feedback-{{ $topic->id }}" class="form-label">Feedback (optional):</label>
                                        <textarea name="feedback" id="feedback-{{ $topic->id }}" class="form-control" rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>

                                <form action="{{ route('supervisor.topics.reject', $topic) }}" method="POST" class="d-inline">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="reject-feedback-{{ $topic->id }}" class="form-label">Rejection Reason:</label>
                                        <textarea name="feedback" id="reject-feedback-{{ $topic->id }}" class="form-control" rows="2" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- JavaScript to fetch similar topics for each pending topic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const similarTopicContainers = document.querySelectorAll('.similar-topics');

            similarTopicContainers.forEach(container => {
                const topicTitle = container.getAttribute('data-topic-title');

                fetch(`{{ route('topics.search-similar') }}?query=${encodeURIComponent(topicTitle)}`)
                    .then(response => response.json())
                    .then(similarTopics => {
                        // Limit to top 5 similar topics if there are more than 5
                        similarTopics = similarTopics.slice(0, 5);

                        let html = '';
                        if (similarTopics.length > 0) {
                            html = '<div class="list-group" style="max-height: 300px; overflow-y: auto;">';
                            similarTopics.forEach(similar => {
                                if (parseInt(similar.id) === parseInt(container.getAttribute('data-topic-id'))) return;
                                const badgeClass = similar.similarity >= 60 ? 'bg-danger' : 'bg-warning';
                                html += `
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${similar.title}</strong>
                                        <div class="text-muted small">
                                            ${similar.user.name} • ${similar.status}
                                        </div>
                                    </div>
                                    <span class="badge ${badgeClass}">
                                        ${similar.similarity.toFixed(1)}% similar
                                    </span>
                                </div>
                            </div>
                        `;
                            });
                            html += '</div>';
                        } else {
                            html = '<div class="alert alert-warning">No similar topics found</div>';
                        }
                        container.innerHTML = html;
                    })
                    .catch(() => {
                        container.innerHTML = '<div class="alert alert-danger">Error loading similar topics</div>';
                    });
            });
        });
    </script>
@endsection
