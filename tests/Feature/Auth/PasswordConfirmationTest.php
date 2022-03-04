<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays confirm password screen', function () {
    $user = User::factory()->create();

    actingAs($user)->get('/confirm-password')->assertStatus(200);
});

it('confirms password with correct details', function () {
    $user = User::factory()->create();

    actingAs($user)->post('/confirm-password', ['password' => 'password'])
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

it('fails password confirmation with incorrect details', function () {
    $user = User::factory()->create();

    actingAs($user)->post('/confirm-password', ['password' => 'wrong-password'])
        ->assertSessionHasErrors();
});
