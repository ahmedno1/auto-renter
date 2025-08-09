<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';
    
    public string $phone = '';

    public string $role = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string'],
            'role' => ['required', 'in:owner,customer'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ]))));
        
        Auth::login($user);

        $destination = $user->role === 'owner'
            ? route('dashboard', absolute: false)
            : route('home', absolute: false);

        $this->redirect($destination, navigate: true);
        $this->dispatch('registrationSuccess');
        
    }

}
