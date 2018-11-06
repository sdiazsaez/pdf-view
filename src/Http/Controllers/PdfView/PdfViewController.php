<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 10/22/18
 * Time: 17:37
 */

namespace Larangular\PdfView\Http\Controllers\PdfView;

use Illuminate\Contracts\Mail\Mailable;
use Larangular\EmailRecord\Models\EmailRequest;
use \Illuminate\Support\Facades\Mail;
use Larangular\EmailRecord\Models\SentEmail;
use Larangular\Support\Instance;
use Larangular\EmailRecord\Http\Controllers\Emails\RecordableEmail;
use Illuminate\View\View;

class PdfViewController {

    public function preview($id, $type) {
        return $this->getView($id, $type);
    }

    public function pdfBuild($id, $type) {
        $view = $this->getView($id, $type);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view->render());
        return $pdf->stream();
    }

    private function getView($id, $type): View {
        $view = null;
        $builder = $this->getBuilder(config('pdf-view.pdf_types.' . $type), $id);
        if (Instance::hasInterface($builder, PdfViewBuilder::class)) {
            $view = view($builder->templatePath(), $builder->content());
        }

        return $view;
    }

    private function getBuilder(string $type, int $contentId): PdfViewBuilder {
        $viewer = new $type;
        $viewer->setContentId($contentId);
        return $viewer;
    }


}
