<?php
namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LaporBank extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table      = 't_lapor_bank';
    protected $primaryKey = 'lapor_bank_id';

    const CREATED_AT      = 'lapor_bank_created_at';
    const UPDATED_AT      = 'lapor_bank_updated_at';
    const DELETED_AT      = 'lapor_bank_deleted_at';
}
