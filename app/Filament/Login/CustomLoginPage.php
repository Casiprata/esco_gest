<?php

namespace App\Filament\Login;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;

class CustomLoginPage extends Login
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        if (!Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            throw ValidationException::withMessages(['data.email' => __('filament-panels::pages/auth/login.messages.failed')]);
        }

        // if (!$this->userIsActive(auth()->user())) {
        //     auth()->logout();
        //     throw ValidationException::withMessages(['data.email' => 'A sua conta está bloqueada, consulte um usuario administrador!']);
        // }

        if (!$this->canAcessPanel(auth()->user())) {
            auth()->logout();
            throw ValidationException::withMessages(['data.email' => 'Não tem permissão para aceder a esta área']);
        }

        // /*
        //  * @var User
        //  */
        // if (!User::find(auth()->user()->id)->isActive()) {
        //     auth()->logout();
        //     throw ValidationException::withMessages(['data.email' => 'Conta inativa, consulte um usuario administrador!']);
        // }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    public function userIsActive(User $user): bool
    {
        return $user->isActive();
    }

    public function canAcessPanel($user): bool
{
    $roles = [];

    if (str_contains(Filament::getUrl(), 'admin')) {
        $roles = [User::ROLE_ADMIN, User::ROLE_GESTOR];
    } else if (str_contains(Filament::getUrl(), 'matricula')) {
        $roles = [User::ROLE_MATRICULA];
    } else if (str_contains(Filament::getUrl(), 'professor')) {
        $roles = [User::ROLE_PROFESSOR];
    } else if (str_contains(Filament::getUrl(), 'responsavel')) {
        $roles = [User::ROLE_RESPONSAVEL];
    }

    return in_array($user->role, $roles);
}
}
