<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Carbon\Carbon;
use Emotality\LaravelColor\Color;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * App\Models\UpdatePackage
 *
 * @property int $update_package_id
 * @property bool|null $has_errors
 * @property \Illuminate\Support\Carbon|null $package_create_date
 * @property \Illuminate\Support\Carbon|null $package_done_date
 * @property \Illuminate\Support\Carbon|null $package_plan_date
 * @property int $package_type_id
 * @property int|null $project_id
 * @property bool|null $verified
 * @property int|null $version
 * @property-read \App\Models\PackageType|null $package_type
 * @property-read \App\Models\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UpdateScript[] $update_script
 * @property-read int|null $update_script_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereHasErrors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage wherePackageCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage wherePackageDoneDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage wherePackagePlanDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage wherePackageTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereUpdatePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereVersion($value)
 *
 * @mixin IdeHelperUpdatePackage
 *
 * @property string|null $update_package_name
 * @property string|null $author_name Название пакета
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereUpdatePackageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdatePackage whereUpdatedAt($value)
 * @method static filter(\App\Filters\UpdatePackageFilter $filter)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductionProjectLogScript> $productionProjectLogScript
 * @property-read int|null $production_project_log_script_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectLogPackage> $project_log_package
 * @property-read int|null $project_log_package_count
 * @property-read array $all_servers
 * @property-read mixed $color
 * @property-read array $done_servers
 * @property-read array $percent_servers
 * @property-read Collection $servers
 *
 * @mixin \Eloquent
 */
class UpdatePackage extends Model
{
    use BroadcastsEvents;

    const SuccessStatusId = 4;

    /** @var string */
    protected $table = 'update.update_package';

    /** @var string */
    protected $primaryKey = 'update_package_id';

    public function update_script(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UpdateScript::class, 'update_package_id', 'update_package_id');
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Project::class, 'project_id', 'project_id');
    }

    public function package_type(): HasOne
    {
        return $this->hasOne(PackageType::class, 'package_type_id', 'package_type_id');
    }

    public function productionProjectLogScript(): HasMany
    {
        return $this->hasMany(ProductionProjectLogScript::class, 'update_package', 'update_package_id');
    }

    /** @var string[] */
    protected $with = [
        'project', 'package_type', 'project_log_package', 'project_log_package.project_log_server.server',
    ];

    /** @var string[] */
    protected $casts = [
        'update_package_id' => 'int', 'package_type_id' => 'int', 'project_id' => 'int', 'verified' => 'bool',
        'has_errors' => 'bool', 'version' => 'int', 'package_create_date' => 'datetime:Y-m-d H:i:s',
        'package_done_date' => 'datetime:Y-m-d H:i:s', 'package_plan_date' => 'datetime:Y-m-d H:i:s',
    ];

    /** @var string[] */
    protected $dates = [
        'package_create_date', 'package_done_date', 'package_plan_date',
    ];

    /** @var string[] */
    protected $fillable = [
        'package_create_date', 'package_done_date', 'package_type_id', 'package_plan_date', 'project_id', 'verified',
        'has_errors', 'version', 'update_package_name', 'author_name',
    ];

    public function broadcastOn($event)
    {
        return [$this, new PrivateChannel('UpdatePackage')];
    }

    public function project_log_package(): HasMany
    {
        return $this->hasMany(ProjectLogPackage::class, 'update_package', 'update_package_id');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    protected $appends = [
        'servers', 'all_servers', 'done_servers', 'color', 'percent_servers',
    ];

    public function getServersAttribute(): Collection
    {
        return \DB::table('production.project_log_script', 'pls')
            ->leftJoin('production.project_log_server as ser', 'ser.project_log_server_id', '=',
                'pls.project_log_server')
            ->leftJoin('production.server as s', 's.server_id', '=', 'ser.server_id')
            ->leftJoin('production.project_log as pl', 'pl.project_log_id', '=', 'ser.project_log_id')
            ->leftJoin('production.server_status as ss', 'ss.server_status_id', '=', 'ser.server_status_id')
            ->leftJoin('dbo.project_server as ps', 'ps.server', '=', 's.server_id')
            ->select('s.server_name', 'ser.created_at', 'ss.status_title', 'ss.status_color', 'ser.server_status_id',
                'ps.required_update', 'pl.author')
            ->where('pls.update_package', $this->update_package_id)
            ->where('ps.project', $this->project_id)
            ->get()
            ->map(function ($server) {
                $server->created_at = Carbon::parse($server->created_at)->toDateTimeString();
                $server->status_color = Color::fontColor($server->status_color, 40);

                return $server;
            });
    }

    public function getAllServersAttribute(): array
    {
        $data = \DB::table('dbo.project_server', 'ps')
            ->leftJoin('production.server as s', 's.server_id', '=', 'ps.server')
            ->select('s.server_name', 's.server_id', 'ps.required_update')
            ->where('ps.project', $this->project_id)
            ->get();

        return [
            'count' => $data->count(), 'data' => $data,
        ];
    }

    public function getDoneServersAttribute(): array
    {
        $data = \DB::table('production.project_log_script', 'sc')
            ->where('sc.update_package', $this->update_package_id)
            ->where('ps.project', $this->project_id)
            ->leftJoin('production.project_log_server as pls', 'pls.project_log_server_id', '=',
                'sc.project_log_server')
            ->leftJoin('production.server as s', 's.server_id', '=', 'pls.server_id')
            ->leftJoin('dbo.project_server as ps', 'ps.server', '=', 's.server_id')
            ->select('pls.server_id', 'sc.project_log_server', 'pls.server_status_id', 's.server_name',
                'ps.required_update')
            ->get()
            ->unique('project_log_server');

        return [
            'count' => $data->count(),
            'required_update_count' => $data->where('required_update', true)->count(),
            'data' => $data,
        ];
    }

    public function getPercentServersAttribute(): array
    {
        $doneServers = $this->done_servers['data'];
        $allServers = $this->all_servers['data'];

        return [
            'value' => $doneServers->where('required_update', true)
                ->count() > 0 ? (($doneServers->where('required_update', true)
                ->where('server_status_id', self::SuccessStatusId)
                ->unique('server_id')
                ->count() / $allServers->where('required_update', true)->count()) * 100) : 0,
        ];
    }

    public function getColorAttribute()
    {
        $color = \DB::table('update.update_package_color')
            ->whereRaw('? between min_value and max_value', [$this->percent_servers['value']])
            ->value('color');
        if ($color) {
            $rgba = Color::rgba($color);

            return "rgba({$rgba->red}, {$rgba->green}, {$rgba->blue}, 0.25)";
        } else {
            return '';
        }
    }
}
