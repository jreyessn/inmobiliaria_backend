<?php

namespace App\Models\Contact;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Contact.
 *
 * @package namespace App\Models\Contact;
 */
class Contact extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'customer_id',
        'user_id',
        'address',
        'language',
        'note',
        'avatar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarAttribute($value)
    {
        return $value? "contact/profile/{$value}" : null;
    }
}
