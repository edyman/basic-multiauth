# Basic multiauth integration #
Goal: use another user table to implement backend access, it assumes that make:auth was executed already

### Just if user table doesn't exist ###
```
php artisan make:migration create_user_table --create
// Add table sctructure
php artisan migrate:install
// Create Tables sctructure
php artisan migrate
``

## Create files controllers, Middleware, Model ##
```
php artisan make:model Admin
// It has to look like as follow
namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Authenticatable
{
    protected $table="user";
    protected $primaryKey="userid";
    protected $guard = "admin";
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

}
```

## Execute commands ##

After create them, copy the content of each file in this repo
```
php artisan make:controller Admin/HomeController

php artisan make:controller Admin/AuthController

php artisan make:controller Admin/PasswordController

php artisan make:middleware AdminAuthenticate

php artisan make:middleware AdminRedirectIfAuthenticated
```

### Create File config/admin.php ###
```
return [
	/**
	 * Define your admin url prefix here.
	 */
	'url' => 'admin/'
];
```

### Modify config/auth.php: ###
```
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => \App\Admin::class,
        ],


    'passwords' => [
        'users' => [
            'provider' => 'users',
            'email' => 'auth.emails.password',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'email'    => 'admin.auth.emails.password',
            'table'    => 'password_resets',
            'expire'   => 60,
        ],
    ],

];

```

### add in Kernel in $routeMiddleware:
```php
      'admin.auth'  => \App\Http\Middleware\AdminAuthenticate::class,
      'admin.guest' => \App\Http\Middleware\AdminRedirectIfAuthenticated::class,
```

## Create healper whith this usefull admin verifications app/helper.php :##

```php
if (! function_exists('admin') )
{
	/**
	 * Returns the admin session instance
	 *
	 * @return  mixed
	 */
	function admin()
	{
		return auth()->guard('admin')->user();
	}
}

if (! function_exists('isAdminLoggedIn') )
{
	/**
	 * Determines if admin is logged in.
	 *
	 * @return boolean  True if admin logged in, False otherwise.
	 */
	function isAdminLoggedIn()
	{
		return auth()->guard('admin')->check();
	}
}
```

### Add Helper.php in composer.json ###

```
"autoload": {
     "classmap": [
         "database"
     ],
     "psr-4": {
         "App\\": "app/"
     },
   "files":[
     "app/helper.php"
   ]
 },
```

### Final commands ###

```
composer dump-autoload

php artisan cache:clear

```

### Add Routes for admin access ###

```
// Admin Authentication Routes...
Route::group(['prefix' => config('admin.url')], function() {
    Route::group(['middleware' => 'admin.auth'], function() {
        Route::get('/', ['as' => 'admin', 'uses' => 'Admin\HomeController@index']);
        Route::get('/home', ['as' => 'admin', 'uses' => 'Admin\HomeController@index']);
        Route::get('logout', ['as' => 'admin.logout', 'uses' => 'Admin\AuthController@logout']);
    });

    Route::group(['middleware' => 'admin.guest'], function() {

        // Authentication Routes...
        Route::get('login', ['as' => 'admin.login', 'uses' => 'Admin\AuthController@showLoginForm']);
        Route::post('login', ['as' => 'admin.login.post', 'uses' => 'Admin\AuthController@login']);

        // Password Reset Routes...
        Route::get('password/reset/{token?}', ['as' => 'admin.password.reset', 'uses' => 'Admin\PasswordController@showResetForm']);
        Route::post('password/email', ['as' => 'admin.password.email', 'uses' => 'Admin\PasswordController@sendResetLinkEmail']);
        Route::post('password/reset', ['as' => 'admin.password.post', 'uses' => 'Admin\PasswordController@reset']);
    });
});
```
