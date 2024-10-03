<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

/**
 * App\Models\PermissionSection
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property mixed $permission_section_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionSection query()
 *
 * @property string $title
 * @property string $sysname
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionSection wherePermissionSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionSection whereSysname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionSection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionSection whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PermissionSection extends Model
{
    use HasFactory;

    protected $table = 'system.permission_section';

    protected $primaryKey = 'permission_section_id';

    protected $with = ['permissions'];

    protected $fillable = ['title', 'sysname'];

    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Permission::class, 'permission_section_id', 'permission_section_id');
    }
}
