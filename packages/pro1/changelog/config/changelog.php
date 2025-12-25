<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Changelog Table
    |--------------------------------------------------------------------------
    |
    | This is the database table where changelog entries are stored.
    | If you want to use a different table name, update it here.
    |
    */

    'table' => 'changelogs',

    /*
    |--------------------------------------------------------------------------
    | Default Pagination
    |--------------------------------------------------------------------------
    |
    | Number of changelog entries shown per page in the UI.
    |
    */

    'per_page' => 15,

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | The format used to display dates in changelogs.
    | Example: 'd M Y H:i' → 19 Aug 2025 14:30
    |
    */

    'date_format' => 'd M Y H:i',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Define which middleware stack should protect the changelog routes.
    | Example: ['web', 'auth'] → Only authenticated users can access.
    |
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | URL prefix for accessing changelog routes.
    | Example: 'changelog' → site.com/changelog
    |
    */

    'route_prefix' => 'changelog',

    /*
    |--------------------------------------------------------------------------
    | Route Name Prefix
    |--------------------------------------------------------------------------
    |
    | Used for naming routes. Example: 'changelog.index'
    |
    */

    'route_name_prefix' => 'changelog.',

    /*
    |--------------------------------------------------------------------------
    | Views Path
    |--------------------------------------------------------------------------
    |
    | Path namespace for package views. By default it uses "changelog::"
    | so in Blade you can call view('changelog::index').
    |
    */

    'view_namespace' => 'changelog::',

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The user model related to changelog records. Normally Pro1\Changelog\Models\User.
    | You can change this if your project uses a different model.
    |
    */

    'user_model' => Pro1\Changelog\Models\User::class,

];
