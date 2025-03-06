<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'role',
    ];

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_GESTOR = 'GESTOR';
    const ROLE_MATRICULA = 'MATRICULA';
    const ROLE_PROFESSOR = 'PROFESSOR';
    const ROLE_RESPONSAVEL = 'RESPONSAVEL';




    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_GESTOR => 'Gestor',
        self::ROLE_MATRICULA => 'Matricula',
        self::ROLE_PROFESSOR => 'Professor',
        self::ROLE_RESPONSAVEL => 'Responsavel',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isGestor()
    {
        return $this->role === self::ROLE_GESTOR;
    }

    public function isMatricula()
    {
        return $this->role === self::ROLE_MATRICULA;
    }

    public function isProfessor()
    {
        return $this->role === self::ROLE_PROFESSOR;
    }

    public function isResponsavel()
    {
        return $this->role === self::ROLE_RESPONSAVEL;
    }

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
        ];
    }

    public function canUserAccessPanel($role): bool
    {
        return strtolower($this->role) === strtolower($role);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_GESTOR]);
        }

        if ($panel->getId() === 'matricula') {
            return in_array($this->role, [self::ROLE_MATRICULA]);
        }

        if ($panel->getId() === 'professor') {
            return in_array($this->role, [self::ROLE_PROFESSOR]);
        }

        if ($panel->getId() === 'responsavel') {
            return in_array($this->role, [self::ROLE_RESPONSAVEL]);
        }

        return false;
    }
}
