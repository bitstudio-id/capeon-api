<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisterToken extends Model
{
    use SoftDeletes;
    protected $table      = 't_register_token';
    protected $primaryKey = 'register_token_id';

    const CREATED_AT      = 'register_token_created_at';
    const UPDATED_AT      = 'register_token_updated_at';
    const DELETED_AT      = 'register_token_deleted_at';
}
