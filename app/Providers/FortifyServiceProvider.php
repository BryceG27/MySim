<?php

namespace App\Providers;

use App\Models\Sim;
use App\Classes\MySim;
use GuzzleHttp\Client;
use App\Models\Credential;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\SimController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Notifications\CustomVerifyEmailNotification;
use App\Actions\Fortify\UpdateUserProfileInformation;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        /** 
         * Custom template per verifica email
         * Funziona anche sensa ==> Non necessario
         */
        // VerifyEmail::toMailUsing(function ($notifiable, $verificationUrl) {
        //     return (new CustomVerifyEmailNotification($verificationUrl));
        // });
        
        Fortify::authenticateUsing(function (Request $request) {
            $value = $request['g-recaptcha-response'];

            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => env('RECAPTCHA_SECRET_KEY'),
                    'response' => $value,
                ],
            ]);

            $body = json_decode((string)$response->getBody());
            
            if($body->success) {
                $sim = Sim::where('id', $request->sim)->first();
                $user = User::where('email', $request->email)->first();
                
                if($user && Hash::check($request->password, $user->password)) {
                    $SimController = new SimController();
                    
                    if($sim){
                        $SimController->associateSim($user->id, $sim);
                    }

                    return $user;
                }
            }

        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', compact('request'));
        });

        Fortify::verifyEmailView(function () {
            /* return view('auth.verify-email'); */
            if(Auth::user())
                return redirect('/')->with('status', __('auth.verified'));
            else
                return redirect(route('login'))->with('status', __('auth.verified'));
        });
    }
}
