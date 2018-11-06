<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 3/24/18
 * Time: 21:41
 */

namespace Larangular\PdfView;

use Illuminate\Support\ServiceProvider;

class PdfViewServiceProvider extends ServiceProvider {

    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/Http/Routes/PdfViewRoutes.php');
        $this->publishes([
                             __DIR__ . '/../config/pdf-view.php' => config_path('pdf-view.php'),
                         ]);
    }

    public function register(){}
}
