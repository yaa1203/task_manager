<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    public function view(User $user, Workspace $workspace)
    {
        return $user->id === $workspace->user_id;
    }

    public function update(User $user, Workspace $workspace)
    {
        return $user->id === $workspace->user_id;
    }

    public function delete(User $user, Workspace $workspace)
    {
        return $user->id === $workspace->user_id;
    }
}
