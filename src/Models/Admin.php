<?php
namespace Osi\AuthApi\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;
use Osi\AuthApi\Resources\AuthResource;
use SmallRuralDog\Admin\Auth\Database\Administrator;

/**
 * Api used
 */
class Admin extends Administrator implements AuthInterface
{
    use HasApiTokens;
    protected $hidden = ['remember_token', 'password'];
    protected $casts = [
        'created_at' => 'Y-m-d H:i:s',
        'updated_at' => 'Y-m-d H:i:s',
    ];
    public function resourceFormat($model = false)
    {
        return new AuthResource($model ? $model : $this);
    }
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
    public function queryLike($query)
    {
        return $this->where('username', 'like', '%' . $query . '%')->Orwhere('name', 'like', '%' . $query . '%');
    }
    public function apiLogs(): MorphMany
    {
        return $this->morphMany(ApiLog::class, 'model');
    }
    public function apiPermissions(): MorphMany
    {
        return $this->morphMany(ApiPermission::class, 'model');
    }
}
