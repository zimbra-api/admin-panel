<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

/**
 * Alias model
 *
 * @package  App
 * @category Models
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class Alias extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'aliases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agency_id',
        'domain_id',
        'account_id',
        'name',
        'zimbra_target_id',
        'zimbra_create',
        'created_by',
        'updated_by',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('agency', function (Builder $builder) {
            $builder->where('agency_id', auth()->user()->agency->id);
        });
    }

    /**
     * Get the agency associated with the alias.
     */
    public function agency(): HasOne
    {
        return $this->hasOne(Agency::class, 'agency_id');
    }

    /**
     * Get the domain associated with the alias.
     */
    public function domain(): HasOne
    {
        return $this->hasOne(Domain::class, 'domain_id');
    }

    /**
     * Get the account associated with the alias.
     */
    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'account_id');
    }
}
