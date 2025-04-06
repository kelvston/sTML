<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $schools = [
            'SACEM' => [
                'name' => 'School of Architecture Construction Economics and Management',
                'departments' => [
                    'Architecture',
                    'Construction Economics and Management',
                    'Real Estate Management',
                    'Project Management'
                ]
            ],
            'SERBI' => [
                'name' => 'School of Earth Sciences, Real Estate, Business and Informatics',
                'departments' => [
                    'Earth Sciences',
                    'Real Estate',
                    'Business Administration', // Make sure this exists
                    'Information Technology',
                    'Business Informatics'
                ]
            ],
            'SEES' => [
                'name' => 'School of Engineering and Environmental Studies',
                'departments' => [
                    'Civil Engineering',
                    'Mechanical Engineering',
                    'Electrical Engineering',
                    'Environmental Engineering',
                    'Computer Engineering'
                ]
            ],
            'SSPSS' => [
                'name' => 'School of Spatial Planning and Social Sciences',
                'departments' => [
                    'Urban and Regional Planning',
                    'Community Development',
                    'Social Sciences',
                    'Development Studies',
                    'Public Administration'
                ]
            ]
        ];

        return view('profile.edit', compact('schools'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.Auth::id(),
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school' => 'nullable|string',
            'department' => 'nullable|string',
//            'registration_number' => 'nullable|string'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->school = $request->school;
        $user->department = $request->department;
        $user->registration_number = $request->registration_number;

        if ($request->hasFile('profile_picture')) {
            // Delete old file
            if ($user->profile_picture) {
                Storage::delete('profile-pictures/'.$user->profile_picture);
            }

            // Store new file
            $file = $request->file('profile_picture');
            $fileName = time().'_'.Str::slug($file->getClientOriginalName());
            $path = $file->storeAs('profile-pictures', $fileName, 'public');

            $user->profile_picture = $fileName;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
