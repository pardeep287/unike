<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Route;
use App\MyRouter;
use App\Unit;
class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/');
            }
        }

        $currentRouteName = \Route::currentRouteName(); // current route name
        if (!isSuperAdmin()) {
            $routes = getSuperAdminRoutes();
            //dd($routes,$currentRouteName);

            if (in_array($currentRouteName, $routes)) {
                abort(401);
            }
        }

        /*
        *start user_permission json file decoding
        *get file , get route name and check in file if exist then pass otherwise go to dashboard or previous page
        *
        */
        if (authUser() && !isAdmin() && !isSuperAdmin())
        {
            if(authUser()->roll_id == 2){
                /*Customer Login*/
                $getCustomerRoutes = getCustomerRoutes();
                if(!in_array($currentRouteName, $getCustomerRoutes) && $currentRouteName != null){
                    abort(401);
                }
            }
            else {
                $permissionResult = getUserPermission();
                $routeCheck = in_array($currentRouteName, $permissionResult);
                if($currentRouteName != 'dashboard') {
                    if($routeCheck != 1) {

                        if($request->ajax()) {
                            return validationResponse(false, 207, lang('auth.auth_required'));
                        }
                        abort(401);
                    }
                }
            }
        }
        else if(isAdmin()){
            $getAdminRoutesNotAllowed = getCustomerRoutesNotAllowed();
            if(in_array($currentRouteName, $getAdminRoutesNotAllowed) && $currentRouteName != null){
                abort(401);
            }
        }

        $routeData = getModelByRouteName($currentRouteName);

        if(!isFullVersion()) {
            if( count($routeData) > 0) {
                $model = $routeData['model'];
                $count = $routeData['rows'];
                $redirectRoute = $routeData['redirect_url'];
                $result  = isDemoVersionExpired($model, $count);
                 if($result) {
                     return redirect()->route($redirectRoute)->with('error', lang('common.demo_version_expired'));
                 }
            }
        }

        return $next($request);
    }
}
