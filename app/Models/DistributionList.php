<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Model;

/**
 * Distribution list model
 *
 * @package  App
 * @category Models
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class DistributionList extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'distribution_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agency_id',
        'domain_id',
        'group_admin',
        'zimbra_id',
        'name',
        'display_name',
        'total_members',
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
     * Get the distribution list's parent.
     */
    public function parent(): HasOneThrough
    {
        return $this->hasOneThrough(
            DistributionList::class,
            DlistHierarchy::class,
            'dlist_id',
            'id',
            'id',
            'parent_id',
        );
    }

    /**
     * Get the children for the distribution list.
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            DistributionList::class,
            'dlist_hierarchy',
            'parent_id',
            'dlist_id',
        );
    }

    /**
     * Get the members for the distribution list.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            Account::class,
            'dlist_members',
            'dlist_id',
            'account_id',
        );
    }
}
