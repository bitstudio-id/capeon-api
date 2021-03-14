<?php
namespace App\Models\Self;

use App\Models\Self\Bank;
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

    public function bank()
    {
        return $this->belongsTo(Bank::class, "lapor_bank_bank_id" ,"bank_id");
    }
}
