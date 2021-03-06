<?php

/*
|--------------------------------------------------------------------------
| Datatables default configurations
|--------------------------------------------------------------------------
|
| These are the default configurations for all datatables components in your 
| application.
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | General table configurations
    |--------------------------------------------------------------------------
    |
    | These values are the default general configurations for all datatables 
    | components in the application.
    |
    */
    'config' => [
        'info' => true,
        'paging' => true,
        'ordering' => true,
        'searching' => true,
        'rowIndexes' => true,
        'customTitle' => false,
        'columnVisibility' => false,

        /*
        |--------------------------------------------------------------------------
        | Export configurations
        |--------------------------------------------------------------------------
        |
        | These are the default export configurations for all datatables components 
        | in the application.
        |
        */
        'exports' => [
            /*
            |--------------------------------------------------------------------------
            | Print Configurations
            |--------------------------------------------------------------------------
            |
            | These are the configurations that enable printing of datatables
            |
            */
            'print' => [
                'enabled' => false,
                'options' => [
                    'header' => true,
                    'footer' => true,
                    'autoPrint' => true,
                    'pageSize' => 'A4',
                    'orientation' => 'landscape',
                    'text' => 'Print',
                    'title' => '',
                    'messageTop' => '',
                    'messageBottom' => '',
                    'logo' => '',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | CSV Configurations
            |--------------------------------------------------------------------------
            |
            | These are the configurations that enable csv datatable exports
            |
            */
            'csv' => [
                'enabled' => false,
                'options' => [
                    'header' => true,
                    'footer' => false,
                    'text' => 'CSV',
                    'filename' => '',
                    'extension' => '.csv',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | PDF Configurations
            |--------------------------------------------------------------------------
            |
            | These are the configurations that enable PDF datatable exports
            |
            */
            'pdf' => [
                'enabled' => false,
                'options' => [
                    'header' => true,
                    'footer' => true,
                    'autoDownload' => true,
                    'pageSize' => 'A4',
                    'orientation' => 'landscape',
                    'text' => 'PDF',
                    'filename' => 'Random',
                    'extension' => '.pdf',
                    'title' => '',
                    'messageTop' => '',
                    'messageBottom' => '',
                    'logo' => '',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Excel Configurations
            |--------------------------------------------------------------------------
            |
            | These are the configurations that enable Excel datatable exports
            |
            */
            'excel' => [
                'enabled' => false,
                'options' => [
                    'header' => true,
                    'footer' => true,
                    'text' => 'Excel',
                    'filename' => '',
                    'extension' => '.xlsx',
                    'title' => '',
                    'messageTop' => '',
                    'messageBottom' => '',
                    'logo' => '',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Copy Configurations
            |--------------------------------------------------------------------------
            |
            | These are the configurations that enable copying of datatable items into 
            | the clipboard
            |
            */
            'copy' => [
                'enabled' => false,
                'options' => [
                    'text' => 'Copy',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ]
            ],

            /*
            |--------------------------------------------------------------------------
            | Json Configurations
            |--------------------------------------------------------------------------
            |
            | These are the configurations that enable json exportations of datatables
            |
            */
            'json' => [
                'enabled' => false,
                'options' => [
                    'text' => 'JSON',
                    'extension' => '.json',
                    'exportOptions' => [
                        'columns' => ':visible'
                    ]
                ]
            ]
        ],

        /*
        |--------------------------------------------------------------------------
        | Filters setup
        |--------------------------------------------------------------------------
        |
        | These are the configurations that enable application of datatables filters.
        | Input is an array of searchable column properties eg
        | [ 'column' => 'column_name', 'type' => 'input|select' ]
        |
        */
        'filters' => [],

        /*
        |--------------------------------------------------------------------------
        | Sorting Configurations
        |--------------------------------------------------------------------------
        |
        | These are the configurations for the default sorting of datatable columns
        | [ 'column' => 'column_name', 'order' => 'asc|desc' ]
        |
        */
        'sorting' => [],

        /*
        |--------------------------------------------------------------------------
        | Hidden Column Configurations
        |--------------------------------------------------------------------------
        |
        | These are the configurations that determine which columns are hidden by 
        | default. Provide an array of column names
        |
        */
        'hiddenColumns' => [],

        /*
        |--------------------------------------------------------------------------
        | Ajax Configurations
        |--------------------------------------------------------------------------
        |
        | These are the configurations that manage ajax operations
        |
        */
        'ajax' => [
            'enabled' => false,
            'options' => [
                'route' => '',
            ]
        ]
    ]
];