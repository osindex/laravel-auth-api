## 第三方应用API接口扩展

### 安装
```bash
composer require osi/auth-api
```
### 依赖
```bash
composer require laravel/sanctum
```

### 最佳实践
```bash
#依赖组件
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
#此组件
composer require osi/auth-api
php artisan vendor:publish --provider="Osi\AuthApi\ExtendServiceProvider"

php artisan authapi:menu
```
### 配置
- 如果基于 `smallruraldog/laravel-vue-admin` 则修改 `config/auth.php` providers:
```php
 	// ...
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // ** New provider**
        'admin-api' => [
            'driver' => 'eloquent',
            'model' => Osi\AuthApi\Models\Admin::class,
        ],
    ],
    // ...
```
并且修改 `config/auth.php` guards:
```php
	'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
	    // ** New guard **
        'admin-api' => [
            'driver' => 'sanctum',
            'provider' => 'admin-api',
            'name' => 'Admin`s Api',
        ],
    ],
```
- 如果是常规laravel项目或使用其它用户表登录
配置model
```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;
use Osi\AuthApi\Models\AuthInterface;
class Admin extends Authenticatable implements AuthInterface
{
    use HasApiTokens;
    public function resourceFormat()
    {
    	return $this;
    	// OR Create New Resource
        // return new AuthResource($this);
    }
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
    public function apiLogs(): MorphMany
    {
       // return $this->morphMany(ApiLog::class, 'model');
    }
    public function apiPermissions(): MorphMany
    {
       // return $this->morphMany(ApiPermission::class, 'model');
    }
}
```
### 使用
- 登录
```
POST /api/sanctum/token HTTP/1.1
Content-Type: application/json
Accept: application/json

{"username":"admin","password":"admin","provider":"admin-api"}

// 返回 Bearer 的后面部分
{
  "data": "KW9DFVIIjJrk5YqmF1tlQC9Rr8jZW8Lsk9o7iR5Fue8XzXUEAgDXhGkghLiJGU6dVXw2fFnVRqCR6zeA"
}
```
- 当前用户信息
```
GET /api/sanctum/me HTTP/1.1
Authorization: Bearer KW9DFVIIjJrk5YqmF1tlQC9Rr8jZW8Lsk9o7iR5Fue8XzXUEAgDXhGkghLiJGU6dVXw2fFnVRqCR6zeA
Content-Type: application/json
Accept: application/json

// 返回用户信息 定义model文件中 resource 函数可修改
public function resourceFormat() 
```


### 其它提供方法
- data
- created
- accepted
- noContent
- badRequest
- unauthorized
- forbidden
- unprocesableEtity
- success
```
建议所有外部接口继承基础控制器：
Osi\AuthApi\Controllers\Controller
```