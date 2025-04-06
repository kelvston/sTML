<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $fillable = [
        'user_id',
        'expertise',
        'max_students'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topics()
    {
        return $this->belongsToMany(ResearchTopic::class, 'topic_supervisors')
            ->withPivot('status', 'feedback')
            ->withTimestamps();
    }

    public function researchTopics()
    {
        return $this->belongsToMany(ResearchTopic::class, 'topic_supervisors', 'supervisor_id', 'topic_id')
            ->withPivot('status', 'feedback')
            ->withTimestamps();
    }
    // app/Models/Supervisor.php
    public function pendingTopics()
    {
        return $this->belongsToMany(ResearchTopic::class, 'topic_supervisors', 'supervisor_id', 'topic_id')
            ->wherePivot('status', 'pending')
            ->withPivot('feedback');
    }

    public function approvedTopics()
    {
        return $this->belongsToMany(ResearchTopic::class, 'topic_supervisors')
            ->wherePivot('status', 'approved')
            ->withPivot('feedback');
    }

}
