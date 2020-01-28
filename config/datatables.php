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
        'info' => true,
        'paging' => true,
        'ordering' => true,
        'searching' => true,
        'rowIndexes' => true,
        'customTitle' => true,
        'columnVisibility' => true,
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
            'options' => [
                'extend' => 'print',
                'header' => true,
                'footer' => true,
                'autoPrint' => true,
                'pageSize' => 'A4',
                'orientation' => 'landscape',
                'text' => 'Print',
                'title' => 'This is a title',
                'messageTop' => 'Lorem',
                'messageBottom' => 'Lorem Bottom',
                'logo' => '',
                'exportOptions' => [
                    'column_selector' => ':visible',
                ]
            ],
        ],
        'csv' => [
            'enabled' => true,
            'options' => [
                'extend' => 'csvHtml5',
                'header' => true,
                'footer' => false,
                'text' => 'CSV',
                'filename' => 'Random',
                'extension' => '.csv',
                'exportOptions' => [
                    'column_selector' => ':visible',
                ]
            ],
        ],
        'pdf' => [
            'enabled' => true,
            'options' => [
                'extend' => 'pdfHtml5',
                'header' => true,
                'footer' => true,
                'auto_download' => true,
                'pageSize' => 'A4',
                'orientation' => 'landscape',
                'text' => 'PDF',
                'filename' => 'Random',
                'extension' => '.pdf',
                'title' => 'This is a title',
                'messageTop' => 'Lorem',
                'messageBottom' => 'Lorem Bottom',
                'logo' => '',
                'exportOptions' => [
                    'column_selector' => ':visible',
                ]
            ],
        ],
        'excel' => [
            'enabled' => true,
            'options' => [
                'extend' => 'excelHtml5',
                'header' => true,
                'footer' => true,
                'text' => 'Excel',
                'filename' => 'Random',
                'extension' => '.xlsx',
                'title' => 'This is a title',
                'messageTop' => 'Lorem',
                'messageBottom' => 'Lorem Bottom',
                'logo' => '',
                'exportOptions' => [
                    'column_selector' => ':visible',
                ]
            ],
        ],
        'copy' => [
            'enabled' => true,
            'options' => [
                'extend' => 'copyHtml5',
                'header' => true,
                'footer' => false,
                'text' => 'Copy',
                'title' => 'This is a title',
                'messageTop' => 'Lorem',
                'messageBottom' => 'Lorem Bottom',
                'exportOptions' => [
                    'column_selector' => ':visible',
                ]
            ],
        ],
        'json' => [
            'enabled' => true,
            'options' => [
                'header' => true,
                'footer' => true,
                'text' => 'JSON',
                'filename' => 'Random',
                'extension' => '.json',
                'exportOptions' => [
                ]
            ],
        ]
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
                [ 'column' => 'name', 'order' => 'asc' ],
                [ 'column' => 'gender', 'order' => 'desc' ],
            ],
        ]
    ] 
];