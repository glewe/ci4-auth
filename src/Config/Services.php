<?php

namespace CI4\Auth\Config;

use CodeIgniter\Model;
use CI4\Auth\Config\Auth as AuthConfig;
use CI4\Auth\Authorization\FlatAuthorization;
use CI4\Auth\Models\UserModel;
use CI4\Auth\Models\LoginModel;
use CI4\Auth\Authorization\GroupModel;
use CI4\Auth\Authorization\RoleModel;
use CI4\Auth\Authorization\PermissionModel;
use CI4\Auth\Authentication\Activators\ActivatorInterface;
use CI4\Auth\Authentication\Activators\UserActivator;
use CI4\Auth\Authentication\Passwords\PasswordValidator;
use CI4\Auth\Authentication\Passwords\ValidatorInterface;
use CI4\Auth\Authentication\Resetters\EmailResetter;
use CI4\Auth\Authentication\Resetters\ResetterInterface;
use Config\Services as BaseService;

class Services extends BaseService
{
    //-------------------------------------------------------------------------
    /**
     */
    public static function authentication(string $lib = 'local', Model $userModel = null, Model $loginModel = null, bool $getShared = true)
    {
        if ($getShared) return self::getSharedInstance('authentication', $lib, $userModel, $loginModel);

        $userModel  = $userModel ?? model(UserModel::class);
        $loginModel = $loginModel ?? model(LoginModel::class);

        /** @var AuthConfig $config */
        $config   = config('Auth');
        $class     = $config->authenticationLibs[$lib];
        $instance = new $class($config);

        return $instance->setUserModel($userModel)->setLoginModel($loginModel);
    }

    //-------------------------------------------------------------------------
    /**
     */
    public static function authorization(Model $roleModel = null, Model $permissionModel = null, Model $userModel = null, bool $getShared = true, Model $groupModel = null)
    {
        if ($getShared) return self::getSharedInstance('authorization', $roleModel, $permissionModel, $userModel, $getShared, $groupModel);

        $groupModel       = $groupModel ?? model(GroupModel::class);
        $roleModel        = $roleModel ?? model(RoleModel::class);
        $permissionModel  = $permissionModel ?? model(PermissionModel::class);
        $userModel        = $userModel ?? model(UserModel::class);
        $instance         = new FlatAuthorization($groupModel, $roleModel, $permissionModel);

        return $instance->setUserModel($userModel);
    }

    //-------------------------------------------------------------------------
    /**
     * Returns an instance of the PasswordValidator.
     *
     * @param AuthConfig|null $config
     * @param bool      $getShared
     *
     * @return PasswordValidator
     */
    public static function passwords(AuthConfig $config = null, bool $getShared = true): PasswordValidator
    {
        if ($getShared) return self::getSharedInstance('passwords', $config);

        return new PasswordValidator($config ?? config(AuthConfig::class));
    }

    //-------------------------------------------------------------------------
    /**
     * Returns an instance of the Activator.
     *
     * @param AuthConfig|null $config
     * @param bool      $getShared
     *
     * @return ActivatorInterface
     */
    public static function activator(AuthConfig $config = null, bool $getShared = true): ActivatorInterface
    {
        if ($getShared) return self::getSharedInstance('activator', $config);

        $config = $config ?? config(AuthConfig::class);
        $class   = $config->requireActivation ?? UserActivator::class;

        return new $class($config);
    }

    //-------------------------------------------------------------------------
    /**
     * Returns an instance of the Resetter.
     *
     * @param AuthConfig|null $config
     * @param bool      $getShared
     *
     * @return ResetterInterface
     */
    public static function resetter(AuthConfig $config = null, bool $getShared = true): ResetterInterface
    {
        if ($getShared) return self::getSharedInstance('resetter', $config);

        $config = $config ?? config(AuthConfig::class);
        $class   = $config->activeResetter ?? EmailResetter::class;

        return new $class($config);
    }
}
