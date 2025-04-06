<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class VerifyStoragePermissions extends Command
{
    protected $signature = 'storage:verify';

    public function handle()
    {
        $path = 'templates/research_topics_template.xlsx';
        $fullPath = storage_path('app/'.$path);

        $this->info("Checking: ".$fullPath);

        // Check directory
        $dir = dirname($fullPath);
        $this->info("Directory exists: ".(is_dir($dir) ? 'YES' : 'NO'));
        $this->info("Directory writable: ".(is_writable($dir) ? 'YES' : 'NO'));

        // Try creating test file
        $testFile = $dir.'/test_'.time().'.txt';
        try {
            file_put_contents($testFile, 'test');
            $this->info("File creation: SUCCESS");
            unlink($testFile);
        } catch (\Exception $e) {
            $this->error("File creation failed: ".$e->getMessage());
        }
    }
}
