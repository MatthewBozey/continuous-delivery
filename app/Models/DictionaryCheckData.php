<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictionaryCheckData extends Model
{
    protected $table = 'update.dictionary_check_data';

    protected $casts = [
        'update_script' => 'integer',
        'primary_key' => 'string',
        'data_fields' => 'json',
        'sql_query' => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'schema' => 'string',
        'table' => 'string',
    ];

    protected $fillable = [
        'update_script',
        'primary_key',
        'data_fields',
        'sql_query',
        'schema',
        'table',
    ];
}
