<?php

namespace App\Models;

use Encore\Admin\Form\Field\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class UserOperationLog extends Model
{
    protected $table = 'user_operation_logs';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
