<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'update.package_type';

    /** @var string */
    protected $primaryKey = 'package_type_id';
}
