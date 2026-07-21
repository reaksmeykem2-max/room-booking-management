<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\User\BookRoom;
use App\Livewire\User\MyBookings;
use App\Livewire\User\AvailabilityBoard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\ManageBookings;
use App\Livewire\Admin\ManageRooms;
use App\Livewire\Admin\ManageHolidays;
use App\Livewire\Admin\WorkingDays;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('book-room');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // User Dashboard (redirects to book room)
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('book-room');
    })->name('dashboard');

    // Booking Pages
    Route::get('/book-room', BookRoom::class)->name('book-room');
    Route::get('/availability', AvailabilityBoard::class)->name('availability');
    Route::get('/my-bookings', MyBookings::class)->name('my-bookings');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/bookings', ManageBookings::class)->name('bookings');
    Route::get('/rooms', ManageRooms::class)->name('rooms');
    Route::get('/holidays', ManageHolidays::class)->name('holidays');
    Route::get('/working-days', WorkingDays::class)->name('working-days');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login/Register/Logout)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function () {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials, request()->boolean('remember'))) {
            request()->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    });

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function () {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'department' => 'nullable|string|max:100',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'department' => $validated['department'] ?? null,
            'role' => 'user',
        ]);

        auth()->login($user);
        return redirect('/dashboard');
    });
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');
