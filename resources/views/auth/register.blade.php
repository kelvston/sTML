@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="role" class="col-md-4 col-form-label text-md-end">Role</label>
                                <div class="col-md-6">
                                    <select id="role" class="form-control" name="role" required onchange="toggleSupervisorFields()">
                                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div id="supervisorFields" style="display: none;">
                                <div class="row mb-3">
                                    <label for="expertise" class="col-md-4 col-form-label text-md-end">Department</label>
                                    <div class="col-md-6">
                                        <select class="form-control @error('expertise') is-invalid @enderror" name="expertise" id="expertise">
                                            <option value="">Select Department</option>
                                            <option value="Computer Science" {{ old('expertise') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                            <option value="Information Technology" {{ old('expertise') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                            <option value="Biotechnology" {{ old('expertise') == 'Biotechnology' ? 'selected' : '' }}>Biotechnology</option>
                                            <option value="Environmental Science" {{ old('expertise') == 'Environmental Science' ? 'selected' : '' }}>Environmental Science</option>
                                            <option value="Civil Engineering" {{ old('expertise') == 'Civil Engineering' ? 'selected' : '' }}>Civil Engineering</option>
                                            <option value="Mechanical Engineering" {{ old('expertise') == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                                            <option value="Electrical Engineering" {{ old('expertise') == 'Electrical Engineering' ? 'selected' : '' }}>Electrical Engineering</option>
                                            <option value="Business Administration" {{ old('expertise') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                                        </select>
                                        @error('expertise')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="max_students" class="col-md-4 col-form-label text-md-end">Max Students</label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control @error('max_students') is-invalid @enderror" name="max_students" id="max_students" min="1" max="20" value="{{ old('max_students', 5) }}">
                                        @error('max_students')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSupervisorFields() {
            var role = document.getElementById("role").value;
            var supervisorFields = document.getElementById("supervisorFields");
            supervisorFields.style.display = (role === "supervisor") ? "block" : "none";
        }

        // Ensure the correct fields are visible on page load
        document.addEventListener("DOMContentLoaded", function () {
            toggleSupervisorFields();
        });
    </script>

@endsection
