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
 * Agency model
 *
 * @package  App
 * @category Models
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class Agency extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'telephone',
        'mobile',
        'address',
        'organization',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the members for the agency.
     */
    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            AgencyMember::class,
            'agency_id',
            'user_id',
        );
    }

    /**
     * Get the mail hosts for the agency.
     */
    public function mailHosts(): HasManyThrough
    {
        return $this->hasManyThrough(
            MailHost::class,
            AgencyMailHost::class,
            'agency_id',
            'mail_host_id',
        );
    }

    /**
     * Get the coses for the agency.
     */
    public function coses(): HasManyThrough
    {
        return $this->hasManyThrough(
            ClassOfService::class,
            AgencyCos::class,
            'agency_id',
            'cos_id',
        );
    }
}
