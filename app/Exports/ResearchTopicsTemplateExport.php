<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResearchTopicsTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['Sample Research Topic 1', 'pending'],
        ];
    }

    public function headings(): array
    {
        return [
            'title',
            'status',
        ];
    }
}
