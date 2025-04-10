<?php

namespace App\Http\Controllers;

use App\Models\ResearchTopic;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $topics = ResearchTopic::with('user')->latest()->paginate(10);

        $user = auth()->user();

        $pendingTopics = $user->topics()->where('ts.status', 'pending')
            ->leftJoin('topic_supervisors as ts','ts.topic_id','=','research_topics.id')->get();
        $approvedTopics = $user->topics()->where('ts.status', 'approved')->leftJoin('topic_supervisors as ts','ts.topic_id','=','research_topics.id')->get();
        $rejectedTopics = $user->topics()->where('ts.status', 'rejected')->leftJoin('topic_supervisors as ts','ts.topic_id','=','research_topics.id')->get();
//        return view('topics.index', compact('pendingTopics', 'approvedTopics', 'rejectedTopics','topics'));

        return view('home', compact('pendingTopics', 'approvedTopics', 'rejectedTopics','topics'));
    }
}
