<?php

namespace Larangular\PdfView\Http\Controllers\PdfView;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Larangular\Support\Instance;

class PdfViewController {

    private $content;

    public function show(int $id, string $type, ?string $extension = '') {
        try {
            $builder = $this->getBuilder(config('pdf-view.pdf_types.' . $type), $id);
            switch ($extension) {
                case 'json':
                    return $this->getBuilderContent($builder);
                    break;
                case 'pdf':
                    return $this->viewToPdf($this->getView($builder));
                    break;
                default:
                    return $this->getView($builder);
                    break;
            }

        } catch (\Exception $e) {
            abort(404);
            //$this->showExceptionMessage($e);
        }
    }

    private function viewToPdf(View $view): Response {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view->render());
        return $pdf->stream();
    }

    private function showExceptionMessage(\Exception $e) {
        echo __('pdf-view.exception', ['message' => $e->getMessage()]);
    }

    private function getView(PdfViewBuilder $builder): ?View {
        return (Instance::hasInterface($builder, PdfViewBuilder::class))
            ? view($builder->templatePath(), $this->getBuilderContent($builder))
            : null;
    }

    private function getBuilder(string $type, int $contentId): PdfViewBuilder {
        $viewer = new $type;
        $viewer->setContentId($contentId);
        return $viewer;
    }

    private function getBuilderContent(PdfViewBuilder $builder): array {
        if (is_null($this->content)) {
            $this->content = $builder->content();
        }

        if (empty($this->content)) {
            throw new \Exception('content cant be empty');
        }
        return $this->content;
    }
}
