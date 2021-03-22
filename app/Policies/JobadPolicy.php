<?php

namespace App\Policies;

use App\Models\Jobad;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class JobadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Jobad $jobad
     * @return mixed
     */
    public function view(User $user, Jobad $jobad)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('create jobads'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Jobad $jobad
     * @return mixed
     */
    public function update(User $user, Jobad $jobad)
    {
        if ($user->can('update jobads'))
            return $user->id == $jobad->user_id;
        return false;
    }

    /**
     * Determine whether the user can approve any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function approve(User $user,Jobad $jobad)
    {
        if ($user->can('approve jobads'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can view any active or inactive models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewCompanyJobads(User $user)
    {
        if ($user->can('view all company jobads'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Jobad $jobad
     * @return mixed
     */
    public function delete(User $user, Jobad $jobad)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Jobad $jobad
     * @return mixed
     */
    public function restore(User $user, Jobad $jobad)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Jobad $jobad
     * @return mixed
     */
    public function forceDelete(User $user, Jobad $jobad)
    {
        //
    }
}
