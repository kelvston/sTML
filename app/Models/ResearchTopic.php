<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchTopic extends Model
{
    protected $fillable = ['user_id','title','status'];
    protected $casts = [
        'similarity_score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function similarTo()
    {
        return $this->belongsTo(ResearchTopic::class, 'similar_to_id');
    }

    public function similarityLogs()
    {
        return $this->hasMany(SimilarityLog::class, 'new_topic_id');
    }

//    public function supervisors()
//    {
//        return $this->belongsToMany(Supervisor::class, 'topic_supervisors')
//            ->withPivot('status', 'feedback')
//            ->withTimestamps();
//    }
    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'topic_supervisors', 'topic_id', 'supervisor_id')
            ->withPivot('status', 'feedback')
            ->withTimestamps();
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class,'topic_id');
    }
}
