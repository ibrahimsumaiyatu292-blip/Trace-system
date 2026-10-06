<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * Determine whether the user can view any assets.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the asset.
     */
    public function view(User $user, Asset $asset): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create assets.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'Administrator', 'Procurement Officer'])
            || $user->can('create_asset');
    }

    /**
     * Determine whether the user can update the asset.
     */
    public function update(User $user, Asset $asset): bool
    {
        // Admins and Procurement Officers can edit assets
        return $user->hasAnyRole(['admin', 'Administrator', 'Procurement Officer'])
            || $user->can('edit_asset');
    }

    /**
     * Determine whether the user can delete the asset.
     */
    public function delete(User $user, Asset $asset): bool
    {
        return $user->hasAnyRole(['admin', 'Administrator'])
            || $user->can('delete_asset');
    }
}
