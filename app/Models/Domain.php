<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model;

/**
 * Domain model
 *
 * @package  App
 * @category Models
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class Domain extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'domains';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agency_id',
        'domain_admin',
        'zimbra_id',
        'name',
        'status',
        'max_accounts',
        'total_accounts',
        'zimbra_create',
        'description',
        'attributes',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the coses for the domain.
     */
    public function coses(): HasManyThrough
    {
        return $this->hasManyThrough(
            ClassOfService::class,
            DomainCos::class,
            'domain_id',
            'cos_id',
        );
    }
}
