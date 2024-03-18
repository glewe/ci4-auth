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
if (!function_exists('bs5_alert_small')) {
  /**
   * Creates a Bootstrap 5 alert box.
   *
   * @param array $data The data for the alert box
   *
   * @return   string
   */
  function bs5_alert_small($data) {

    $html = '
      <div class="alert alert-%type% alert-border-left %dismissible%" role="alert">
        <i class="%icon% me-2 align-middle fs-16"></i><strong>%title%</strong> %subject%
        %text%
        %help%
        %button%
      </div>';

    $type = $data['type'];
    $icon = $data['icon'];
    $title = $data['title'];
    $subject = $data['subject'];
    $text = $data['text'];
    $help = $data['help'];
    $dismissible = $data['dismissible'];

    $alert_icon = '';
    switch ($type) {
      case 'success':
      case 'check':
        $alert_icon = 'bi bi-check-circle';
        break;
      case 'warning':
      case 'exclamation':
        $alert_icon = 'bi bi-exclamation-triangle';
        break;
      case 'danger':
      case 'error':
        $alert_icon = 'bi bi-radioactive';
        break;
      case 'info':
        $alert_icon = 'bi bi-info-circle';
        break;
    }

    if (strlen($icon)) {
      $alert_icon = $icon;
    }

    $html = str_replace("%type%", $type, $html);
    $html = str_replace("%icon%", $alert_icon, $html);

    if ($dismissible) {
      $html = str_replace("%dismissible%", 'alert-dismissible fade show', $html);
      $html = str_replace("%button%", '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>', $html);
    } else {
      $html = str_replace("%dismissible%", '', $html);
      $html = str_replace("%button%", '', $html);
    }

    if (strlen($title)) {
      $html = str_replace("%title%", $title, $html);
    } else {
      $html = str_replace("%title%", '', $html);
    }

    if (strlen($subject)) {
      $html = str_replace("%subject%", "- " . $subject, $html);
    } else {
      $html = str_replace("%subject%", '', $html);
    }

    if (strlen($text)) {
      $html = str_replace("%text%", '<p class="mt-2">' . $text . "</p>", $html);
    } else {
      $html = str_replace("%text%", '', $html);
    }

    if (strlen($help)) {
      $html = str_replace("%help%", '<hr><p class="fs-6 fst-italic fw-lighter">' . $help . '</p>', $html);
    } else {
      $html = str_replace("%help%", '', $html);
    }

    return $html;
  }
}

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

    $type = $data['type'];
    $icon = $data['icon'];
    $title = $data['title'];
    $subject = $data['subject'];
    $text = $data['text'];
    $help = $data['help'];
    $dismissible = $data['dismissible'];

    $alert_icon = '';
    switch ($type) {
      case 'info':
        $alert_icon = '<svg class="bi flex-shrink-0 me-2 float-start" width="20" height="20" role="img" aria-label="Info:"><use xlink:href="#info-circle"/></svg>';
        break;
      case 'warning':
        $alert_icon = '<svg class="bi flex-shrink-0 me-2 float-start" width="20" height="20" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle"/></svg>';
        break;
      case 'success':
        $alert_icon = '<svg class="bi flex-shrink-0 me-2 float-start" width="20" height="20" role="img" aria-label="Success:"><use xlink:href="#check-circle"/></svg>';
        break;
      case 'error':
      case 'danger':
        $alert_icon = '<svg class="bi flex-shrink-0 me-2 float-start" width="20" height="20" role="img" aria-label="Danger:"><use xlink:href="#exclamation-octagon"/></svg>';
        break;
    }

    $alert_dismissible = '';
    if ($dismissible) $alert_dismissible = 'alert-dismissible fade show ';

    $asubject = "";
    if (strlen($subject)) $asubject = "<p><strong>" . $subject . "</strong><br>";

    $html = '
      <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
          <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
        </symbol>
        <symbol id="info-circle" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
          <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
        </symbol>
        <symbol id="exclamation-triangle" fill="currentColor" viewBox="0 0 16 16">
          <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
          <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
        </symbol>
        <symbol id="exclamation-octagon" fill="currentColor" viewBox="0 0 16 16">
          <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
          <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
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
        <h5 class="alert-heading">' . $title . '</h5>
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
        <!-- Card Header: ' . $data['title'] . ' -->
        <div class="card-header">
            <i class="' . $data['icon'] . ' me-2"></i><strong>' . $data['title'] . '</strong>
            <a href="' . $data['help'] . '" target="_blank" class="float-end card-header-help-icon" title="' . lang('Auth.getHelpForPage') . '"><i class="bi-question-circle"></i></a>
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
        <div class="row"><label class="col">Form Row: data array incomplete or wrong</label></div>
        <hr class="my-4">
        ';

    if (!isset($data['disabled'])) $data['disabled'] = false;

    switch ($data['type']) {

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
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <label class="col" for="' . $data['name'] . '">
              <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
              <span>' . $data['desc'] . '</span>
            </label>
            <div class="col">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="' . $data['name'] . '" name="' . $data['name'] . '" value="' . $data['name'] . '"' . ($data['disabled'] ? ' disabled' : '') . '>
                <label class="form-check-label" for="' . $data['name'] . '">' . $data['title'] . '</label>
              </div>
              <div class="invalid-feedback">' . $data['errors'] . '</div>
            </div>
          </div>
          ';
        break;

      case 'ckeditor':
        //
        // CKEditor
        // $data[
        //    'desc'      => Description of the element on the form
        //    'disabled'  => true or false
        //    'errors'    => Possible errors from the last post
        //    'mandatory' => true or false. True will add a red star to the title on the form.
        //    'name'      => Name of the element to access it by in the controller
        //    'title'     => Title of the element on the form
        //    'type'      => 'textarea'
        //    'rows'      => Number of rows
        //    'value'    => array( [ 'label' => 'Label to show', 'value' => value to save, 'checked' => true/false ], ... )
        // ]
        //
        $html = '
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <div class="form-group">
              <label for="' . $data['name'] . '" class="mb-1">
                <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
                <span>' . $data['desc'] . '</span>
              </label>
              <textarea id="' . $data['name'] . '" class="' . ($data['errors'] ? ' is-invalid' : '') . '" name="' . $data['name'] . '" rows="' . $data['rows'] . '" ' . ($data['disabled'] ? ' disabled' : '') . '>' . $data['value'] . '</textarea>
              <script>
              CKEDITOR.replace("' . $data['name'] . '", { 
                // extraAllowedContent: "i[*]; span[*]" 
              });
              </script>
              <div class="invalid-feedback">' . $data['errors'] . '</div>
            </div>
          </div>
          ';
        break;

      case 'email':
      case 'text':
        //
        // Text field, Email field
        //
        // $data[
        //    'desc'        => Description of the element on the form
        //    'disabled'    => true or false
        //    'errors'      => Possible errors from the last post
        //    'mandatory'   => true or false. True will add a red star to the title on the form.
        //    'name'        => Name of the element to access it by in the controller
        //    'title'       => Title of the element on the form
        //    'type'        => 'text' or 'email'
        //    'value'       => Value to put into the field
        //    'placeholder' => Placeholder
        // ]
        //
        if (!array_key_exists('placeholder', $data)) $data['placeholder'] = '';
        $html = '
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <label class="col" for="' . $data['name'] . '">
              <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
              <span>' . $data['desc'] . '</span>
            </label>
            <div class="col">
              <input type="' . $data['type'] . '" class="form-control' . ($data['errors'] ? ' is-invalid' : '') . '" name="' . $data['name'] . '" value="' . $data['value'] . '" placeholder="' . $data['placeholder'] . '"' . ($data['disabled'] ? ' disabled' : '') . '>
              <div class="invalid-feedback">' . $data['errors'] . '</div>
            </div>
          </div>
          ';
        break;

      case 'number':
        //
        // Number text field
        //
        // $data[
        //    'desc'        => Description of the element on the form
        //    'disabled'    => true or false
        //    'errors'      => Possible errors from the last post
        //    'mandatory'   => true or false. True will add a red star to the title on the form.
        //    'name'        => Name of the element to access it by in the controller
        //    'title'       => Title of the element on the form
        //    'type'        => 'text' or 'email'
        //    'value'       => Value to put into the field
        //    'placeholder' => Placeholder
        //    'min'         => Minimum value
        //    'max'         => Maximum value
        // ]
        //
        if (!array_key_exists('placeholder', $data)) $data['placeholder'] = '';
        $html = '
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <label class="col" for="' . $data['name'] . '">
              <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
              <span>' . $data['desc'] . '</span>
            </label>
            <div class="col">
              <input type="' . $data['type'] . '" class="form-control' . ($data['errors'] ? ' is-invalid' : '') . '" name="' . $data['name'] . '" min="' . $data['min'] . '" max="' . $data['max'] . '" value="' . $data['value'] . '" placeholder="' . $data['placeholder'] . '"' . ($data['disabled'] ? ' disabled' : '') . '>
              <div class="invalid-feedback">' . $data['errors'] . '</div>
            </div>
          </div>
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
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <label for="' . $data['name'] . '" class="col">
              <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
              <span>' . $data['desc'] . '</span>
            </label>
            <div class="col">
              <input 
                type="' . $data['type'] . '" 
                class="form-control' . ($data['errors'] ? ' is-invalid' : '') . '" 
                name="' . $data['name'] . '" 
                autocomplete=off 
                readonly 
                onfocus="this.removeAttribute(\'readonly\');" 
                onblur="this.setAttribute(\'readonly\',\'\');"' . ($data['disabled'] ? ' disabled' : '') . '
              >
                <div class="invalid-feedback">' . $data['errors'] . '</div>
            </div>
          </div>
          ';
        break;

      case 'radio':
        //
        // Radio Buttons
        //
        // $data[
        //    'desc'      => Description of the element on the form
        //    'disabled'  => true or false
        //    'errors'    => Possible errors from the last post
        //    'mandatory' => true or false. True will add a red star to the title on the form.
        //    'name'      => Name of the element to access it by in the controller
        //    'title'     => Title of the element on the form
        //    'type'      => 'text' or 'email'
        //    'value'     => array( [ 'label' => 'Label to show', 'value' => value to save, 'checked' => true/false ], ... )
        // ]
        //
        $html = '
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <label class="col" for="' . $data['name'] . '">
              <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
              <span>' . $data['desc'] . '</span>
            </label>
            <div class="col">';
        $i = 1;
        foreach ($data['value'] as $val) {
          $html .= '<div class="form-check">
                <input class="form-check-input" type="radio" name="' . $data['name'] . '" id="' . $data['name'] . $i . '" value="' . $val['value'] . '" ' . ($val['checked'] ? 'checked' : '') . '>
                <label class="form-check-label" for="' . $data['name'] . $i . '">' . $val['label'] . '</label>
              </div>';
          $i++;
        }
        $html .= '<div class="invalid-feedback">' . $data['errors'] . '</div>
            </div>
          </div>
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
        if (!isset($data['items'])) $data['items'] = array();

        if ($data['subtype'] == 'multi') {

          $multiple = ' multiple';
          if (isset($data['size'])) $size = ' size="' . $data['size'] . '"';
          else $size = ' size="8"';
          $data['name'] .= "[]";
        }

        $html = '
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <label class="col">
              <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
              <span>' . $data['desc'] . '</span>
            </label>
            <div class="col">
              <select class="form-select"' . $multiple . $size . ' name="' . $data['name'] . '"' . ($data['disabled'] ? ' disabled' : '') . '>';

        foreach ($data['items'] as $item) {
          if ($item['selected']) $selected = ' selected';
          else $selected = '';

          $html .= '
                <option' . $selected . ' value="' . $item['value'] . '">' . $item['title'] . '</option>';
        }

        $html .= '
              </select>
              <div class="invalid-feedback">' . $data['errors'] . '</div>
          </div>
          </div>
          ';
        break;

      case 'switch':
        //
        // Switch
        // $data[
        //    'desc'      => Description of the element on the form
        //    'disabled'  => true or false
        //    'errors'    => Possible errors from the last post
        //    'mandatory' => true or false. True will add a red star to the title on the form.
        //    'name'      => Name of the element to access it by in the controller
        //    'title'     => Title of the element on the form
        //    'type'      => 'text' or 'email'
        //    'value'    => array( [ 'label' => 'Label to show', 'value' => value to save, 'checked' => true/false ], ... )
        // ]
        //
        $html = '
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <label class="col" for="' . $data['name'] . '">
              <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
              <span>' . $data['desc'] . '</span>
            </label>
              <div class="col">
                <div class="form-check form-switch">
                  <input 
                    type="checkbox" 
                    class="form-check-input" 
                    id="' . $data['name'] . '" 
                    name="' . $data['name'] . '" 
                    value="' . $data['name'] . '"' . ((intval($data['value'])) ? " checked" : "") . ($data['disabled'] ? ' disabled' : '') . '
                  >
                  <label class="form-check-label" for="' . $data['name'] . '">' . $data['title'] . '</label>
                  <div class="invalid-feedback">' . $data['errors'] . '</div>
                </div>
              </div>
          </div>
          ';
        break;

      case 'textarea':
        //
        // Text Area
        // $data[
        //    'desc'      => Description of the element on the form
        //    'disabled'  => true or false
        //    'errors'    => Possible errors from the last post
        //    'mandatory' => true or false. True will add a red star to the title on the form.
        //    'name'      => Name of the element to access it by in the controller
        //    'title'     => Title of the element on the form
        //    'type'      => 'textarea'
        //    'rows'      => Number of rows
        //    'value'    => array( [ 'label' => 'Label to show', 'value' => value to save, 'checked' => true/false ], ... )
        // ]
        //
        $html = '
          <!-- Form Row: ' . $data['name'] . ' -->
          <div class="row">
            <label class="col" for="' . $data['name'] . '">
              <strong>' . ($data['mandatory'] ? '<i class="text-danger">* </i>' : '') . $data['title'] . '</strong><br>
              <span>' . $data['desc'] . '</span>
            </label>
            <div class="col">
              <textarea class="form-control' . ($data['errors'] ? ' is-invalid' : '') . '" name="' . $data['name'] . '" rows="' . $data['rows'] . '" ' . ($data['disabled'] ? ' disabled' : '') . '>' . $data['value'] . '</textarea>
              <div class="invalid-feedback">' . $data['errors'] . '</div>
            </div>
          </div>
          ';
        break;
    }

    if (!isset($data['ruler']) || $data['ruler']) {
      $html .= '<hr class="my-4">';
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
    $id = $data['id'];
    $header = $data['header'];
    $headerColor = $data['header_color'];
    $body = $data['body'];
    $btnColor = $data['btn_color'];
    $btnName = $data['btn_name'];
    $btnText = $data['btn_text'];

    if ($headerColor == 'default' or !strlen($headerColor)) {
      $headerBgColor = '';
      $headerTextColor = '';
    } else {
      $headerBgColor = 'bg-' . $headerColor;
      $headerTextColor = 'text-bg-' . $headerColor;
    }

    $html = '
      <!-- Modal: ' . $id . ' -->
      <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="' . $id . 'Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
          <div class="modal-header ' . $headerBgColor . ' ' . $headerTextColor . '">
            <h5 class="modal-title" id="' . $id . 'Label">' . $header . '</h5>
            <button type="button" class="' . $headerBgColor . ' ' . $headerTextColor . ' btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-start">
              ' . $body . '
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-' . $btnColor . '" name="' . $btnName . '">' . $btnText . '</button>
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
      <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="' . lang('Auth.btn.search') . '..." aria-label="search" aria-describedby="btn_search" value="' . $value . '">
        <button class="input-group-text" type="submit" name="btn_reset" id="btn_reset" title="' . lang('Auth.btn.reset') . '..."' . $disabled . '><i class="bi-backspace-fill text-danger"></i></button>
        <button class="input-group-text" type="submit" name="btn_search" id="btn_search" title="' . lang('Auth.btn.search') . '..."><i class="bi-search text-primary"></i></button>
      </div>' .
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
    $title = $data['title'];
    $time = $data['time'];
    $body = $data['body'];
    $style = $data['style'];
    $delay = $data['delay'];
    $customStyle = $data['custom_style'];

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
