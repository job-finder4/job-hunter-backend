<?php

namespace App\Models;

use App\Traits\Company;
use App\Traits\JobSeeker;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Company, JobSeeker, Notifiable, HasRoles,HasApiTokens;
    protected $guard_name = 'api';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function image()
    {
        return $this->morphOne(Image::class,'imageable')
            ->withDefault([
                'path' => 'storage/profile/user-default.jpg'
            ]);
    }


    /**
     * @param mixed $user
     */
    public function deleteOldImage(): void
    {
        if ($this->image()->exists()) {
            $image = $this->image;
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }


    /**
     * @param UploadedFile $image
     */
    public function storeImage(UploadedFile $image): Image
    {
        Storage::disk('public')->put('/profile', $image);

        $image = $this->image()->create([
            'path' => $image->hashName('storage/profile/')
        ]);
        return $image;
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

}
