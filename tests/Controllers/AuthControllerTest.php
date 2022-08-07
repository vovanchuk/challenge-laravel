<?php

namespace Tests\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testRegisterReturnsErrorsForInvalidData()
    {
        $this->json('POST', 'api/auth/register')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'name', 'password'])
        ;
    }

    public function testRegisterEmailShouldBeValid()
    {
        $password = fake()->password(8);

        $data = [
            'email' => 'sadsdfsdfsdf',
            'name' => fake()->name(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->json('POST', 'api/auth/register', $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email'])
        ;
    }

    public function testRegisterPasswordShouldHaveAtLeast8Chars()
    {
        $password = fake()->password(1,7);

        $data = [
            'email' => fake()->email(),
            'name' => fake()->name(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->json('POST', 'api/auth/register', $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password'])
        ;
    }

    public function testRegisterPasswordShouldBeConfirmed()
    {
        $password = fake()->password(8);

        $data = [
            'email' => fake()->email(),
            'name' => fake()->name(),
            'password' => $password,
        ];

        $this->json('POST', 'api/auth/register', $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password'])
        ;
    }

    public function testRegisterSuccess()
    {
        $password = fake()->password(8);

        $data = [
            'email' => fake()->email(),
            'name' => fake()->name(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->json('POST', 'api/auth/register', $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['user', 'token'])
        ;
    }

    public function testLoginShouldReturnUnauthorized()
    {
        $data = [
            'email' => fake()->email(),
            'password' => fake()->password()
        ];

        $this->json('POST', 'api/auth/login', $data)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testLoginSuccess()
    {
        $password = 'TestP@$$w0Rd';

        $user = User::factory()->create([
            'password' => bcrypt($password)
        ]);

        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => $password
        ])->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['token']);
    }
}
