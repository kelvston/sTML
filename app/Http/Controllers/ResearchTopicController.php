<?php
namespace App\Http\Controllers;

use App\Models\ResearchTopic;
use App\Models\SimilarityLog;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResearchTopicController extends Controller
{
    public function index()
    {
        $topics = ResearchTopic::with('user')->latest()->paginate(10);

        $user = auth()->user();

        $pendingTopics = $user->topics()->where('ts.status', 'pending')
            ->leftJoin('topic_supervisors as ts','ts.topic_id','=','research_topics.id')->get();
        $approvedTopics = $user->topics()->where('ts.status', 'approved')->leftJoin('topic_supervisors as ts','ts.topic_id','=','research_topics.id')->get();
        $rejectedTopics = $user->topics()->where('ts.status', 'rejected')->leftJoin('topic_supervisors as ts','ts.topic_id','=','research_topics.id')->get();
        return view('topics.index', compact('pendingTopics', 'approvedTopics', 'rejectedTopics','topics'));
    }

    public function store(Request $request)
    {

        $request->validate(['title' => 'required|string|max:500']);

        // 1. Similarity Check
        $similarityCheck = $this->checkTopicSimilarity($request->title);

//        if ($similarityCheck['similarity'] >= 60) {
//            return $this->handleRejection($request->title, $similarityCheck);
//        }
        // 2. Create Topic
        $topic = ResearchTopic::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'status' => 'pending',
            'similarity_score' => $similarityCheck['similarity']
        ]);

        // 3. Log similarity approval
        $topic->approvals()->create([
            'stage' => 'similarity_check',
            'status' => 'approved',
            'action_by' => auth()->id(),
        ]);

        // 4. Assign potential supervisors (auto or manual)
        $this->assignSupervisors($topic);

        return redirect()->route('topics.show', $topic)
            ->with('success', 'Topic submitted! Awaiting supervisor assignment.');
    }

    public function show(ResearchTopic $topic)
    {
//        $similarTopics = ResearchTopic::where('id', '!=', $topic->id)
//            ->select('id', 'title', 'user_id')
//            ->selectRaw('similarity(title, ?) * 100 as similarity', [$topic->title])
//            ->having('similarity', '>', 10) // Minimum 10% similarity
//            ->with('user:id,name')
//            ->get();
        return view('topics.show', [
            'topic' => $topic->load(['approvals', 'supervisors']),
//            'similarTopics' => $similarTopics
        ]);
    }

    private function assignSupervisors(ResearchTopic $topic)
    {

        // Example: Auto-assign 3 supervisors based on expertise
        $supervisors = Supervisor::where('expertise', 'LIKE', "%{$topic->keywords}%")
//            ->whereHas('user', fn($q) => $q->where('active', true))
            ->inRandomOrder()
            ->limit(3)
            ->get();

        foreach ($supervisors as $supervisor) {
            $topic->supervisors()->attach($supervisor, [
                'status' => 'pending',
                'feedback' => null
            ]);
        }

        // Log assignment stage
        $topic->approvals()->create([
            'stage' => 'supervisor_assignment',
            'status' => 'pending',
            'action_by' => auth()->id(),
        ]);
    }

    private function checkTopicSimilarity(string $newTitle): array
    {
        $mostSimilar = ResearchTopic::select('id', 'title')
            ->selectRaw('similarity(title, ?) * 100 as similarity', [$newTitle])
            ->orderByDesc('similarity')
            ->first();

        $similarity = $mostSimilar ? (float)$mostSimilar->similarity : 0;

        return [
            'similarity' => $similarity,
            'similar_topic' => $similarity >= 40 ? $mostSimilar : null, // Lower threshold recommended
        ];
    }

    public function searchSimilar(Request $request)
    {
        $request->validate(['query' => 'required|string|min:3']);

        $searchTerm = $request->input('query');
        $pythonScript = base_path('phython_scripts/calculate_similarity.py');



        $results = ResearchTopic::with('user:id,name')
            ->get()
            ->map(function ($topic) use ($searchTerm, $pythonScript) {
                $command = sprintf(
                    'python3 %s %s %s 2>&1',
                    escapeshellarg($pythonScript),
                    escapeshellarg($searchTerm),
                    escapeshellarg($topic->title)
                );

                $output = shell_exec($command);
                $result = json_decode($output, true);

                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'status' => $topic->status,
                    'user' => $topic->user,
                    'similarity' => $result['similarity'] ?? 0
                ];
            })
            ->filter(fn($topic) => $topic['similarity'] >= 40)
            ->sortByDesc('similarity')
            ->values();

//        dd($results);
        return response()->json($results);
    }





}
