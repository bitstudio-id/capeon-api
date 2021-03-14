<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class RegisterToken extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 'register_token';
    protected $primaryKey = 'register_token_id';

    const CREATED_AT      = 'register_token_created_at';
    const UPDATED_AT      = 'register_token_updated_at';
    const DELETED_AT      = 'register_token_deleted_at';
}
