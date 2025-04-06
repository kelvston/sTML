<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimilarityLog extends Model
{
    protected $casts = [
        'similarity_score' => 'float',
    ];

    public function newTopic()
    {
        return $this->belongsTo(ResearchTopic::class, 'new_topic_id');
    }

    public function existingTopic()
    {
        return $this->belongsTo(ResearchTopic::class, 'existing_topic_id');
    }
}
