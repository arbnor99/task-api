<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);
        } catch (ValidationException $e) {
            Log::error('Validation exception', ['exception_message' => $e->getMessage()]);
            return response()->json(['validation_failed' => $e->getMessage()]);
        }catch (\Exception $e) {
            Log::error('Failed to create user', ['exception_message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()]);
        }
//        return response()->noContent();
        return response()->json([
            'message' => "Successfully Registered",
            'auth_user' => auth()->user()
        ]);
    }
}
