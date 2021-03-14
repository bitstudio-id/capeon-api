<?php
namespace App\Models\Self;

use App\Models\Self\LaporBank;
use App\Models\Self\LaporFoto;
use App\Models\Self\LaporMedia;
use App\Models\Self\LaporThumbnail;
use App\Models\User;
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

    public function user()
    {
        return $this->belongsTo(User::class, "lapor_created_by" ,"id");
    }

    public function media()
    {
        return $this->hasMany(LaporMedia::class, "lapor_media_lapor_id" ,"lapor_id");
    }

    public function bank()
    {
        return $this->hasMany(LaporBank::class, "lapor_bank_lapor_id" ,"lapor_id");
    }

    public function foto()
    {
        return $this->hasMany(LaporFoto::class, "lapor_foto_lapor_id" ,"lapor_id");
    }
}
