<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;
    protected $table      = 't_token';
    protected $primaryKey = 'token_id';

    const CREATED_AT      = 'token_created_at';
    const UPDATED_AT      = 'token_updated_at';
    const DELETED_AT      = 'token_deleted_at';
}
