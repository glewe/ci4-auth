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

class Services extends BaseService {
  //---------------------------------------------------------------------------
  /**
   * This method is responsible for setting up the authentication service.
   * It takes four parameters as input: the library to use for authentication, the user model, the login model, and a boolean indicating whether to get a shared instance.
   * If the library is not provided, it defaults to 'local'.
   * If the user model is not provided, it defaults to the UserModel.
   * If the login model is not provided, it defaults to the LoginModel.
   * If the getShared parameter is not provided, it defaults to true.
   * It first checks if a shared instance is requested. If so, it returns the shared instance.
   * If a shared instance is not requested, it creates a new instance of the specified library with the provided user model and login model.
   *
   * @param string $lib The library to use for authentication. Defaults to 'local'.
   * @param Model $userModel The user model to use. Defaults to UserModel.
   * @param Model $loginModel The login model to use. Defaults to LoginModel.
   * @param bool $getShared Whether to get a shared instance. Defaults to true.
   * @return mixed The authentication service instance.
   */
  public static function authentication(string $lib = 'local', Model $userModel = null, Model $loginModel = null, bool $getShared = true) {
    if ($getShared) return self::getSharedInstance('authentication', $lib, $userModel, $loginModel);

    $userModel = $userModel ?? model(UserModel::class);
    $loginModel = $loginModel ?? model(LoginModel::class);

    /** @var AuthConfig $config */
    $config = config('Auth');
    $class = $config->authenticationLibs[ $lib ];
    $instance = new $class($config);

    return $instance->setUserModel($userModel)->setLoginModel($loginModel);
  }

  //---------------------------------------------------------------------------
  /**
   * This method is responsible for setting up the authorization service.
   * It takes five parameters as input: the role model, the permission model, the user model, a boolean indicating whether to get a shared instance, and the group model.
   * If the role model is not provided, it defaults to the RoleModel.
   * If the permission model is not provided, it defaults to the PermissionModel.
   * If the user model is not provided, it defaults to the UserModel.
   * If the group model is not provided, it defaults to the GroupModel.
   * If the getShared parameter is not provided, it defaults to true.
   * It first checks if a shared instance is requested. If so, it returns the shared instance.
   * If a shared instance is not requested, it creates a new instance of the FlatAuthorization with the provided role model, permission model, and group model.
   * The user model is then set to the created instance.
   *
   * @param Model $roleModel The role model to use. Defaults to RoleModel.
   * @param Model $permissionModel The permission model to use. Defaults to PermissionModel.
   * @param Model $userModel The user model to use. Defaults to UserModel.
   * @param bool $getShared Whether to get a shared instance. Defaults to true.
   * @param Model $groupModel The group model to use. Defaults to GroupModel.
   * @return mixed The authorization service instance.
   */
  public static function authorization(Model $roleModel = null, Model $permissionModel = null, Model $userModel = null, bool $getShared = true, Model $groupModel = null) {
    if ($getShared) return self::getSharedInstance('authorization', $roleModel, $permissionModel, $userModel);

    $groupModel = $groupModel ?? model(GroupModel::class);
    $roleModel = $roleModel ?? model(RoleModel::class);
    $permissionModel = $permissionModel ?? model(PermissionModel::class);
    $userModel = $userModel ?? model(UserModel::class);
    $instance = new FlatAuthorization($groupModel, $roleModel, $permissionModel);

    return $instance->setUserModel($userModel);
  }

  //---------------------------------------------------------------------------
  /**
   * Returns an instance of the PasswordValidator.
   *
   * @param AuthConfig|null $config
   * @param bool $getShared
   *
   * @return PasswordValidator
   */
  public static function passwords(AuthConfig $config = null, bool $getShared = true): PasswordValidator {
    if ($getShared) return self::getSharedInstance('passwords', $config);

    return new PasswordValidator($config ?? config(AuthConfig::class));
  }

  //---------------------------------------------------------------------------
  /**
   * Returns an instance of the Activator.
   *
   * @param AuthConfig|null $config
   * @param bool $getShared
   *
   * @return ActivatorInterface
   */
  public static function activator(AuthConfig $config = null, bool $getShared = true): ActivatorInterface {
    if ($getShared) return self::getSharedInstance('activator', $config);

    $config = $config ?? config(AuthConfig::class);
    $class = $config->requireActivation ?? UserActivator::class;

    return new $class($config);
  }

  //---------------------------------------------------------------------------
  /**
   * Returns an instance of the Resetter.
   *
   * @param AuthConfig|null $config
   * @param bool $getShared
   *
   * @return ResetterInterface
   */
  public static function resetter(AuthConfig $config = null, bool $getShared = true): ResetterInterface {
    if ($getShared) return self::getSharedInstance('resetter', $config);

    $config = $config ?? config(AuthConfig::class);
    $class = $config->activeResetter ?? EmailResetter::class;

    return new $class($config);
  }
}
