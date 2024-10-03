<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProjectTest extends TestCase
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
        $response = $this->getJson('/api/project', $this->headers);
        $response->assertStatus(403);
    }

    public function testIndex(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project list')->pluck('id')->toArray());
        $response = $this->getJson('/api/project', $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['*' => [
            'project_id',
            'project_name',
            'project_sysname',
            'project_title',
            'project_desc',
            'to_cd',
            'created_at',
            'updated_at',
            'deleted_at',
            'server_names',
            'server_ids',
            'required_update_server_names',
            'required_update_server_ids',
            'servers',
        ]]]);
    }

    public function testIndexProjectNameFilter(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project list')->pluck('id')->toArray());
        $project = Project::factory()->create()->toArray();
        $response = $this->getJson('/api/project?project_name='.$project['project_name'], $this->headers);
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertNotEmpty($jsonData);
        $response->assertJsonStructure(['data' => ['*' => [
            'project_id',
            'project_name',
            'project_sysname',
            'project_title',
            'project_desc',
            'to_cd',
            'created_at',
            'updated_at',
            'deleted_at',
            'server_names',
            'server_ids',
            'required_update_server_names',
            'required_update_server_ids',
            'servers',
        ]]]);
        $this->assertContainsEquals($project['project_name'], array_column($jsonData, 'project_name'));
    }

    public function testIndexProjectSysnameFilter(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project list')->pluck('id')->toArray());
        $project = Project::factory()->create()->toArray();
        $response = $this->getJson('/api/project?project_sysname='.$project['project_sysname'], $this->headers);
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertNotEmpty($jsonData);
        $response->assertJsonStructure(['data' => ['*' => [
            'project_id',
            'project_name',
            'project_sysname',
            'project_title',
            'project_desc',
            'to_cd',
            'created_at',
            'updated_at',
            'deleted_at',
            'server_names',
            'server_ids',
            'required_update_server_names',
            'required_update_server_ids',
            'servers',
        ]]]);
        $this->assertContainsEquals($project['project_sysname'], array_column($jsonData, 'project_sysname'));
    }

    public function testIndexProjectTitleFilter(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project list')->pluck('id')->toArray());
        $project = Project::factory()->create()->toArray();
        $response = $this->getJson('/api/project?project_title='.$project['project_title'], $this->headers);
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertNotEmpty($jsonData);
        $response->assertJsonStructure(['data' => ['*' => [
            'project_id',
            'project_name',
            'project_sysname',
            'project_title',
            'project_desc',
            'to_cd',
            'created_at',
            'updated_at',
            'deleted_at',
            'server_names',
            'server_ids',
            'required_update_server_names',
            'required_update_server_ids',
            'servers',
        ]]]);
        $this->assertContainsEquals($project['project_title'], array_column($jsonData, 'project_title'));
    }

    public function testIndexProjectDescFilter(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project list')->pluck('id')->toArray());
        $project = Project::factory()->create()->toArray();
        $response = $this->getJson('/api/project?project_desc='.$project['project_desc'], $this->headers);
        $response->assertStatus(200);
        $jsonData = $response->json('data');
        $this->assertNotEmpty($jsonData);
        $response->assertJsonStructure(['data' => ['*' => [
            'project_id',
            'project_name',
            'project_sysname',
            'project_title',
            'project_desc',
            'to_cd',
            'created_at',
            'updated_at',
            'deleted_at',
            'server_names',
            'server_ids',
            'required_update_server_names',
            'required_update_server_ids',
            'servers',
        ]]]);
        $this->assertContainsEquals($project['project_desc'], array_column($jsonData, 'project_desc'));
    }

    public function testStoreNotAllowed(): void
    {
        $project = Project::factory()->make()->toArray();
        $response = $this->postJson('/api/project', $project, $this->headers);
        $response->assertStatus(403);
    }

    public function testStoreProjectNameNotExists(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project create')->pluck('id')->toArray());
        $project = Project::factory()->make()->toArray();
        unset($project['project_name']);
        $response = $this->postJson('/api/project', $project, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'error_list',
        ]);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Поле Название проекта обязательно для заполнения.',
            ],
        ]);
    }

    public function testStoreProjectNameNotUnique(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project create')->pluck('id')->toArray());
        $existsProject = Project::factory()->create()->toArray();
        $project = Project::factory()->make()->toArray();
        $project['project_name'] = $existsProject['project_name'];
        $response = $this->postJson('/api/project', $project, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'error_list',
        ]);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Такое значение поля Название проекта уже существует.',
            ],
        ]);
    }

    public function testStore(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project create')->pluck('id')->toArray());
        $project = Project::factory()->make()->toArray();
        $response = $this->postJson('/api/project', $project, $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'project_id',
            'project_name',
            'project_sysname',
            'project_title',
            'project_desc',
            'to_cd',
            'created_at',
            'updated_at',
            'server_names',
            'server_ids',
            'required_update_server_names',
            'required_update_server_ids',
        ]]);
        $this->assertDatabaseHas('dbo.project', ['project_name' => $project['project_name']]);
        $createdProject = $response->json('data');
        $this->assertEquals($createdProject['project_name'], $project['project_name']);
    }

    public function testShowNotAllowed(): void
    {
        $project = Project::factory()->create()->toArray();
        $response = $this->getJson("/api/project/{$project['project_id']}", $this->headers);
        $response->assertStatus(403);
    }

    public function testShow(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project show')->pluck('id')->toArray());
        $project = Project::factory()->create()->toArray();
        $response = $this->getJson("/api/project/{$project['project_id']}", $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'project_id',
            'project_name',
            'project_sysname',
            'project_title',
            'project_desc',
            'to_cd',
            'created_at',
            'updated_at',
            'server_names',
            'server_ids',
            'required_update_server_names',
            'required_update_server_ids',
            'servers',
        ]]);
        $createdProject = $response->json('data');
        $this->assertEquals($createdProject['project_name'], $project['project_name']);
    }

    public function testShowNotExists(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project show')->pluck('id')->toArray());
        $project = Project::factory()->create();
        $projectData = $project->toArray();
        $project->delete();
        $response = $this->getJson("/api/project/{$projectData['project_id']}", $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'metadata']);
        $this->assertNull($response->json('data'));
    }

    public function testUpdateNotAllowed(): void
    {
        $project = Project::factory()->create()->toArray();
        $response = $this->putJson("/api/project/{$project['project_id']}", $project, $this->headers);
        $response->assertStatus(403);
    }

    public function testUpdateNotFound(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project edit')->pluck('id')->toArray());
        $project = Project::factory()->create();
        $projectData = $project->toArray();
        $project->delete();
        $response = $this->putJson("/api/project/{$projectData['project_id']}", $projectData, $this->headers);
        $response->assertNotFound();
    }

    public function testUpdateProjectNameNotUnique(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project edit')->pluck('id')->toArray());
        $project = Project::factory()->create()->toArray();
        $newProject = Project::factory()->create()->toArray();
        $project['project_name'] = $newProject['project_name'];
        $response = $this->putJson("/api/project/{$project['project_id']}", $project, $this->headers);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'error_list']);
        $response->assertJson([
            'message' => 'Произошла ошибка валидации',
            'error_list' => [
                'Такое значение поля Название проекта уже существует.',
            ],
        ]);
    }

    public function testUpdate(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project edit')->pluck('id')->toArray());
        $project = Project::factory()->create()->toArray();
        $project['project_name'] = $this->faker()->word();
        $response = $this->putJson("/api/project/{$project['project_id']}", $project, $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'project_id',
            'project_name',
            'project_sysname',
            'project_title',
            'project_desc',
            'to_cd',
            'created_at',
            'updated_at',
            'server_names',
            'server_ids',
            'required_update_server_names',
            'required_update_server_ids',
            'servers',
        ]]);
        $data = $response->json('data');
        $this->assertEquals($data['project_name'], $project['project_name']);
    }

    public function testDeleteNotAllowed(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project delete')->pluck('id')->toArray());
        $project = Project::factory()->create();
        $projectData = $project->toArray();
        $project->delete();
        $response = $this->deleteJson("/api/project/{$projectData['project_id']}", $projectData, $this->headers);
        $response->assertNotFound();
    }

    public function testDelete(): void
    {
        $this->user->syncPermissions(Permission::where('name', 'project delete')->pluck('id')->toArray());
        $project = Project::factory()->create()->toArray();
        $response = $this->deleteJson("/api/project/{$project['project_id']}", $project, $this->headers);
        $response->assertOk();
    }
}
