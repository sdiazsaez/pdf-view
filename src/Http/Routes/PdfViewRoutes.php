<?php

Route::group([
    'prefix'    => config('pdf-view.route_prefix'),
    'namespace' => '\Larangular\PdfView\Http\Controllers',
], function () {
    Route::get('show/{id}/{type}.{extension?}', 'PdfView\PdfViewController@show');
});
