<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AppKey extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 'app_key';
    protected $primaryKey = 'app_key_id';

    const CREATED_AT      = 'app_key_created_at';
    const UPDATED_AT      = 'app_key_updated_at';
    const DELETED_AT      = 'app_key_deleted_at';
}
