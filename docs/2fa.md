# CI4-Auth

## 2FA

The 2FA integration of CI4-Auth is based on [RobThree TwoFactorAuth](http://github.com/RobThree/TwoFactorAuth). The 
package provides a solid and easy to use implementation of a two factor authentication incl. the onboarding process.

The concept CI4-Auth is following during a 2FA login is:

1. Credential login (username/password)
2. User is not logged in yet but a "2FA login in progress" flag is set
3. The 2FA login page for the user is displayed
4. 2FA PIN login
5. User is logged in

### Options

You can configure two settings in **lewe/ci4-auth/src/Config/Auth.php**

The following setting determines whether users will be forced to setup 2FA (default is false). When this setting is set to `true` , a user will be redirected to the 2FA setup page after his credential login when
his record does not hold a secret key yet.
```
public $require2FA = false;
```
The next setting specifies the title of the application as it appears in the authenticator App.
```
public $authenticatorTitle = 'CI4Auth';
```
The authenticator title will be suffixed the the user's email so that it will read:

_**CI4Auth: admin@mydomain.com**_

### Removing Secret Keys

Users with 'Manage Users' permission can delete the secret key of user accounts from the user list page. 
This might become necessary when a user has a new device on which he must setup the authenticator App again.

A user cannot remove his own secret key. However, he can start the onboarding process again (2FA Setup after login) 
which will overwrite the existing key.
