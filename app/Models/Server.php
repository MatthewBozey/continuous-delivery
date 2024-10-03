<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Server
 *
 * @property-read int $server_id
 * @property string|null $server_name
 * @property string|null $database_name
 * @property string|null $database_user
 * @property string|null $database_password
 * @property string|null $ip_address
 * @property string|null $port
 *
 * @mixin IdeHelperServer
 *
 * @method static orderBy(string $string)
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property bool|null $disabled
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDatabaseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDatabasePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDatabaseUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereServerName($value)
 *
 * @property int $server_version
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereServerVersion($value)
 *
 * @property bool|null $production_server Продуктивный сервер или тестовый
 * @property bool $update_required
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ServerFactory factory($count = null, $state = [])
 * @method static Builder|Server filter(\App\Filters\QueryFilter $filters)
 * @method static Builder|Server onlyTrashed()
 * @method static Builder|Server whereDeletedAt($value)
 * @method static Builder|Server whereProductionServer($value)
 * @method static Builder|Server whereUpdateRequired($value)
 * @method static Builder|Server whereUpdatedAt($value)
 * @method static Builder|Server withTrashed()
 * @method static Builder|Server withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Server extends Model
{
    use BroadcastsEvents, HasFactory, SoftDeletes;

    protected $table = 'production.server';

    protected $primaryKey = 'server_id';

    protected $hidden = [
        'database_user',
        'port',
        'ip_address',
        'database_password',
        'database_name',
    ];

    protected $fillable = [
        'server_name',
        'database_name',
        'database_user',
        'database_password',
        'ip_address',
        'port',
        'disabled',
        'update_required',
        'production_server',
    ];

    protected $casts = [
        'server_id' => 'integer',
        'server_name' => 'string',
        'database_name' => 'string',
        'database_user' => 'string',
        'database_password' => 'string',
        'ip_address' => 'string',
        'port' => 'integer',
        'disabled' => 'boolean',
        'update_required' => 'boolean',
        'production_server' => 'boolean',
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s',
    ];

    protected $dateFormat = 'Y-m-d h:i:s';

    /**
     * @return PrivateChannel[]
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('Server')];
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }
}
