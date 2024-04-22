<?<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'user_id',
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

    /**
     * Get the distribution list's parent.
     */
    public function parent(): HasOneThrough
    {
        return $this->hasOneThrough(
            DistributionList::class,
            DlistHierarchy::class,
            'dlist_id',
            'parent_id',
        );
    }

    /**
     * Get the children for the distribution list.
     */
    public function children(): HasManyThrough
    {
        return $this->hasManyThrough(
            DistributionList::class,
            DlistHierarchy::class,
            'parent_id',
            'dlist_id',
        );
    }

    /**
     * Get the members for the distribution list.
     */
    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(
            Account::class,
            DlistMember::class,
            'dlist_id',
            'account_id',
        );
    }
}