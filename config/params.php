<?php

return [
    'infoEmail' => env('INFO_EMAIL', ''),
    'user.passwordResetTokenExpire' => 60*60*2,
    'allowUserRegistrations' => env('ALLOW_USER_REGISTRATIONS', 0),
    'reCaptcha.siteKey' => env('RECAPTCHA_SITE_KEY', ''),
    'reCaptcha.secretKey' => env('RECAPTCHA_SECRET_KEY', ''),
    'SSHSavePerDay' => env('SSH_SAVE_PER_DAY', 7),
    'SSHSavePerMonth' => env('SSH_SAVE_PER_MONTH', 30),
];
