<p>This is your activation email for your account on <?= site_url() ?>.</p>

<p>To activate your account use the following URL:</p>

<p><a href="<?= site_url('activate-account') . '?token=' . $hash ?>">Activate account</a>.</p>

<br>

<p>If you did not register at this website, you can safely ignore this email.</p>