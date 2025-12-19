<?php

use App\Models\User;

test('user with can login with valid username and password credentials with api',function(){
    $user = User::factory()->create([
        'email' => 'test@gmail.com',
        'password' => bcrypt('1234')
    ]);

    $response = $this->postJson('/api/login',[
        'email' => 'test@gmail.com',
        'password' => '1234'
    ]);

    $response->assertStatus(200);
});
