<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Datatables default configurations
    |--------------------------------------------------------------------------
    |
    | These values are the default configurations for all datatables components
    | in the application.
    |
    */
    'defaults' => [
        'copy' => true,
        'info' => true,
        'filters' => [],
        'paging' => true,
        'ordering' => true,
        'searching' => true,
        'rowIndexes' => true,
        'custom_title' => true,
        'column_visibility' => true,
        'classes' => 'table table-hover table-bordered',
    ],

    /*
    |--------------------------------------------------------------------------
    | Datatables export configurations
    |--------------------------------------------------------------------------
    |
    | These values are the default export function configurations for all datatables 
    | components in this application.
    |
    */
    'exports' => [
        'print' => [
            'enabled' => true,
            'options' => [],
        ],
        'csv' => [
            'enabled' => true,
            'options' => [],
        ],
        'pdf' => [
            'enabled' => true,
            'options' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Datatables export option configurations
    |--------------------------------------------------------------------------
    |
    | These values are the default export options function configurations for all  
    | datatables components in this application.
    |
    */
    'options' => [
        'header' => false,
        'footer' => true,
        'pageSize' => 'A4',
        'orientation' => 'landscape',
        'title' => config('app.name'),
        'exportOptions' => [
            'columns' => ':visible'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Datatables data configurations
    |--------------------------------------------------------------------------
    |
    | These values return table specific configurations. The values are fetched
    | on a table to table basis.
    |
    */
    'data' => [
        'users-table' => [
            'filters' => [
                [ 'column' => 'name', 'type' => 'input' ],
                [ 'column' => 'gender', 'type' => 'select' ],
            ],
            'sorting' => [
                [ 'column' => 'name', 'order' => 'desc' ],
            ],
            'custom' => [
                'message_top' => 'Users',
                'message_bottom' => 'Users'
            ]
        ]
    ] 
];