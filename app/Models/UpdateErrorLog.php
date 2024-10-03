<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UpdateErrorLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateErrorLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateErrorLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateErrorLog query()
 *
 * @property int $update_error_log_id ИД
 * @property string $update_script_id ИД скрипта
 * @property string $update_error_log_date
 * @property string $update_error_log_text Текст ошибки
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateErrorLog whereUpdateErrorLogDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateErrorLog whereUpdateErrorLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateErrorLog whereUpdateErrorLogText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UpdateErrorLog whereUpdateScriptId($value)
 *
 * @mixin \Eloquent
 */
class UpdateErrorLog extends Model
{
    use HasFactory;

    protected $table = 'update.update_error_log';

    protected $primaryKey = 'update_error_log_id';
}
