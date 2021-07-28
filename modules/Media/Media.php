<?php
namespace Modules\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Media extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 'm_media';
    protected $primaryKey = 'media_id';

    const CREATED_AT      = 'media_created_at';
    const UPDATED_AT      = 'media_updated_at';
    const DELETED_AT      = 'media_deleted_at';
}
