<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            $user->name = self::makeNameUrlCompatible($user->name);
            
            if ($user->isDirty('name')) {
                $user->name = self::makeUniqueName($user->name, $user->id);
            }
        });
    }


    private static function makeNameUrlCompatible($name)
    {
        // заменяем пробелы на подчеркивания, удаляем спецсимволы
        $name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $name); // удаляем спецсимволы
        $name = str_replace(' ', '_', trim($name)); // пробелы в подчеркивания
        return $name;
    }

    private static function makeUniqueName($name, $excludeId = null)
    {
        $originalName = $name;
        $counter = 1;

        $query = self::where('name', $name);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $name = $originalName . '_' . $counter;
            $counter++;
            
            $query = self::where('name', $name);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $name;
    }

    public function museums()
    {
        return $this->hasMany(Museum::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function getRouteKeyName()
    {
        return 'name';
    }
}
