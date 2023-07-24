<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = $notifiable->getEmailForPasswordReset();
        //Variabili che passo, inserire qui anche il getLocale
        $url = url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false));

        if(User::where('email', $email)->first()){
            $_user = User::where('email', $email)->first();
            $currentUserLang = $_user->Lang;
        } else {
            $currentUserLang = Config::get('application.languages');
        }
        
        $locale = app()->setLocale($currentUserLang);

        $data = [
            'resetPassword_subject' => __('messages.email.forgotPassword_subject'),
        ];
    
        return (new MailMessage)
            ->subject($data['resetPassword_subject']) // Oggetto Email
            ->view('emails.reset_password', ['resetUrl' => $url, 'data' => $data]); // Template scelto
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}