<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppKey extends Model
{
    use SoftDeletes;
    protected $table      = 'm_app_key';
    protected $primaryKey = 'app_key_id';

    const CREATED_AT      = 'app_key_created_at';
    const UPDATED_AT      = 'app_key_updated_at';
    const DELETED_AT      = 'app_key_deleted_at';
}
