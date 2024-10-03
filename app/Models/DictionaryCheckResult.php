<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DictionaryCheckResult extends Model
{
    protected $table = 'update.dictionary_check_results';

    protected $primaryKey = 'id';

    protected $fillable = [
        'update_script',
        'server',
        'author',
        'check_result',
    ];

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    /**
     * @return PrivateChannel[]
     */
    public function broadcastOn($event): array
    {
        return [new PrivateChannel('DictionaryCheckResult')];
    }
}
