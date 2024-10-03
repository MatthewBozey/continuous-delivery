<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ScriptType
 *
 * @property int $script_type_id
 * @property string|null $script_type_name
 * @property string|null $script_type_title
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ScriptType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScriptType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScriptType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScriptType whereScriptTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScriptType whereScriptTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScriptType whereScriptTypeTitle($value)
 *
 * @mixin IdeHelperScriptType
 * @mixin \Eloquent
 */
class ScriptType extends Model
{
    use HasFactory;

    protected $table = 'update.script_type';

    protected $primaryKey = 'script_type_id';

    protected $casts = [
        'script_type_id' => 'int',
    ];
}
