<?php

test('Login a user', function () {



    $response = $this->postJson('/api/user/login', [

        'email'=> "user@ecom.com",
        'password'=>"user",
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'token'
             ]);

    $this->assertDatabaseHas('users', ['name' => 'user']);
});
