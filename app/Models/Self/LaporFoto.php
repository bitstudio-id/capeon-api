<?php
namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LaporFoto extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 't_lapor_foto';
    protected $primaryKey = 'lapor_foto_id';

    const CREATED_AT      = 'lapor_foto_created_at';
    const UPDATED_AT      = 'lapor_foto_updated_at';
    const DELETED_AT      = 'lapor_foto_deleted_at';
}
