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
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (self $model) {
            $model->created_by = auth()->user()->id;
        });

        static::updating(static function (self $model) {
            $model->updated_by = auth()->user()->id;
        });
    }

    /**
     * Get the members for the agency.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'agency_members',
            'agency_id',
            'user_id',
        );
    }

    /**
     * Get the mail hosts for the agency.
     */
    public function mailHosts(): BelongsToMany
    {
        return $this->belongsToMany(
            MailHost::class,
            'agency_mail_hosts',
            'agency_id',
            'mail_host_id',
        );
    }

    /**
     * Get the coses for the agency.
     */
    public function coses(): BelongsToMany
    {
        return $this->belongsToMany(
            ClassOfService::class,
            'agency_coses',
            'agency_id',
            'cos_id',
        );
    }
}
