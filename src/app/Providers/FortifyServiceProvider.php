<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\LoginRequest;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use illuminate\Http\JsonRequest;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;




class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/attendance');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
public function boot():void
{
Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {

            if(auth()->check()&& auth()->user()->role===0){
                return view('admin.login');
            }else
            return view('auth.login');

        });

Fortify::authenticateUsing(function (Request $request){
    $loginRequest = new LoginRequest();
    $validated =  $loginRequest->validate($request->all());

    $user = \App\Models\User::where('email', $request->email)->first();

        return $user;
});







        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);


    }
}