<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Token extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 'token';
    protected $primaryKey = 'token_id';

    const CREATED_AT      = 'token_created_at';
    const UPDATED_AT      = 'token_updated_at';
    const DELETED_AT      = 'token_deleted_at';
}
