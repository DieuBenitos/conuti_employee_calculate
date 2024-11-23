<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'datatables.net-bs5' => [
        'version' => '2.1.8',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'datatables.net' => [
        'version' => '2.1.8',
    ],
    'datatables.net-bs5/css/dataTables.bootstrap5.min.css' => [
        'version' => '2.1.8',
        'type' => 'css',
    ],
    'datatables.net-autofill-bs5' => [
        'version' => '2.7.0',
    ],
    'datatables.net-autofill' => [
        'version' => '2.7.0',
    ],
    'datatables.net-autofill-bs5/css/autoFill.bootstrap5.min.css' => [
        'version' => '2.7.0',
        'type' => 'css',
    ],
    'datatables.net-buttons-bs5' => [
        'version' => '3.2.0',
    ],
    'datatables.net-buttons' => [
        'version' => '3.2.0',
    ],
    'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css' => [
        'version' => '3.2.0',
        'type' => 'css',
    ],
    'datatables.net-buttons/js/buttons.html5.mjs' => [
        'version' => '3.2.0',
    ],
    'datatables.net-fixedheader-bs5' => [
        'version' => '4.0.1',
    ],
    'datatables.net-fixedheader' => [
        'version' => '4.0.1',
    ],
    'datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css' => [
        'version' => '4.0.1',
        'type' => 'css',
    ],
    'datatables.net-keytable-bs5' => [
        'version' => '2.12.1',
    ],
    'datatables.net-keytable' => [
        'version' => '2.12.1',
    ],
    'datatables.net-keytable-bs5/css/keyTable.bootstrap5.min.css' => [
        'version' => '2.12.1',
        'type' => 'css',
    ],
    'datatables.net-responsive-bs5' => [
        'version' => '3.0.3',
    ],
    'datatables.net-responsive' => [
        'version' => '3.0.3',
    ],
    'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css' => [
        'version' => '3.0.3',
        'type' => 'css',
    ],
    'datatables.net-rowgroup-bs5' => [
        'version' => '1.5.1',
    ],
    'datatables.net-rowgroup' => [
        'version' => '1.5.1',
    ],
    'datatables.net-rowgroup-bs5/css/rowGroup.bootstrap5.min.css' => [
        'version' => '1.5.1',
        'type' => 'css',
    ],
    'pdfmake' => [
        'version' => '0.2.15',
    ],
    'jquery-ui' => [
        'version' => '1.14.1',
    ],
    'jquery-ui-bundle' => [
        'version' => '1.12.1-migrate',
    ],
    'highcharts' => [
        'version' => '11.4.8',
    ],
];
