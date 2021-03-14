<?php
namespace App\Models\Self;

use App\Models\Self\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LaporMedia extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 't_lapor_media';
    protected $primaryKey = 'lapor_media_id';

    const CREATED_AT      = 'lapor_media_created_at';
    const UPDATED_AT      = 'lapor_media_updated_at';
    const DELETED_AT      = 'lapor_media_deleted_at';

    public function media()
    {
        return $this->belongsTo(Media::class, "lapor_media_media_id" ,"media_id");
    }
}
