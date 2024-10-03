<?php

namespace App\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StatePlanning
 *
 * @method static find(int $id)
 * @method static findOrFail(int $id)
 * @method static orderBy(string $string)
 * @method static where(string $string, int $id)
 *
 * @property int $state_id
 * @property string $state_title
 * @property string $state_code
 * @property string|null $state_color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning query()
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereStateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereStateColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereStateTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereUpdatedAt($value)
 *
 * @property int $server_status_id
 * @property string|null $status_name
 * @property string|null $status_title
 * @property string|null $status_color
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereServerStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereStatusColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereStatusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatePlanning whereStatusTitle($value)
 *
 * @mixin \Eloquent
 */
class StatePlanning extends Model
{
    use BroadcastsEvents, HasFactory;

    protected $table = 'production.server_status';

    protected $primaryKey = 'server_status_id';

    protected $fillable = [
        'status_name',
        'status_title',
        'status_color',
    ];

    public function broadcastWith(string $event): array
    {
        return ['user_id' => 'Test'];
    }

    /**
     * @return PrivateChannel[]
     */
    public function broadcastOn($event): array
    {
        return [new PrivateChannel('StatePlanning')];
    }
}
