<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class TokenAuth
{
    public function handle(Request $request, Closure $next, $allowedLevel = 'user')
    {
        // Получаем значение токена из запроса
        $tokenValue = $request->bearerToken();

        if (!$tokenValue) {
            if ($allowedLevel !== 'guest')
                // Если токена нет, но заданный уровень доступа НЕ "гость" —— выводить 401 ошибку
                throw new ApiException(401, 'Token not provided');
        }
        else {
            // Получение настоящего пользователь
            $user = User::getByToken($tokenValue);
            $userRole =  $user->role->code;

            if ($userRole !== 'admin') {
                if ($allowedLevel === 'admin')
                    // Если пользователь не админ, но заданный уровень доступа "администратор" —— выводить 403 ошибку
                    throw new ApiException(403, 'Forbidden for you');

                if ($allowedLevel === 'manager' &&
                    $userRole !== 'manager')
                    // Если пользователь не админ и не менеджер, но заданный уровень доступа "менеджер" —— выводить 403 ошибку
                    throw new ApiException(403, 'Forbidden for you');
            }

            // Запись пользователя в запрос для последующих обработок в контроллерах
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        }

        return $next($request);
    }
}
