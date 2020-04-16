<?php
namespace Osi\AuthApi\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface AuthInterface
{
    public function resourceFormat();
    public function apiLogs(): morphMany;
    public function apiPermissions(): morphMany;
}
