<!-- Information Alert -->
<?php if (session()->has('message')) :
  echo bs5_alert($data = [
    'type' => 'info',
    'icon' => '',
    'title' => lang('Auth.alert.information'),
    'subject' => '',
    'text' => session('message'),
    'help' => '',
    'dismissible' => true,
  ]);
endif ?>

<!-- Success Alert -->
<?php if (session()->has('success')) :
  echo bs5_alert($data = [
    'type' => 'success',
    'icon' => '',
    'title' => lang('Auth.alert.information'),
    'subject' => '',
    'text' => session('success'),
    'help' => '',
    'dismissible' => true,
  ]);
endif ?>

<!-- Warning Alert -->
<?php if (session()->has('warning')) :
  echo bs5_alert($data = [
    'type' => 'warning',
    'icon' => '',
    'title' => lang('Auth.alert.warning'),
    'subject' => '',
    'text' => session('warning'),
    'help' => '',
    'dismissible' => true,
  ]);
endif ?>

<!-- Single Error Alert -->
<?php if (session()->has('error')) :
  echo bs5_alert($data = [
    'type' => 'danger',
    'icon' => '',
    'title' => lang('Auth.alert.error'),
    'subject' => '',
    'text' => session('error'),
    'help' => '',
    'dismissible' => true,
  ]);
endif ?>

<!-- Multiple Errors Alert -->
<?php if (session()->has('errors')) :
  $text = '<ul>';
  if (is_array(session('errors'))) {
    foreach (session('errors') as $error) {
      $text .= '<li>' . $error . '</li>';
    }
  } else {
    $text .= '<li>' . session('errors') . '</li>';
  }
  $text .= '</ul>';
  echo bs5_alert($data = [
    'type' => 'danger',
    'icon' => '',
    'title' => '',
    'subject' => lang('Auth.alert.error'),
    'text' => $text,
    'help' => '',
    'dismissible' => true,
  ]);
endif ?>
