<?php

Route::group([
                 'prefix'    => config('pdf-view.route_prefix'),
                 'namespace' => '\Larangular\PdfView\Http\Controllers',
             ], function () {
    Route::get('show/{id}/{type}.pdf', 'PdfView\PdfViewController@pdfBuild');
    Route::get('show/{id}/{type}', 'PdfView\PdfViewController@preview');
});
