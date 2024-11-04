<?php

namespace CI4\Auth\Collectors;

use CodeIgniter\Debug\Toolbar\Collectors\BaseCollector;
use CI4\Auth\Authorization\GroupModel;
use CI4\Auth\Authorization\RoleModel;

/**
 * Debug Toolbar Collector for Auth
 */
class Auth extends BaseCollector {
  /**
   * Whether this collector has data that can
   * be displayed in the Timeline.
   *
   * @var boolean
   */
  protected $hasTimeline = false;

  /**
   * Whether this collector needs to display
   * content in a tab or not.
   *
   * @var boolean
   */
  protected $hasTabContent = true;

  /**
   * Whether this collector has data that
   * should be shown in the Vars tab.
   *
   * @var boolean
   */
  protected $hasVarData = false;

  /**
   * The 'title' of this Collector.
   * Used to name things in the toolbar HTML.
   *
   * @var string
   */
  protected $title = 'Auth';

  /**
   * --------------------------------------------------------------------------
   * Get Title Details.
   * --------------------------------------------------------------------------
   *
   * Returns any information that should be shown next to the title.
   *
   * @return string
   */
  public function getTitleDetails(): string {
    return get_class(service('authentication'));
  }

  /**
   * --------------------------------------------------------------------------
   * Display.
   * --------------------------------------------------------------------------
   *
   * Returns the data of this collector to be formatted in the toolbar
   *
   * @return string
   */
  public function display(): string {
    $authenticate = service('authentication');

    if ($authenticate->isLoggedIn()) {
      $user = $authenticate->user();
      $groups = model(GroupModel::class)->getGroupsForUser($user->id);
      $roles = model(RoleModel::class)->getRolesForUser($user->id);
      $groupsForUser = implode(', ', array_column($groups, 'name'));
      $rolesForUser = implode(', ', array_column($roles, 'name'));
      $html = '<hr>';
      $html .= '<table><tbody>';
      $html .= '<tr><td colspan="2"><img alt="" style="float:left;margin-right:8px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAtklEQVR4nO2SPQrCQBBGF9KktEjlT51DeAy9hlfwPgmBlOI5tLTRWkXTxebJwgjBZDeQjNjsg2l2dr4Hu2NMQANgARRAJVUCqWb4jTZ329MQFLjJNQSVR/D8teChISg9gkxDkMqHfnMF5qMFjU3K7ZtLZWrhASdABCyBLZB09BPp2TuRO6k9GAMb4NLYmBOwAiZSazn7cJaZuC98BhwZzgGY+gR7xrPzCV4KgtonUMH8TRAwHbwBjaGbQCapfJAAAAAASUVORK5CYII="><h5>Current User</h5></td></tr>';
      $html .= "<tr><td style='width:120px;'>User ID</td><td>#{$user->id}</td></tr>";
      $html .= "<tr><td>Username</td><td>{$user->username}</td></tr>";
      $html .= "<tr><td>Email</td><td>{$user->email}</td></tr>";
      $html .= "<tr><td>Groups</td><td>{$groupsForUser}</td></tr>";
      $html .= "<tr><td>Roles</td><td>{$rolesForUser}</td></tr>";
      $html .= "<tr><td>WhoAmI?</td><td><a href='" . base_url('whoami') . "'>[More Info]</a></td></tr>";
      $html .= '</tbody></table>';
    } else {
      $html = '<p>Not logged in.</p>';
    }

    return $html;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Badge Value.
   * --------------------------------------------------------------------------
   *
   * Gets the "badge" value for the button.
   *
   * @return int|null ID of the current User, or null when not logged in
   */
  public function getBadgeValue(): ?int {
    return service('authentication')->isLoggedIn() ? service('authentication')->id() : null;
  }

  /**
   * --------------------------------------------------------------------------
   * Get Title Details.
   * --------------------------------------------------------------------------
   *
   * Display the icon.
   *
   * Icon from https://icons8.com - 1em package
   *
   * @return string
   */
  public function icon(): string {
    return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAARCAYAAADQWvz5AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAE8SURBVDhPlZO9SgNBFIV39q9POsGAYCNYBCStleAT+AK21tr5EDZWNmnsrWwl4BNIUomCIBYKdlrprN/JzsR1si6TA9+e2Tv3HobMJulQH46NMY+C9YmrRWsIF/ABVYBq2lNPqwwcwATCYZum6RE+a9Y55STLMs1othbFM6w53MSCZBjco/eK9RfM993sQu8QBnh8UFMbBJzj2tdsLYpvWBjgaQtK8jwfYTrRq95TPdCL82hVVTVwy/msD5o6j5a1dsctdQl1EMe7la+ofT3C2XX4hqjfqCzLLUx1zWj2VyRfY1FB9I4x7WlmSbsQhog/QdyW+vzptW7VJXQFDTjNM65rV++/6sE9LAUVRbHN8IOrqUe9ndqEJ1gE8V87xT/du/bUE6U1uAEf5lFNeytJ39gh3Dm09h9woCT5ASFKfPTNNs//AAAAAElFTkSuQmCC';
  }
}
