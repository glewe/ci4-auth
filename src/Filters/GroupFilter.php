<?php

namespace CI4\Auth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CI4\Auth\Exceptions\PermissionException;

class GroupFilter implements FilterInterface {

  /**
   * --------------------------------------------------------------------------
   * Before.
   * --------------------------------------------------------------------------
   *
   * Handles the logic to be executed before the request is processed.
   *
   * This method checks if the user is logged in and belongs to the required groups.
   * If the user is not logged in, they are redirected to the login page.
   * If the user does not belong to the required groups, they are redirected to an
   * error page or an exception is thrown.
   *
   * @param RequestInterface $request   The current request instance.
   * @param array|null       $arguments The groups required to access the resource.
   *
   * @return \CodeIgniter\HTTP\RedirectResponse|bool
   */
  public function before(RequestInterface $request, $arguments = null): \CodeIgniter\HTTP\RedirectResponse|bool {
    //
    // Load the 'auth' helper if the 'logged_in' function does not exist
    //
    if (!function_exists('logged_in')) helper('auth');

    //
    // If no groups are specified, return false
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
    // Check each requested group
    //
    foreach ($arguments as $group) {
      if ($authorize->inGroup($group, $authenticate->id())) {
        return false;
      }
    }

    //
    // If the user does not belong to the required groups, handle the response
    //
    if ($authenticate->silent()) {
      // Redirect to the error page
      $redirectURL = '/error';
      unset($_SESSION['redirect_url']);
      return redirect()->to($redirectURL)->with('error', lang('Auth.exception.insufficient_permissions'));
    } else {
      // Throw a PermissionException
      throw new PermissionException(lang('Auth.exception.insufficient_permissions'));
    }
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
