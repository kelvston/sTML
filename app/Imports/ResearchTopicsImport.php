<?php
namespace App\Imports;

use App\Http\Controllers\ResearchTopicController;
use App\Models\ResearchTopic;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ResearchTopicsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {


        return new ResearchTopic([
            'title'         => $row['title'],
            'user_id'      => auth()->id(),
            'status' => 'pending',
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:500|unique:research_topics,title',
            'status' => 'nullable|in:pending,approved,rejected',
        ];
    }
}
