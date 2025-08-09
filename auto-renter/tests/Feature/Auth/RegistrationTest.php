<?php

use App\Livewire\Auth\Register;
use Livewire\Livewire;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('owners can register and are redirected to the dashboard', function () {
    $response = Livewire::test(Register::class)
        ->set('name', 'Owner User')
        ->set('email', 'owner@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('phone', '123456789')
        ->set('role', 'owner')
        ->call('register');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticated();
    });

test('customers can register and are redirected home', function () {
    $response = Livewire::test(Register::class)
        ->set('name', 'Customer User')
        ->set('email', 'customer@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('phone', '123456789')
        ->set('role', 'customer')
        ->call('register');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('home', absolute: false));
        
    $this->assertAuthenticated();
});