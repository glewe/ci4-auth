<?php

/**
 * ============================================================================
 * Bootstrap 5 Helper Functions
 * ============================================================================
 *
 * These function require that you load the Bootstrap 5 CSS and JS files.
 *
 */

//-----------------------------------------------------------------------------
if (!function_exists('bs5_alert')) {
  /**
   * Creates a Bootstrap 5 alert box.
   *
   * @param array $data The data for the alert box
   *
   * @return   string
   */
  function bs5_alert($data) {

    $type = $data[ 'type' ];
    $icon = $data[ 'icon' ];
    $title = $data[ 'title' ];
    $subject = $data[ 'subject' ];
    $text = $data[ 'text' ];
    $help = $data[ 'help' ];
    $dismissible = $data[ 'dismissible' ];

    $alert_icon = '';
    switch ($icon) {
      case 'info':
        $alert_icon = '<svg class="bi flex-shrink-0 me-2 float-start" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>';
        break;
      case 'exclamation':
        $alert_icon = '<svg class="bi flex-shrink-0 me-2 float-start" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>';
        break;
      case 'check':
        $alert_icon = '<svg class="bi flex-shrink-0 me-2 float-start" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>';
        break;
    }

    $alert_dismissible = '';
    if ($dismissible) $alert_dismissible = 'alert-dismissible fade show ';

    $asubject = "";
    if (strlen($subject)) $asubject = "<p><strong>" . $subject . "</strong><br>";

    $html = '
      <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </symbol>
      </svg>
      <div class="alert ' . $alert_dismissible . ' alert-' . $type . '" role="alert">';

    $html .= "\n" . $alert_icon;

    if ($dismissible) {
      $html .= '
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close" title="' . lang('Auth.btn.close') . '"></button>';
    }

    if (strlen($title)) {
      $html .= '
        <h4 class="alert-heading">' . $title . '</h4>
        <hr>';
    }

    if (strlen($subject)) {
      $html .= '<div class="fw-bold">' . $subject . '</div>';
    }

    $html .= '<div>' . $text . '</div>';

    if (strlen($help)) {
      $html .= '
        <hr>
        <p class="fs-6 fst-italic fw-lighter">' . $help . '</p>';
    }

    $html .= '</div>';

    return $html;
  }
}

//-----------------------------------------------------------------------------
if (!function_exists('bs5_cardheader')) {
  /**
   * Creates a card header for the main card on a page.
   *
   * @param array $data The data for the card header
   *
   * @return   string
   */
  function bs5_cardheader($data) {
    $html = '
      <!-- Card Header: ' . $data[ 'title' ] . ' -->
      <div class="card-header">
        <i class="' . $data[ 'icon' ] . ' me-2"></i><strong>' . $data[ 'title' ] . '</strong>
        <a href="' . $data[ 'help' ] . '" target="_blank" class="float-end card-header-help-icon" title="' . lang('Auth.getHelpForPage') . '"><i class="bi-question-circle"></i></a>
      </div>';

    return $html;
  }
}

//-----------------------------------------------------------------------------
if (!function_exists('bs5_formrow')) {
  /**
   * Creates a form row.
   *
   * @param array $data The data for the form row
   *
   * @return   string
   */
  function bs5_formrow($data) {
    $html = '
      <!-- Form Row  -->
      <div class="row"><label class="col">Form Row: data missing</label></div>
      <hr class="my-4">
      ';

    if (!isset($data[ 'disabled' ])) $data[ 'disabled' ] = false;

    switch ($data[ 'type' ]) {

      case 'check':
      case 'checkbox':
        //
        // Checkbox
        //
        // $data[
        //    'desc'      => Description of the element on the form
        //    'disabled'  => true or false
        //    'errors'    => Possible errors from the last post
        //    'mandatory' => true or false. True will add a red star to the title on the form.
        //    'name'      => Name of the element to access it by in the controller
        //    'title'     => Title of the element on the form
        //    'type'      => 'text' or 'email'
        //    'value'     => Value to put into the field
        // ]
        //
        $html = '
          <!-- Form Row: ' . $data[ 'name' ] . ' -->
          <div class="row">
            <label class="col" for="' . $data[ 'name' ] . '">
              <strong>' . ($data[ 'mandatory' ] ? '<i class="text-danger">* </i>' : '') . $data[ 'title' ] . '</strong><br>
              <span>' . $data[ 'desc' ] . '</span>
            </label>
            <div class="col">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="' . $data[ 'name' ] . '" name="' . $data[ 'name' ] . '" value="' . $data[ 'name' ] . '"' . ($data[ 'disabled' ] ? ' disabled' : '') . '>
                <label class="form-check-label" for="' . $data[ 'name' ] . '">' . $data[ 'title' ] . '</label>
              </div>
              <div class="invalid-feedback">' . $data[ 'errors' ] . '</div>
            </div>
          </div>
          <hr class="my-4">
          ';
        break;

      case 'email':
      case 'text':
        //
        // Text field, Email field
        //
        // $data[
        //    'desc'      => Description of the element on the form
        //    'disabled'  => true or false
        //    'errors'    => Possible errors from the last post
        //    'mandatory' => true or false. True will add a red star to the title on the form.
        //    'name'      => Name of the element to access it by in the controller
        //    'title'     => Title of the element on the form
        //    'type'      => 'text' or 'email'
        //    'value'     => Value to put into the field
        // ]
        //
        $html = '
          <!-- Form Row: ' . $data[ 'name' ] . ' -->
          <div class="row">
            <label class="col" for="' . $data[ 'name' ] . '">
              <strong>' . ($data[ 'mandatory' ] ? '<i class="text-danger">* </i>' : '') . $data[ 'title' ] . '</strong><br>
              <span>' . $data[ 'desc' ] . '</span>
            </label>
            <div class="col">
              <input type="' . $data[ 'type' ] . '" class="form-control' . ($data[ 'errors' ] ? ' is-invalid' : '') . '" name="' . $data[ 'name' ] . '" value="' . $data[ 'value' ] . '"' . ($data[ 'disabled' ] ? ' disabled' : '') . '>
              <div class="invalid-feedback">' . $data[ 'errors' ] . '</div>
            </div>
          </div>
          <hr class="my-4">
          ';
        break;

      case 'select':
        //
        // Select List
        //
        // $data[
        //    'desc'      => Description of the element on the form
        //    'disabled'  => true or false
        //    'errors'    => Possible errors from the last post
        //    'mandatory' => true or false. True will add a red star to the title on the form.
        //    'name'      => Name of the element to access it by in the controller
        //    'size'      => How many items to show in a multi select list
        //    'subtype'   => 'single' or 'multi'
        //    'title'     => Title of the element on the form
        //    'type'      => 'select'
        //    'items' => [
        //        'selected' => true or false
        //        'title'    => Title to show in the list box
        //        'value'    => Value of the item to post to the controller
        //    ]
        // ]
        //
        $multiple = '';
        $size = '';
        if (!isset($data[ 'items' ])) $data[ 'items' ] = array();

        if ($data[ 'subtype' ] == 'multi') {

          $multiple = ' multiple';
          if (isset($data[ 'size' ])) $size = ' size="' . $data[ 'size' ] . '"';
          else $size = ' size="8"';
          $data[ 'name' ] .= "[]";
        }

        $html = '
          <!-- Form Row: ' . $data[ 'name' ] . ' -->
          <div class="row">
            <label class="col">
              <strong>' . ($data[ 'mandatory' ] ? '<i class="text-danger">* </i>' : '') . $data[ 'title' ] . '</strong><br>
              <span>' . $data[ 'desc' ] . '</span>
            </label>
            <div class="col">
              <select class="form-select"' . $multiple . $size . ' name="' . $data[ 'name' ] . '"' . ($data[ 'disabled' ] ? ' disabled' : '') . '>';

        foreach ($data[ 'items' ] as $item) {
          if ($item[ 'selected' ]) $selected = ' selected';
          else $selected = '';

          $html .= '
                <option' . $selected . ' value="' . $item[ 'value' ] . '">' . $item[ 'title' ] . '</option>';
        }

        $html .= '
              </select>
              <div class="invalid-feedback">' . $data[ 'errors' ] . '</div>
          </div>
          </div>
          <hr class="my-4">
          ';
        break;

      case 'password':
        //
        // Password field
        //
        // $data[
        //    'desc'      => Description of the element on the form
        //    'disabled'  => true or false
        //    'errors'    => Possible errors from the last post
        //    'mandatory' => true or false. True will add a red star to the title on the form.
        //    'name'      => Name of the element to access it by in the controller
        //    'title'     => Title of the element on the form
        //    'type'      => 'password'
        // ]
        //
        $html = '
          <!-- Form Row: ' . $data[ 'name' ] . ' -->
          <div class="row">
            <label for="' . $data[ 'name' ] . '" class="col">
              <strong>' . ($data[ 'mandatory' ] ? '<i class="text-danger">* </i>' : '') . $data[ 'title' ] . '</strong><br>
              <span>' . $data[ 'desc' ] . '</span>
            </label>
            <div class="col">
              <input 
                type="' . $data[ 'type' ] . '" 
                class="form-control' . ($data[ 'errors' ] ? ' is-invalid' : '') . '" 
                name="' . $data[ 'name' ] . '" 
                autocomplete=off 
                readonly 
                onfocus="this.removeAttribute(\'readonly\');" 
                onblur="this.setAttribute(\'readonly\',\'\');"' . ($data[ 'disabled' ] ? ' disabled' : '') . '
              >
              <div class="invalid-feedback">' . $data[ 'errors' ] . '</div>
            </div>
          </div>
          <hr class="my-4">
          ';
        break;

      case 'switch':
        /**
         * Switch
         */
        $html = '
          <!-- Form Row: ' . $data[ 'name' ] . ' -->
          <div class="row">
            <label class="col" for="' . $data[ 'name' ] . '">
              <strong>' . ($data[ 'mandatory' ] ? '<i class="text-danger">* </i>' : '') . $data[ 'title' ] . '</strong><br>
              <span>' . $data[ 'desc' ] . '</span>
            </label>
              <div class="col">
                <div class="form-check form-switch">
                  <input 
                      type="checkbox" 
                      class="form-check-input" 
                      id="' . $data[ 'name' ] . '" 
                      name="swi_' . $data[ 'name' ] . '" 
                      value="swi_' . $data[ 'name' ] . '"' . ((intval($data[ 'value' ])) ? " checked" : "") . ($data[ 'disabled' ] ? ' disabled' : '') . '
                  >
                  <div class="invalid-feedback">' . $data[ 'errors' ] . '</div>
                </div>
              </div>
          </div>
          <hr class="my-4">
          ';
        break;
    }

    return $html;
  }
}

//-----------------------------------------------------------------------------
if (!function_exists('bs5_modal')) {
  /**
   * Creates a modal dialog
   *
   * @param array $data The data for the modal dialog
   *
   * @return   string
   */
  function bs5_modal($data) {
    $id = $data[ 'id' ];
    $header = $data[ 'header' ];
    $headerColor = $data[ 'header_color' ];
    $body = $data[ 'body' ];
    $btnColor = $data[ 'btn_color' ];
    $btnName = $data[ 'btn_name' ];
    $btnText = $data[ 'btn_text' ];

    if ($headerColor == 'default') $headerColor = '';
    else $headerColor = 'btn-' . $headerColor;

    $html = '
      <!-- Modal: ' . $id . ' -->
      <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="' . $id . 'Label" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header ' . $headerColor . '">
            <h5 class="modal-title" id="' . $id . 'Label">' . $header . '</h5>
            <button type="button" class="' . $headerColor . ' btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              ' . $body . '
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-' . $btnColor . '" name="' . $btnName . '">' . $btnText . '</button>
          </div>
          </div>
        </div>
      </div>
      ';

    return $html;
  }
}

//-----------------------------------------------------------------------------
if (!function_exists('bs5_searchform')) {
  /**
   * Creates a search form to ber used on list pages.
   *
   * @param string $action Form action
   * @param string $search Search string
   *
   * @return   string
   */
  function bs5_searchform($action, $search) {
    helper('form');

    $value = '';
    $disabled = ' disabled';

    if ($search) {
      $value = $search;
      $disabled = '';
    }

    $html = '
      <!-- Search Form -->
      ' . form_open($action, [ 'csrf_id' => 'csrfForm', 'id' => 'data-form', 'class' => 'input-group form-validate', 'name' => 'form_search' ]) . '
          <input type="text" class="form-control" name="search" placeholder="' . lang('Auth.btn.search') . '..." aria-label="search" aria-describedby="btn_search" value="' . $value . '">
          <button class="btn btn-outline-secondary" type="submit" name="btn_reset" id="btn_reset" title="' . lang('Auth.btn.reset') . '..."' . $disabled . '><i class="bi-backspace-fill text-danger"></i></button>
          <button class="btn btn-outline-secondary" type="submit" name="btn_search" id="btn_search" title="' . lang('Auth.btn.search') . '..."><i class="bi-search text-primary"></i></button>' .
      form_close();

    return $html;
  }
}

//-----------------------------------------------------------------------------
if (!function_exists('bs5_toast')) {
  /**
   * Create a Bootstrap toast.
   *
   * The div block must be put on the page but will stay hidden until calling
   * $('.toast').toast('show');
   *
   * Data array = [
   *    time          Time stamp, right header
   *    body          The text
   *    style         Bootstrap coloring style
   *    delay         Milliseconds until disappear
   *    customStyle   Allows to define a custom style
   * ]
   *
   * @param array $data The data for the toast
   *
   * @return   string                 HTML snippet
   */
  function bs5_toast($data) {
    $title = $data[ 'title' ];
    $time = $data[ 'time' ];
    $body = $data[ 'body' ];
    $style = $data[ 'style' ];
    $delay = $data[ 'delay' ];
    $customStyle = $data[ 'custom_style' ];

    $bgcolor = "bg-" . $style;
    $txtcolor = "text-light";
    if ($style == "light" || $style == "warning") $txtcolor = "text-dark";

    //
    // Add an icon here if you like
    //
    $icon = '';
    // $icon = '<i class="bi-exclamation-diamond me-2"></i>';

    //
    // Feel free to add your own custom style settings here
    //
    if ($customStyle) {
      switch ($customStyle) {
        case 'custom1':
          $bgcolor = 'custom1-bg-color';
          $txtcolor = "custom1-text-color";
          $icon = '<img src="images/custom/custom1.png" style="width:auto;height:16px;padding-right:8px;" alt="" />';
          $title = '';
          break;
      }
    }

    $html = '
      <div style="min-width:300px;" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="' . $delay . '" data-bs-animation="true" data-bs-autohide="true">
        <div class="toast-header ' . $bgcolor . ' ' . $txtcolor . '">
          ' . $icon . '
          <strong class="me-auto">' . $title . '</strong>
          <small class="' . $txtcolor . '">' . $time . '</small>
          <button type="button" class="btn-close ' . $txtcolor . '" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          ' . $body . '
        </div>
      </div>
      ';

    return $html;
  }
}
