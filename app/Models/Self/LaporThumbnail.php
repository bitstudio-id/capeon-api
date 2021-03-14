<?php
namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LaporThumbnail extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 't_lapor_thumbnail';
    protected $primaryKey = 'lapor_thumbnail_id';

    const CREATED_AT      = 'lapor_thumbnail_created_at';
    const UPDATED_AT      = 'lapor_thumbnail_updated_at';
    const DELETED_AT      = 'lapor_thumbnail_deleted_at';
}
