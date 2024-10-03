<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * App\Models\Project
 *
 * @mixin IdeHelperProject
 *
 * @property int $project_id
 * @property string $project_name
 * @property string $project_sysname
 * @property string $project_title
 * @property string|null $project_desc
 * @property bool|null $to_cd
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectSysname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereToCd($value)
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 *
 * @property-read Collection $required_update_server_ids
 * @property-read string|null $required_update_server_names
 * @property-read Collection $server_ids
 * @property-read string|null $server_names
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Server> $servers
 * @property-read int|null $servers_count
 *
 * @method static \Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static Builder|Project filter(\App\Filters\QueryFilter $filters)
 *
 * @mixin \Eloquent
 */
class Project extends Model
{
    use BroadcastsEvents, HasFactory;

    /** @var string */
    protected $table = 'dbo.project';

    /** @var string */
    protected $primaryKey = 'project_id';

    protected $appends = [
        'server_names',
        'server_ids',
        'required_update_server_names',
        'required_update_server_ids',
    ];

    protected $casts = [
        'project_id' => 'int',
        'project_name' => 'string',
        'project_sysname' => 'string',
        'project_title' => 'string',
        'project_desc' => 'string',
        'to_cd' => 'boolean',
    ];

    protected $fillable = [
        'project_id',
        'project_name',
        'project_sysname',
        'project_title',
        'project_desc',
        'to_cd',
    ];

    /**
     * @return PrivateChannel[]
     */
    public function broadcastOn($event): array
    {
        return [new PrivateChannel('Project')];
    }

    public function servers(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Server::class,
            ProjectServer::class,
            'project',
            'server_id', 'project_id', 'server')
            ->select('server_id', 'server_name', 'required_update')
            ->withCasts(['required_update' => 'boolean']);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    public function server_collection(): \Illuminate\Support\Collection
    {
        return $this->servers()->pluck('server_name') ?? collect();
    }

    public function getServerNamesAttribute(): ?string
    {
        return $this->server_collection()->implode("\n");
    }

    public function getServerIdsAttribute(): Collection
    {
        return $this->server_collection()->pluck('server_id');
    }

    public function required_servers(): ?Collection
    {
        return $this->server_collection()->where('required_update', 'true');
    }

    public function getRequiredUpdateServerNamesAttribute(): ?string
    {
        return $this->required_servers()->pluck('server_name')->implode("\n");
    }

    public function getRequiredUpdateServerIdsAttribute(): Collection
    {
        return $this->required_servers()->pluck('server_ids');
    }
}
