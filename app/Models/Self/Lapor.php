<?php
namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Lapor extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 't_lapor';
    protected $primaryKey = 'lapor_id';

    const CREATED_AT      = 'lapor_created_at';
    const UPDATED_AT      = 'lapor_updated_at';
    const DELETED_AT      = 'lapor_deleted_at';
}
