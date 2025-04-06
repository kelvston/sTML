<?php
// app/Models/Approval.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'stage',
        'status',
        'action_by',
        'research_topic_id'
    ];

    public function topic()
    {
        return $this->belongsTo(ResearchTopic::class,'topic_id');
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
