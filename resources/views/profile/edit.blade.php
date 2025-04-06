
@extends('layouts.dashboard')

@section('content')
    <style>
        .profile-summary-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .profile-pic-lg {
            width: 150px;
            height: 150px;
            border: 3px solid #6366f1;
            background-color: #f0f9ff;
            margin: 0 auto 1.5rem;
        }

        .profile-pic-lg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .summary-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-label {
            color: #64748b;
            font-weight: 500;
        }

        .summary-value {
            color: #1e293b;
            font-weight: 600;
            max-width: 60%;
            text-align: right;
        }

        @media (max-width: 992px) {
            .profile-pic-lg {
                width: 120px;
                height: 120px;
            }

            .col-lg-8 {
                margin-bottom: 2rem;
            }
        }
    </style>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
        <div class="row g-7">
            <!-- Form Column -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0">{{ __('Update Profile') }}</h5>
                    </div>
                    <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                        <div class="col-md-6">
                            <input id="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name', Auth::user()->name) }}"
                                   required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                        <div class="col-md-6">
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email', Auth::user()->email) }}"
                                   required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="profile_picture" class="col-md-4 col-form-label text-md-end">
                            {{ __('Profile Picture') }}
                        </label>
                        <div class="col-md-6">
                            <input id="profile_picture" type="file"
                                   class="form-control @error('profile_picture') is-invalid @enderror"
                                   name="profile_picture" accept="image/*">

                            @error('profile_picture')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                    </div>


                    <!-- School Field -->
                    {{-- In your profile/edit.blade.php --}}
                    <div class="row mb-3">
                        <label for="school" class="col-md-4 col-form-label text-md-end">{{ __('School') }}</label>
                        <div class="col-md-6">
                            <select id="school" class="form-control @error('school') is-invalid @enderror" name="school">
                                <option value="">Select School</option>
                                @foreach($schools as $abbr => $school)
                                    <option value="{{ $abbr }}"
                                        {{ old('school', Auth::user()->school) == $abbr ? 'selected' : '' }}>
                                        {{ $school['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school')
                            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="department" class="col-md-4 col-form-label text-md-end">{{ __('Department') }}</label>
                        <div class="col-md-6">
                            <select id="department" class="form-control @error('department') is-invalid @enderror" name="department">
                                <option value="">Select Department</option>
                                @if(Auth::user()->school && isset($schools[Auth::user()->school]['departments']))
                                    @foreach($schools[Auth::user()->school]['departments'] as $dept)
                                        <option value="{{ $dept }}"
                                            {{ old('department', Auth::user()->department) == $dept ? 'selected' : '' }}>
                                            {{ $dept }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('department')
                            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Registration Number Field -->
                    <div class="row mb-3">
                        <label for="registration_number" class="col-md-4 col-form-label text-md-end">{{ __('Registration Number') }}</label>
                        <div class="col-md-6">
                            <input id="registration_number" type="text"
                                   class="form-control @error('registration_number') is-invalid @enderror"
                                   name="registration_number"
                                   value="{{ old('registration_number', Auth::user()->registration_number) }}">

                            @error('registration_number')
                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Update Profile') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div></div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0">{{ __('Profile Summary') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="profile-pic-lg rounded-circle overflow-hidden">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/profile-pictures/' . Auth::user()->profile_picture) }}"
                                     alt="Profile Picture">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            @endif
                        </div>
                    </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between px-0">
                        <span class="summary-label">Name:</span>
                        <span class="summary-value">{{ Auth::user()->name }}</span>
                    </div>



                   <div class="list-group-item d-flex justify-content-between px-0">
                        <span class="summary-label">Email:</span>
                        <span class="summary-value">{{ Auth::user()->email }}</span>
                    </div>

                   <div class="list-group-item d-flex justify-content-between px-0">
                        <span class="summary-label">School:</span>
                        <span class="summary-value">{{ Auth::user()->school ?? 'N/A' }}</span>
                    </div>

                   <div class="list-group-item d-flex justify-content-between px-0">
                        <span class="summary-label">Department:</span>
                        <span class="summary-value">{{ Auth::user()->department ?? 'N/A' }}</span>
                    </div>

                   <div class="list-group-item d-flex justify-content-between px-0">
                        <span class="summary-label">Registration Number:</span>
                        <span class="summary-value">{{ Auth::user()->registration_number ?? 'N/A' }}</span>
                    </div>

              </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelect = document.getElementById('school');
            const departmentSelect = document.getElementById('department');
            const schools = @json($schools);

            // Function to update departments
            function updateDepartments() {
                const selectedSchool = schoolSelect.value;
                departmentSelect.innerHTML = '<option value="">Select Department</option>';

                if(selectedSchool && schools[selectedSchool]) {
                    schools[selectedSchool].departments.forEach(dept => {
                        const option = new Option(dept, dept);
                        departmentSelect.add(option);
                    });

                    // Set existing department value
                    const currentDept = "{{ old('department', Auth::user()->department) }}";
                    if(currentDept) {
                        departmentSelect.value = currentDept;
                    }
                }
            }

            // Initial update if school is preselected
            if(schoolSelect.value) {
                updateDepartments();
            }

            // Update on school change
            schoolSelect.addEventListener('change', updateDepartments);
        });
    </script>
@endsection
