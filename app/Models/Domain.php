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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
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

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('agency', function (Builder $builder) {
            $builder->where('agency_id', auth()->user()->agency->id);
        });
    }

    /**
     * Get the agency associated with the domain.
     */
    public function agency(): HasOne
    {
        return $this->hasOne(Agency::class);
    }

    /**
     * Get the coses for the domain.
     */
    public function coses(): BelongsToMany
    {
        return $this->belongsToMany(
            ClassOfService::class,
            'domain_coses',
            'domain_id',
            'cos_id',
        );
    }
}
