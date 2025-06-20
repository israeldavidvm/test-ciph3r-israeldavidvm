<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use Illuminate\Support\MessageBag;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class User extends Authenticatable
{

    use \Israeldavidvm\EloquentTraits\AttributesTrait;

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token'
    ];

	public function initAttributes($attributes) {

		$this->name=$attributes['name'];
        $this->email=$attributes['email'];
        $this->password=$attributes['password'];
      
    }

	public static function validateAttributes (array $arrayattributes):  ?MessageBag {

        $validator = Validator::make($arrayattributes, 
        [
            'name' => [
                'required',
                'max:255'
            ],
            'email' => [
                'required',
                'max:255',
                'email:dns,rfc', // Formato de email válid
            ],
            'password' => [
                'required',
                'max:255',
                'min:8', 
                //Contiene al menos una letra minúscula, 
                // una mayúscula, un número y un carácter especial
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[.$%@!&*+]).*$/',
            ],
            'action' => [
                'required',
                Rule::in(['create', 'update', 'updateOrCreate']),
            ]
        ])->sometimes('name', 'unique:users,name', function ($input) {
            return strtolower($input->action) == 'create';
        })->sometimes('email', 'unique:users,email', function ($input) {
            return strtolower($input->action) == 'create';
        });
        

        if ($validator->fails()) {
            return $validator->errors();
        }

        return null;
    }
}