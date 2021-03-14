<?php
namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Bank extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 'm_bank';
    protected $primaryKey = 'bank_id';

    const CREATED_AT      = 'bank_created_at';
    const UPDATED_AT      = 'bank_updated_at';
    const DELETED_AT      = 'bank_deleted_at';
}
