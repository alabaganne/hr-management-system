<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *      schema="user",
 *      title="User",
 *      description="User object",
 *      type="object",
 * 
 *      @OA\Property(property="name", type="string", example="Ala Baganne"),
 *      @OA\Property(property="username", type="string", example="alabaganne"),
 *      @OA\Property(property="email", type="string", format="email", example="alabaganne9@gmail.com"),
 *      @OA\Property(property="password", type="string"),
 *      @OA\Property(property="phone_number", type="string", example="50101959"),
 *      @OA\Property(property="date_of_birth", type="string", format="dateTime", example="1999-12-16"),
 *      @OA\Property(property="address", type="string", type="string"),
 *      @OA\Property(property="gender", type="string", enum={"male", "female"}),
 *      @OA\Property(property="civil_status", enum={"single", "married"}),
 *      @OA\Property(property="id_card_number", type="string"),
 *      @OA\Property(property="nationality", type="string", example="Tunisian"),
 *      @OA\Property(property="university", type="string", example="Higher institute of applied science and technology of sousse"),
 *      @OA\Property(property="history", type="string"),
 *      @OA\Property(property="experience_level", type="integer", maximum=5, example=4),
 *      @OA\Property(property="source", type="string"),
 *      @OA\Property(property="position", type="string", example="Full stack web developer"),
 *      @OA\Property(property="grade", type="string"),
 *      @OA\Property(property="hiring_date", type="string", format="dateTime"),
 *      @OA\Property(property="contract_end_date", type="string", format="dateTime"),
 *      @OA\Property(property="type_of_contract", type="string", enum={"option 1", "option 2", "option 3"}),
 *      @OA\Property(property="allowed_leave_days", type="integer", default=30),
 *      @OA\Property(property="department_id", type="integer", example=1)
 * )
*/

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $guard_name = 'api'; // https://github.com/spatie/laravel-permission/issues/686

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'deleted_at' => 'datetime:Y-m-d',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // ? Check if user is admin
    public function isAdmin() {
        return $this->hasRole('admin');
    }

    // relationships
    public function department() {
        return $this->belongsTo('App\Models\Department');
    }

    public function evaluations() {
        return $this->hasMany('App\Models\Evaluation');
    }

    public function skills() {
        return $this->belongsToMany('App\Models\Skill')
            ->withPivot('id', 'note')
            ->withTimestamps();
    }

    public function trainings() {
        return $this->hasMany('App\Models\Training');
    }

    public function leaves() {
        return $this->hasMany('App\Models\Leave');
    }

}
