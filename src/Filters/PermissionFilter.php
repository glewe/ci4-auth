<?php

namespace CI4\Auth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CI4\Auth\Exceptions\PermissionException;

class PermissionFilter implements FilterInterface {

  /**
   * --------------------------------------------------------------------------
   * Before.
   * --------------------------------------------------------------------------
   *
   * Do whatever processing this filter needs to do. By default it should not
   * return anything during normal execution. However, when an abnormal state
   * is found, it should return an instance of CodeIgniter\HTTP\Response. If
   * it does, script execution will end and that Response will be sent back
   * to the client, allowing for error pages, redirects, etc.
   *
   * @param RequestInterface $request
   * @param array|null       $arguments
   *
   * @return \CodeIgniter\HTTP\RedirectResponse|bool
   */
  public function before(RequestInterface $request, $arguments = null): \CodeIgniter\HTTP\RedirectResponse|bool {
    //
    // Load the 'auth' helper if the 'logged_in' function does not exist
    //
    if (!function_exists('logged_in')) {
      helper('auth');
    }

    //
    // If no roles are specified, return without doing anything
    //
    if (empty($arguments)) {
      return false;
    }

    //
    // Get the authentication service
    //
    $authenticate = service('authentication');

    //
    // If no user is logged in, redirect to the login form
    //
    if (!$authenticate->check()) {
      session()->set('redirect_url', current_url());
      return redirect('login');
    }

    //
    // Get the authorization service
    //
    $authorize = service('authorization');

    //
    // Check if the user has any of the required permissions
    //
    $result = true;
    foreach ($arguments as $permission) {
      $result = $result && $authorize->hasPermission($permission, $authenticate->id());
    }

    //
    // If the user does not have the required permissions, handle the response
    //
    if (!$result) {
      if ($authenticate->silent()) {
        // Redirect to the error page
        $redirectURL = '/error_auth';
        unset($_SESSION['redirect_url']);
        return redirect()->to($redirectURL)->with('error', lang('Auth.exception.insufficient_permissions'));
      } else {

        // Throw a PermissionException
//        throw new PermissionException(lang('Auth.exception.insufficient_permissions'));

        // Redirect to the error page
        $redirectURL = '/error_auth';
        unset($_SESSION['redirect_url']);
        return redirect()->to($redirectURL)->with('error', lang('Auth.exception.insufficient_permissions'));
      }
    }
    return false;
  }

  /**
   * --------------------------------------------------------------------------
   * After.
   * --------------------------------------------------------------------------
   *
   * Allows After filters to inspect and modify the response object as needed.
   * This method does not allow any way to stop execution of other after filters,
   * short of throwing an Exception or Error.
   *
   * @param RequestInterface  $request
   * @param ResponseInterface $response
   * @param array|null        $arguments
   *
   * @return void
   */
  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
