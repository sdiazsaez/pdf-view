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
        try {
            return $this->getView($id, $type);
        } catch (\Exception $e) {
            $this->showExceptionMessage($e);
        }
    }

    public function pdfBuild($id, $type) {
        try {
            $view = $this->getView($id, $type);
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view->render());
            return $pdf->stream();
        } catch (\Exception $e) {
            $this->showExceptionMessage($e);
        }
    }

    private function showExceptionMessage(\Exception $e) {
        echo __('pdf-view.exception', ['message' => $e->getMessage()]);
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
