<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hash extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 'hash';
    protected $primaryKey = 'hash_id';

    const CREATED_AT      = 'hash_created_at';
    const UPDATED_AT      = 'hash_updated_at';
    const DELETED_AT      = 'hash_deleted_at';
}
