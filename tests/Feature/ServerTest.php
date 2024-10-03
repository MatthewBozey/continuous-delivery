<?php

namespace Tests\Feature;

use App\Models\Server;
use App\Models\User;
use Database\Seeders\Server\AddServerTrashRestorePermission;
use Database\Seeders\Server\ServerTrashPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use JetBrains\PhpStorm\NoReturn;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ServerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected array $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $token = JWTAuth::fromUser($this->user);
        $this->headers = ['Authorization' => "Bearer {$token}"];
    }

    public function testIndexNotAllowed(): void
    {
        $response = $this->getJson('/api/server', $this->headers);
        $response->assertStatus(403);
    }

    public function testIndex(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server list')->pluck('id')->toArray());
        $response = $this->getJson('/api/server', $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['*' => [
            'server_id',
            'server_name',
            'disabled',
            'production_server',
            'update_required',
            'created_at',
            'updated_at',
            'deleted_at',
        ]]]);
    }

    public function testIndexFullData(): void
    {
        $this->user->syncPermissions(Permission::whereIn('name', ['server list', 'server full-list'])->pluck('id')->toArray());
        $response = $this->getJson('/api/server', $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['*' => [
            'server_id',
            'server_name',
            'database_name',
            'database_user',
            'database_password',
            'ip_address',
            'port',
            'disabled',
            'production_server',
            'update_required',
            'deleted_at',
            'created_at',
            'updated_at',
        ]]]);
    }

    public function testIndexServerNameFilter(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server list')->pluck('id')->toArray());
        $server = Server::factory()->create()->toArray();
        $response = $this->getJson('/api/server?server_name='.$server['server_name'], $this->headers);
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertNotEmpty($jsonData);
        $response->assertJsonStructure(['data' => [[
            'server_id',
            'server_name',
            'disabled',
            'production_server',
            'update_required',
            'deleted_at',
            'created_at',
            'updated_at',
        ]]]);
        $this->assertContainsEquals($server['server_name'], array_column($jsonData, 'server_name'));
    }

    #[NoReturn]
    public function testIndexDatabaseNameFilterWithLowData(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server list')->pluck('id')->toArray());
        $server = Server::factory()->create()->makeVisible(['database_name'])->toArray();
        $response = $this->getJson('/api/server?database_name='.$server['database_name'], $this->headers);
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertNotEmpty($jsonData);
        $response->assertJsonStructure(['data' => ['*' => [
            'server_id',
            'server_name',
            'disabled',
            'production_server',
            'update_required',
            'deleted_at',
            'created_at',
            'updated_at',
        ]]]);
    }

    #[NoReturn]
    public function testIndexDatabaseNameFilterWithFullData(): void
    {
        $this->user->syncPermissions(Permission::whereIn('name', ['server list', 'server full-list'])->pluck('id')->toArray());
        $server = Server::factory()->create()->makeVisible(['database_name'])->toArray();
        $response = $this->getJson('/api/server?database_name='.$server['database_name'], $this->headers);
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertNotEmpty($jsonData);
        $response->assertJsonStructure(['data' => ['*' => [
            'server_id',
            'server_name',
            'database_name',
            'database_user',
            'database_password',
            'ip_address',
            'port',
            'disabled',
            'production_server',
            'update_required',
            'deleted_at',
            'created_at',
            'updated_at',
        ]]]);
        $this->assertCount(1, $jsonData);
        $this->assertContainsEquals($server['database_name'], array_column($jsonData, 'database_name'));
    }

    public function testStoreNotAllowed(): void
    {
        $server = Server::factory()->make()->toArray();
        $response = $this->postJson('/api/server', $server, $this->headers);
        $response->assertStatus(403);
    }

    public function testStoreProjectNameNotExists(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server create')->pluck('id')->toArray());
        $server = Server::factory()->make()->makeVisible([
            'database_user',
            'port',
            'ip_address',
            'database_password',
            'database_name',
        ])->toArray();
        unset($server['server_name']);
        $response = $this->postJson('/api/server', $server, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'error_list',
        ]);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Поле Название сервера обязательно для заполнения.',
            ],
        ]);
    }

    public function testStore(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server create')->pluck('id')->toArray());
        $server = Server::factory()->make()->makeVisible([
            'database_user',
            'port',
            'ip_address',
            'database_password',
            'database_name',
        ])->toArray();
        $response = $this->postJson('/api/server', $server, $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'server_id',
            'server_name',
            'disabled',
            'update_required',
            'created_at',
            'updated_at',
        ]]);

        $this->assertDatabaseHas('production.server', ['server_name' => $server['server_name']]);
        $createdProject = $response->json('data');
        $this->assertEquals($createdProject['server_name'], $server['server_name']);
    }

    public function testShowNotAllowed(): void
    {
        $server = Server::factory()->create()->toArray();
        $response = $this->getJson("/api/server/{$server['server_id']}", $this->headers);
        $response->assertStatus(403);
    }

    public function testShowWithMinData(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server show')->pluck('id')->toArray());
        $server = Server::factory()->create()->toArray();
        $response = $this->getJson("/api/server/{$server['server_id']}", $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'server_id',
            'server_name',
            'production_server',
            'update_required',
            'deleted_at',
            'created_at',
            'updated_at',
        ]]);
        $createdServer = $response->json('data');
        $this->assertEquals($createdServer['server_name'], $server['server_name']);
    }

    public function testShowWithFullData(): void
    {
        $this->user->syncPermissions(Permission::whereIn('name', ['server show', 'server full-list'])->pluck('id')->toArray());
        $server = Server::factory()->create()->toArray();
        $response = $this->getJson("/api/server/{$server['server_id']}", $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'server_id',
            'server_name',
            'database_name',
            'database_user',
            'database_password',
            'ip_address',
            'port',
            'disabled',
            'production_server',
            'update_required',
            'deleted_at',
            'created_at',
            'updated_at',
        ]]);
        $createdServer = $response->json('data');
        $this->assertEquals($createdServer['server_name'], $server['server_name']);
    }

    public function testShowNotExists(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server show')->pluck('id')->toArray());
        $server = Server::factory()->create();
        $serverData = $server->toArray();
        $server->delete();
        $response = $this->getJson("/api/server/{$serverData['server_id']}", $this->headers);
        $response->assertNotFound();
    }

    public function testUpdateNotAllowed(): void
    {
        $server = Server::factory()->create()->toArray();
        $response = $this->putJson("/api/server/{$server['server_id']}", $server, $this->headers);
        $response->assertStatus(403);
    }

    public function testUpdateNotFound(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server edit')->pluck('id')->toArray());
        $server = Server::factory()->create();
        $serverData = $server->toArray();
        $server->delete();
        $response = $this->putJson("/api/server/{$serverData['server_id']}", $serverData, $this->headers);
        $response->assertNotFound();
    }

    public function testServerNameNotUnique(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server edit')->pluck('id')->toArray());
        $server = Server::factory()->create()->toArray();
        $newServer = Server::factory()->create()->toArray();
        $server['server_name'] = $newServer['server_name'];
        $response = $this->putJson("/api/server/{$server['server_id']}", $server, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'error_list']);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Такое значение поля Название сервера уже существует.',
            ],
        ]);
    }

    public function testUpdateNotModified(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server edit')->pluck('id')->toArray());
        $server = Server::factory()->create()->toArray();
        $response = $this->putJson("/api/server/{$server['server_id']}", $server, $this->headers);
        $response->assertNotModified();
    }

    public function testUpdate(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server edit')->pluck('id')->toArray());
        $server = Server::factory()->create()->toArray();
        $server['server_name'] = $this->faker()->word();
        $response = $this->putJson("/api/server/{$server['server_id']}", $server, $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'server_id',
            'server_name',
            'disabled',
            'production_server',
            'update_required',
            'deleted_at',
            'created_at',
            'updated_at',
        ]]);
        $data = $response->json('data');
        $this->assertEquals($data['server_name'], $server['server_name']);
    }

    public function testUpdateFullData(): void
    {
        $this->user->syncPermissions(Permission::whereIn('name', ['server edit', 'server full-list'])->pluck('id')->toArray());
        $server = Server::factory()->create()->toArray();
        $server['server_name'] = $this->faker()->word();
        $response = $this->putJson("/api/server/{$server['server_id']}", $server, $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'server_id',
            'server_name',
            'database_name',
            'database_user',
            'database_password',
            'ip_address',
            'port',
            'disabled',
            'production_server',
            'update_required',
            'deleted_at',
            'created_at',
            'updated_at',
        ]]);
        $data = $response->json('data');
        $this->assertEquals($data['server_name'], $server['server_name']);
    }

    public function testDeleteNotAllowed(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server delete')->pluck('id')->toArray());
        $server = Server::factory()->create();
        $serverData = $server->toArray();
        $server->delete();
        $response = $this->deleteJson("/api/server/{$serverData['server_id']}", $serverData, $this->headers);
        $response->assertNotFound();
    }

    public function testDelete(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'server delete')->pluck('id')->toArray());
        $server = Server::factory()->create()->toArray();
        $response = $this->deleteJson("/api/server/{$server['server_id']}", $server, $this->headers);
        $response->assertOk();
    }

    public function testTrashNotAllowed(): void
    {
        $response = $this->getJson('/api/server/trash', $this->headers);
        $response->assertStatus(403);
    }

    public function testTrash(): void
    {
        $this->seed(ServerTrashPermissionSeeder::class);
        $this->user->syncPermissions(Permission::where('name', 'server trash-list')->pluck('id')->toArray());
        $server = Server::factory()->create();
        $server->delete();
        $response = $this->getJson('/api/server/trash', $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['*' => [
            'server_id',
            'server_name',
            'disabled',
            'production_server',
            'update_required',
            'created_at',
            'updated_at',
            'deleted_at',
        ]]]);
        $jsonData = $response->json('data');
        $this->assertNotContainsEquals(null, array_column($jsonData, 'database_name'));
    }

    public function testTrashRestoreNotAllowed(): void
    {
        $server = Server::factory()->create();
        $serverId = $server->server_id;
        $server->delete();
        $response = $this->postJson("/api/server/trash/restore/{$serverId}", compact('serverId'), $this->headers);
        $response->assertStatus(403);
    }

    public function testTrashRestore(): void
    {
        $this->seed(AddServerTrashRestorePermission::class);
        $this->user->syncPermissions(Permission::where('name', 'server trash-restore')->pluck('id')->toArray());
        $server = Server::factory()->create();
        $serverId = $server->server_id;
        $server->delete();
        $response = $this->postJson("/api/server/trash/restore/{$serverId}", [], $this->headers);
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNull($data['deleted_at']);
    }

    public function testTrashForceDeleteNotAllowed(): void
    {
        $server = Server::factory()->create();
        $serverId = $server->server_id;
        $server->delete();
        $response = $this->deleteJson("/api/server/trash/{$serverId}", compact('serverId'), $this->headers);
        $response->assertStatus(403);
    }
}
