<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_email_is_required()
    {
        $response = $this->post('/admin/login',
        [
            'email' => '',
            'password' => 'password',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください']);
    }

    public function test_password_is_required()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => '',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください']);
    }

    public function test_wrong_login_admin()
    {
        $response = $this->post('/admin/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません']);
    }
}
