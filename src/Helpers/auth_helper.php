<?php

use CI4\Auth\Entities\User;

//-----------------------------------------------------------------------------
if (!function_exists('auth_display')) {
    /**
     * Returns the data of this collector to be formatted in the toolbar
     *
     * @return string
     */
    function auth_display(): string
    {
        $authenticate = service('authentication');
        $authorize = service('authorization');

        if ($authenticate->isLoggedIn()) {
            $user   = $authenticate->user();
            $groups = $authorize->userGroups($user->id);
            $roles  = $authorize->userRoles($user->id);

            $groupsForUser = implode(', ', array_column($groups, 'name'));
            $rolesForUser  = implode(', ', array_column($roles, 'name'));

            $html  = '
            <div class="alert alert-dismissable fade show alert-info" role="alert">
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close" title="Close"></button>
                <h4 class="alert-heading">Current User</h4>
                <hr>
                <div>
                <table><tbody>
                    <tr><td style="width:150px;">User ID</td><td>#{$user->id}</td></tr>
                    <tr><td>Username</td><td>{$user->username}</td></tr>
                    <tr><td>Email</td><td>{$user->email}</td></tr>
                    <tr><td>Groups</td><td>{$groupsForUser}</td></tr>
                    <tr><td>Roles</td><td>{$rolesForUser}</td></tr>
                </tbody></table>
                </div>
            </div>';
        } else {
            $html  = '
            <div class="alert alert-dismissable fade show alert-warning" role="alert">
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close" title="Close"></button>
                <h4 class="alert-heading">Current User</h4>
                <hr>
                <div>
                None logged in.
                </div>
            </div>';
        }

        return $html;
    }
}

//--------------------------------------------------------------------------
/**
 * Dump and Die
 *
 * @param array    $a      Array to print out pretty
 * @param bool     $die    true=die, false=don't die
 */
if (!function_exists("dnd")) {
    /**
     * Dumps the entry of a mixed variable for debugging and dies (if set).
     *
     * @param mixed $a      Data to dump
     * @param bool  $die    True or false (die or not)
     *
     * @return bool
     */
    function dnd($a, $die = true)
    {
        echo highlight_string("<?php\n\$data =\n" . var_export($a, true) . ";\n?>");
        if ($die) die();
    }
}

//-----------------------------------------------------------------------------
if (!function_exists('has_permission')) {
    /**
     * Ensures that the current user has the passed in permission.
     * The permission can be passed in either as an ID or name.
     *
     * @param int|string $permission
     *
     * @return bool
     */
    function has_permission($permission): bool
    {
        $authenticate = service('authentication');
        $authorize    = service('authorization');

        if ($authenticate->check()) {
            return $authorize->hasPermission($permission, $authenticate->id()) ?? false;
        }

        return false;
    }
}

//-----------------------------------------------------------------------------
if (!function_exists('in_groups')) {
    /**
     * Ensures that the current user is in at least one of the passed in groups.
     * The groups can be passed in as either ID's or names.
     * You can pass either a single item or an array of items.
     *
     * Example:
     *  in_groups([1, 2, 3]);
     *  in_groups(14);
     *  in_groups('admins');
     *  in_groups( ['admins', 'moderators'] );
     *
     * @param mixed  $groups
     *
     * @return bool
     */
    function in_groups($groups): bool
    {
        $authenticate = service('authentication');
        $authorize    = service('authorization');

        if ($authenticate->check()) return $authorize->inGroup($groups, $authenticate->id());

        return false;
    }
}

//-----------------------------------------------------------------------------
if (!function_exists('in_roles')) {
    /**
     * Ensures that the current user is in at least one of the passed in roles.
     * The roles can be passed in as either ID's or names.
     * You can pass either a single item or an array of items.
     *
     * Example:
     *  in_roles([1, 2, 3]);
     *  in_roles(14);
     *  in_roles('admins');
     *  in_roles( ['admins', 'moderators'] );
     *
     * @param mixed  $roles
     *
     * @return bool
     */
    function in_roles($roles): bool
    {
        $authenticate = service('authentication');
        $authorize    = service('authorization');

        if ($authenticate->check()) return $authorize->inRole($roles, $authenticate->id());

        return false;
    }
}

//-----------------------------------------------------------------------------
if (!function_exists('logged_in')) {
    /**
     * Checks to see if the user is logged in.
     *
     * @return bool
     */
    function logged_in()
    {
        return service('authentication')->check();
    }
}

//-----------------------------------------------------------------------------
if (!function_exists('user')) {
    /**
     * Returns the User instance for the current logged in user.
     *
     * @return User|null
     */
    function user()
    {
        $authenticate = service('authentication');
        $authenticate->check();
        return $authenticate->user();
    }
}

//-----------------------------------------------------------------------------
if (!function_exists('user_id')) {
    /**
     * Returns the User ID for the current logged in user.
     *
     * @return int|null
     */
    function user_id()
    {
        $authenticate = service('authentication');
        $authenticate->check();
        return $authenticate->id();
    }
}
