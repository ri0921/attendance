<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'ユーザー',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_password_min()
    {
        $response = $this->post('/register', [
            'name' => 'ユーザー',
            'email' => 'test@example.com',
            'password' => 'passwor',
            'password_confirmation' => 'passwor',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_password_confirmation_does_not_match()
    {
        $response = $this->post('/register', [
            'name' => 'ユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'ユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_register_success()
    {
        $response = $this->post('/register', [
            'name' => 'ユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'ユーザー',
            'email' => 'test@example.com',
        ]);
    }
}
