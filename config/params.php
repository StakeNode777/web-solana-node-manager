<?php

return [
    'infoEmail' => env('INFO_EMAIL', ''),
    'user.passwordResetTokenExpire' => 60*60*2,
    'reCaptcha.siteKey' => env('RECAPTCHA_SITE_KEY', ''),
    'reCaptcha.secretKey' => env('RECAPTCHA_SECRET_KEY', ''),
];
