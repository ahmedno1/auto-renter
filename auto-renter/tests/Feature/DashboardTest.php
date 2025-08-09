<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});
test('owners can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create(['role' => 'owner']));

    $this->get('/dashboard')->assertStatus(200);
});

test('customers are redirected from the dashboard', function () {
    $this->actingAs($user = User::factory()->create(['role' => 'customer']));

    $this->get('/dashboard')->assertRedirect(route('home'));
        
});