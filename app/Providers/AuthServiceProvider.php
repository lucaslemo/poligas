<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function($notifiable, $token){
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expires = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
            return (new MailMessage)
                ->greeting('Olá!')
                ->subject('Notificação de redefinição de senha.')
                ->line('Você está recebendo este email porque recebemos uma solicitação de redefinição de senha para sua conta.')
                ->action('Redefinir senha', $url)
                ->line('Este link de redefinição de senha expirará em '.$expires.' minutos.')
                ->line('Se você não solicitou uma redefinição de senha, nenhuma ação adicional será necessária.')
                ->salutation('Atenciosamente, Equipe '.config('app.name').'.');
        });
    }
}
