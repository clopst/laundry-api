<?php

namespace App\Models;

use App\Traits\WithPaginatedData;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, WithPaginatedData;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    protected $searchable = [
        'name',
        'username',
        'email',
        'role'
    ];

    /**
     * Get all of the outlets that are assigned to this user.
     *
     * @return void
     */
    public function outlets()
    {
        return $this->morphedByMany(Outlet::class, 'userable');
    }

    /**
     * Save data and handle avatar image.
     *
     * @return bool
     */
    public function saveDataWithAvatar($attributes = [])
    {
        $fillableInputs = collect($attributes)->only($this->getFIllable());

        foreach ($fillableInputs as $key => $value) {
            if ($key === 'password') {
                $this->{$key} = Hash::make($value);
            } else {
                $this->{$key} = $value;
            }
        }

        if (isset($attributes['avatar_path'])) {
            $this->saveAvatar($attributes['avatar_path']);
        }

        return $this->save();
    }

    /**
     * Remove avatar image and path.
     *
     * @return void
     */
    public function removeAvatar()
    {
        if ($this->avatar_path && file_exists(storage_path('app/public/' . $this->avatar_path))) {
            Storage::delete('public/' . $this->avatar_path);
        }
    }

    /**
     * Save new avatar image.
     *
     * @return void
     */
    public function saveAvatar($avatar)
    {
        $this->removeAvatar();

        $file = $avatar->store('avatars', 'public');
        $this->avatar_path = $file;
    }
}
