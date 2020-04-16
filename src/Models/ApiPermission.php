<?php
namespace Osi\AuthApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApiPermission extends Model
{
    protected $guarded = [];
    protected $casts = [
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
