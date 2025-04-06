<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supervisor;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validate incoming registration data.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,supervisor,admin'],
            'expertise' => ['required_if:role,supervisor', 'string', 'max:255'],
            'max_students' => ['required_if:role,supervisor', 'integer', 'min:1', 'max:20'],
        ]);
    }

    /**
     * Create a new user instance and handle supervisor registration.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // If the user is a supervisor, create supervisor details
        if ($data['role'] === 'supervisor') {
            Supervisor::create([
                'user_id' => $user->id,
                'expertise' => $data['expertise'],
                'max_students' => $data['max_students'] ?? 5,
            ]);
        }

        return $user;
    }
}
