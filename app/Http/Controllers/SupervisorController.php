<?php

namespace App\Http\Controllers;

use App\Exports\ResearchTopicsTemplateExport;
use App\Models\ResearchTopic;
use App\Models\TopicSupervisor;
use App\Imports\ResearchTopicsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
//use Symfony\Component\HttpFoundation\BinaryFileResponse;


class SupervisorController extends Controller
{
    // app/Http/Controllers/SupervisorController.php
    public function dashboard()
    {
        $pendingTopics = auth()->user()->supervisor->researchTopics()
            ->wherePivot('status', 'pending')
            ->with('user')
            ->get();

        return view('supervisor.dashboard', compact('pendingTopics'));
    }

    public function approveTopic(ResearchTopic $topic)
    {
        auth()->user()->supervisor->researchTopics()->updateExistingPivot($topic->id, [
            'status' => 'approved',
            'feedback' => request('feedback')
        ]);

        // Log approval - using correct stage value
        $topic->approvals()->create([
            'stage' => 'supervisor_approval',  // Changed from 'supervisor_review'
            'status' => 'approved',
            'action_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Topic approved successfully');
    }

    public function rejectTopic(ResearchTopic $topic)
    {
        auth()->user()->supervisor->researchTopics()->updateExistingPivot($topic->id, [
            'status' => 'rejected',
            'feedback' => request('feedback')
        ]);

        // Log rejection
        $topic->approvals()->create([
            'stage' => 'supervisor_review',
            'status' => 'rejected',
            'action_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Topic rejected successfully');
    }



    public function showImportForm()
    {
        return view('supervisor.import');
    }



    public function downloadTemplate(): BinaryFileResponse
    {
        $templateName = 'research_topics_template.xlsx';
        $templateDir = 'templates';
        $relativePath = $templateDir.'/'.$templateName;

        // 1. Ensure directory exists
        if (!Storage::exists($templateDir)) {
            try {
                Storage::makeDirectory($templateDir);
            } catch (\Exception $e) {
                \Log::error('Directory creation failed: '.$e->getMessage());
                abort(500, 'Could not create templates directory');
            }
        }

        // 2. Generate file if it doesn't exist
        if (!Storage::exists($relativePath)) {
            $success = false;
            $attempts = 0;

            while (!$success && $attempts < 3) {
                try {
                    \Log::info("Attempting template generation (Attempt ".($attempts+1).")");

                    // Try different export methods
                    Excel::store(new ResearchTopicsTemplateExport(), $relativePath);

                    if (Storage::exists($relativePath)) {
                        $success = true;
                        break;
                    }

                    $attempts++;
                } catch (\Exception $e) {
                    \Log::error("Generation attempt $attempts failed: ".$e->getMessage());
                    $attempts++;
                    sleep(1);
                }
            }

            if (!$success) {
                \Log::error('All template generation attempts failed');
                abort(500, 'Failed to generate template after multiple attempts');
            }
        }

        // 3. Verify file exists in storage
        if (!Storage::exists($relativePath)) {
            \Log::error('Template file verification failed');
            abort(500, 'Template file does not exist after generation');
        }

        // 4. Return download response using Storage facade
        $localPath = Storage::path($relativePath); // Gets absolute filesystem path
        return response()->download($localPath, $templateName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    public function processImport(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);
//        try {
//            Excel::import(new ResearchTopicsImport(), $request->file('file'));
//
//            return redirect()
//                ->back()
//                ->with('success', 'Research topics imported successfully!');
//        } catch (\Exception $e) {
//            return back()->with('error', 'Error importing file: ' . $e->getMessage());
//        }



        try {
            Excel::import(new ResearchTopicsImport(), $request->file('file'));

            return redirect()
                ->back()
                ->with('success', 'Research topics imported successfully!');

        } catch (ValidationException $e) {
            // Properly handle Excel validation errors
            $failures = $e->failures();

            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values()
                ];
            }

            return redirect()
                ->back()
                ->with('import_errors', $errorMessages)
                ->withInput();

        } catch (\Exception $e) {
            // Handle all other exceptions
            return redirect()
                ->back()
                ->with('error', 'Error importing file: ' . $e->getMessage())
                ->withInput();
        }

    }

}
