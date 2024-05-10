<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    // Заполняемые поля
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'phone',
        'password',
        'sex',
        'birthday',
        'pass_number',
        'pass_issue_date',
        'pass_birth_address',
        'pass_authority_name',
        'pass_authority_code',
        'role_id',
    ];
    // Скрытие поля пароля
    protected $hidden = ['password'];
    // Хеширование пароля
    protected $casts = ['password' => 'hashed'];

    // Получение модели пользователя по токену
    static public function getByToken($token): User
    {
        $cacheKey = "user:token=$token";
        $user = Cache::get($cacheKey);
        if (!$user) {
            $tokenDB = Token::where('value', $token)->first();
            if (!$tokenDB)
                throw new ApiException(401, 'Invalid token');

            $user = $tokenDB->user;
            Cache::put($cacheKey, $user, 1800);
        }
        return $user;
    }
    public function getFIO(): string
    {
        return $this->name ." "
            . mb_substr($this->surname, 0, 1) . "."
            . ($this->patronymic
                ? mb_substr($this->patronymic, 0, 1) . "."
                : ''
            );
    }

    // Генерация токена
    public function generateToken(): string
    {
        $token = Token::create([
            'user_id' => $this->id,
            'value' => Str::random(255),
        ]);
        return $token->value;
    }

    // Связи
    public function tokens() {
        return $this->hasMany(Token::class);
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
    public function role() {
        return $this->belongsTo(Role::class);
    }
}
