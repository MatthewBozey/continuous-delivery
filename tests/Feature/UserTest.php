<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Spatie\Permission\Models\Permission;
use Str;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected array $headers;

    protected mixed $originalQueueDriver;

    //    protected array $connectionsToTransact = ['KRON_CI_TEST'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->generateToken();
        $this->originalQueueDriver = config('queue.default');
    }

    protected function tearDown(): void
    {
        Queue::setDefaultDriver($this->originalQueueDriver);
        parent::tearDown();
    }

    public function testIndexNotAllowed(): void
    {
        $response = $this->getJson('/api/users', $this->headers);
        $response->assertStatus(403);
    }

    public function testIndex(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users list')->pluck('id')->toArray());
        $response = $this->getJson('/api/users', $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [[
            'user_id',
            'username',
            'email',
            'last_name',
            'first_name',
            'patronymic',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]]]);
    }

    public function testStoreNotAllowed(): void
    {
        $userData = User::factory()->make()->toArray();
        $response = $this->postJson('/api/users', $userData, $this->headers);
        $response->assertStatus(403);
    }

    public function testStoreInvalidPasswordsParams(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users create')->pluck('id')->toArray());
        $userData = User::factory()->make()->toArray();
        $response = $this->postJson('/api/users', $userData, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'error_list',
        ]);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Поле Пароль обязательно для заполнения.',
                'Поле Подтверждение пароля обязательно для заполнения.',
            ],
        ]);
    }

    public function testStoreInvalidPasswordConfirmationParam(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users create')->pluck('id')->toArray());
        $userData = User::factory()->make()->toArray();
        $userData['password'] = Str::password();
        $response = $this->postJson('/api/users', $userData, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'error_list',
        ]);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Поле Пароль не совпадает с подтверждением.',
                'Поле Подтверждение пароля обязательно для заполнения.',
            ],
        ]);
    }

    public function testStore(): void
    {
        $userData = User::factory()->make()->toArray();
        $userData['password'] = Str::password();
        $userData['password_confirmation'] = $userData['password'];
        $this->user->syncPermissions(Permission::where('name', 'users create')->pluck('id')->toArray());
        $response = $this->postJson('/api/users', $userData, $this->headers);
        $response->assertCreated();
        $response->assertJsonStructure(['data' => [
            'user_id',
            'username',
            'email',
            'last_name',
            'first_name',
            'patronymic',
        ]]);
        $createdUser = $response->json('data');
        $this->assertEquals($userData['username'], $createdUser['username']);
        $this->assertEquals($userData['email'], $createdUser['email']);
        $this->assertDatabaseHas('system.user', [
            'username' => $createdUser['username'],
            'email' => $createdUser['email'],
        ]);
    }

    public function testShowNotAllowed(): void
    {
        $userData = User::factory()->create()->toArray();
        $response = $this->getJson("/api/users/{$userData['user_id']}", $this->headers);
        $response->assertStatus(403);
    }

    public function testShow(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users show')->pluck('id')->toArray());
        $userData = User::factory()->create()->toArray();
        $response = $this->getJson("/api/users/{$userData['user_id']}", $this->headers);
        $response->assertOk();
        $response->assertJsonStructure(['data' => [
            'user_id',
            'username',
            'email',
            'last_name',
            'first_name',
            'patronymic',
        ]]);
        $createdUser = $response->json('data');
        $this->assertEquals($userData['username'], $createdUser['username']);
        $this->assertEquals($userData['email'], $createdUser['email']);
    }

    public function testUpdateNotAllowed(): void
    {
        $userData = User::factory()->create()->toArray();
        $response = $this->putJson("/api/users/{$userData['user_id']}", [], $this->headers);
        $response->assertStatus(403);
    }

    public function testUpdatePasswordConfirmationNotExists(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users edit')->pluck('id')->toArray());
        $userData = User::factory()->create()->toArray();
        $userData['password'] = Str::password();
        $response = $this->putJson("/api/users/{$userData['user_id']}", $userData, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'error_list',
        ]);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Поле Пароль не совпадает с подтверждением.',
            ],
        ]);
    }

    public function testUpdateChangePassword(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users edit')->pluck('id')->toArray());
        $userData = User::factory()->create()->toArray();
        $userData['password'] = Str::password();
        $userData['password_confirmation'] = $userData['password'];
        $response = $this->putJson("/api/users/{$userData['user_id']}", $userData, $this->headers);
        $response->assertStatus(200);
    }

    public function testUpdateChangeNotValidPassword(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users edit')->pluck('id')->toArray());
        $userData = User::factory()->create()->toArray();
        $userData['password'] = Str::password(16, true, false, false);
        $userData['password_confirmation'] = $userData['password'];
        $response = $this->putJson("/api/users/{$userData['user_id']}", $userData, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'error_list',
        ]);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Пароль должен содержать хотя бы один специальный символ (например, !@#$%^&*).',
                'Пароль должен содержать хотя бы одну цифру.',
            ],
        ]);
    }

    public function testUpdateUser(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users edit')->pluck('id')->toArray());
        $userData = User::factory()->create()->toArray();
        $userData['username'] = $this->faker()->userName();
        $userData['email'] = $this->faker()->email();
        $response = $this->putJson("/api/users/{$userData['user_id']}", $userData, $this->headers);
        $response->assertOk();
        $response->assertJsonStructure(['data' => [
            'user_id',
            'username',
            'email',
            'last_name',
            'first_name',
            'patronymic',
        ]]);
        $createdUser = $response->json('data');
        $this->assertEquals($userData['username'], $createdUser['username']);
        $this->assertEquals($userData['email'], $createdUser['email']);
    }

    public function testDeleteNotAllowed(): void
    {
        $userData = User::factory()->create()->toArray();
        $response = $this->deleteJson("/api/users/{$userData['user_id']}", $userData, $this->headers);
        $response->assertStatus(403);
    }

    public function testDeleteWithUserNotFound(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users delete')->pluck('id')->toArray());
        $userData = User::factory()->create()->toArray();
        $response = $this->deleteJson("/api/users/-{$userData['user_id']}", $userData, $this->headers);
        $response->assertStatus(404);
    }

    public function testDelete(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'users delete')->pluck('id')->toArray());
        $userData = User::factory()->create()->toArray();
        $response = $this->deleteJson("/api/users/{$userData['user_id']}", $userData, $this->headers);
        $response->assertNoContent();
        $this->assertDatabaseMissing('system.user', $userData);
    }

    private function generateToken(): void
    {
        $token = JWTAuth::fromUser($this->user);
        $this->headers = ['Authorization' => "Bearer $token"];
    }
}
