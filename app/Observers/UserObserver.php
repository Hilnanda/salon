<?php

namespace App\Observers;

use App\Helper\SearchLog;
use App\User;

class UserObserver
{
    public function roleAttached(User $user, $role, $team)
    {
        if (!$user->is_admin) {
            $type = 'Employee';
            $route = 'admin.employee.edit';

            if ($user->is_customer) {
                $type = 'Customer';
                $route = 'admin.customers.show';
            }

            SearchLog::createSearchEntry($user->id, $type, $user->name, $route);
            SearchLog::createSearchEntry($user->id, $type, $user->email, $route);
        }
    }

    public function updating(User $user)
    {
        if (!$user->is_admin) {
            $type = 'Employee';
            $route = 'admin.employee.edit';

            if ($user->is_customer) {
                $type = 'Customer';
                $route = 'admin.customers.show';
            }

            if ($user->isDirty('name')) {
                $original = $user->getOriginal('name');
                SearchLog::updateSearchEntry($user->id, $type, $user->name, $route, ['name' => $original]);
            }

            if ($user->isDirty('email')) {
                $original = $user->getOriginal('email');
                SearchLog::updateSearchEntry($user->id, $type, $user->email, $route, ['email' => $original]);
            }
        }
    }

    public function deleted(User $user)
    {
        if (!$user->is_admin) {
            $route = 'admin.employee.edit';

            if ($user->is_customer) {
                $route = 'admin.customers.show';
            }

            SearchLog::deleteSearchEntry($user->id, $route);
        }
    }
}
