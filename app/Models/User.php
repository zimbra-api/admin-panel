<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Models;

use App\Enums\AdminPanel;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

/**
 * User model
 *
 * @package  App
 * @category Models
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    /**
     * User can access panel in production.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->isSupperAdmin()) {
            return true;
        }
        return match ($panel->getId()) {
            AdminPanel::Admin->value => $this->hasRole(UserRole::Administrator),
            AdminPanel::Agency->value => $this->hasRole(UserRole::Agency),
            default => false,
        };
    }

    /**
     * User is supper admin.
     *
     * @return bool
     */
    public function isSupperAdmin(): bool
    {
        return $this->id === 1;
    }
}
