<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

return [
    /* Main */
    'logo_light' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif'],
    ],
    'logo_dark' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif'],
    ],
    'logo_email' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif'],
    ],
    'favicon' => [
        'whitelisted_file_extensions' => ['png', 'ico', 'gif'],
    ],
    'opengraph' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif'],
    ],

    /* Blog featured images */
    'blog' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif'],
    ],

    /* Payment proofs for offline payments */
    'offline_payment_proofs' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'pdf'],
    ],

    /* QR code logos */
    'qr_code_logo' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif']
    ],

    /* Biolinks themes preview thumbnail */
    'biolinks_themes' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif']
    ],
];
