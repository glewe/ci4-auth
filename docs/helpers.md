# CI4-Auth

## Helper Functions (Auth)

In addition to the helper functions that come with Myth-Auth, CI4-Auth provides these:

**auth_display()**

- Function: Returns a formatted display of the currently logged-in user.
- Parameters: none
- Returns: HTML formatted output

**dnd()**

- Function: Dump'n'Die. Returns a preformatted output of objects and variables.
- Parameters: Variable/Object, Switch to die after output or not
- Returns: Preformatted output

**has_permission()**

- Function: Checks whether the current user has a single permission.
- Parameters: Single permission (name or ID)
- Returns: `true` or `false`

**has_permissions()**

- Function: Checks whether the current user has any of the submitted permissions.
- Parameters: Array of permissions (name or ID)
- Returns: `true` or `false`

**in_groups()**

- Function: Checks whether the current user is in at least one of the passed groups.
- Parameters: Group IDs or names (single item or array of items)
- Returns: `true` or `false`

**in_roles()**

- Function: Checks whether the current user is in at least one of the passed roles.
- Parameters: Role IDs or names (single item or array of items).
- Returns: `true` or `false`

**logged_in()**

- Function: Checks whether a user is logged in.
- Parameters: none
- Returns: `true` or `false`

**user()**

- Function: Returns the User instance for the currently logged-in user.
- Parameters: none
- Returns: User|null

**user_id()**

- Function: Returns the User ID for the currently logged-in user.
- Parameters: none
- Returns: int|null

## Helper Functions (Bootstrap 5)

In order to create Bootstrap objects quicker and to avoid duplicating code in views, these helper functions are
provided:

**bs5_alert()**

- Function: Creates a Bootstrap 5 alert box.
- Parameters: Array with alert box details.
- Returns: HTML

**bs5_cardheader()**

- Function: Creates a Bootstrap card header.
- Parameters: Array with card header details.
- Returns: HTML

**bs5_formrow()**

- Function: Creates a two-column form field div (text, email, select, password).
- Parameters: Array with form field details.
- Returns: HTML

**bs5_modal()**

- Function: Creates a modal dialog.
- Parameters: Array with modal dialog details.
- Returns: HTML

**bs5_searchform()**

- Function: Creates a search form field.
- Parameters: Array with search form details.
- Returns: HTML

**bs5_toast()**

- Function: Creates a toast dialog.
- Parameters: Array with toast dialog details.
- Returns: HTML
- _Note: Javascript is needed on the page where you want to open the toast._
