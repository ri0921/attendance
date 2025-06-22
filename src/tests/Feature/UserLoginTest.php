<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UsersTableSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_email_is_required()
    {
        $response = $this->post('/login', [
                'email' => '',
                'password' => 'password',
            ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください']);
    }

    public function test_password_is_required()
    {
        $response = $this->post('/login', [
            'email' => 'taro.y@example.com',
            'password' => '',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください']);
    }

    public function test_wrong_login_user()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません']);
    }
}
