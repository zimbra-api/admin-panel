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
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Model;

/**
 * Account model
 *
 * @package  App
 * @category Models
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class Account extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agency_id',
        'domain_id',
        'cos_id',
        'zimbra_cos_id',
        'zimbra_id',
        'name',
        'status',
        'mail_host',
        'display_name',
        'title',
        'organization',
        'organization_unit',
        'telephone',
        'mobile',
        'address',
        'zimbra_create',
        'description',
        'attributes',
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
     * Get the agency associated with the account.
     */
    public function agency(): HasOne
    {
        return $this->hasOne(Agency::class, 'agency_id');
    }

    /**
     * Get the domain associated with the account.
     */
    public function domain(): HasOne
    {
        return $this->hasOne(Domain::class, 'domain_id');
    }

    /**
     * Get the cos associated with the account.
     */
    public function cos(): HasOne
    {
        return $this->hasOne(ClassOfService::class, 'cos_id');
    }

    /**
     * Get the distribution list which the account is member of.
     */
    public function memberOf(): HasOneThrough
    {
        return once(fn () => $this->hasOneThrough(
            DistributionList::class,
            DlistMembers::class,
            'account_id',
            'id',
            'id',
            'dlist_id',
        ));
    }
}
