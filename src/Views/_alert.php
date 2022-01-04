<!-- Information Alert -->
<?php if (session()->has('message')) :
    echo bs5_alert($data = [
        'type' => 'info',
        'icon'  => '',
        'title' => '',
        'subject' => lang('Auth.alert.information'),
        'text' => session('message'),
        'help' => '',
        'dismissable' => true,
    ]);
endif ?>

<!-- Success Alert -->
<?php if (session()->has('success')) :
    echo bs5_alert($data = [
        'type' => 'success',
        'icon'  => '',
        'title' => '',
        'subject' => lang('Auth.alert.information'),
        'text' => session('success'),
        'help' => '',
        'dismissable' => true,
    ]);
endif ?>

<!-- Warning Alert -->
<?php if (session()->has('warning')) :
    echo bs5_alert($data = [
        'type' => 'warning',
        'icon'  => '',
        'title' => '',
        'subject' => lang('Auth.alert.warning'),
        'text' => session('warning'),
        'help' => '',
        'dismissable' => true,
    ]);
endif ?>

<!-- Single Error Alert -->
<?php if (session()->has('error')) :
    echo bs5_alert($data = [
        'type' => 'danger',
        'icon'  => '',
        'title' => '',
        'subject' => lang('Auth.alert.error'),
        'text' => session('error'),
        'help' => '',
        'dismissable' => true,
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
        'icon'  => '',
        'title' => '',
        'subject' => lang('Auth.alert.error'),
        'text' => $text,
        'help' => '',
        'dismissable' => true,
    ]);
endif ?>