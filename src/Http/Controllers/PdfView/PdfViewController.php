<?php

namespace Larangular\PdfView\Http\Controllers\PdfView;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Larangular\Support\Instance;

class PdfViewController {

    private $content;

    public function show(int $id, string $type, ?string $extension = '') {
        $builder = $this->getBuilder(config("pdf-view.pdf_types.{$type}"), $id);

        if ($extension === 'json') {
            return $this->getBuilderContent($builder);
        }

        $view = $this->getView($builder);

        return ($extension === 'pdf')
            ? $this->viewToPdf($view)
            : $view;
    }


    private function viewToPdf(View $view): Response {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view->render());
        return $pdf->stream();
    }

    private function getView(PdfViewBuilder $builder): ?View {
        return (Instance::hasInterface($builder, PdfViewBuilder::class))
            ? view($builder->templatePath(), $this->getBuilderContent($builder))
            : null;
    }

    private function getBuilder(string $type, int $contentId): PdfViewBuilder {
        if (!class_exists($type)) {
            throw new Exception("Invalid PDF view type: {$type}");
        }

        $viewer = new $type();
        $viewer->setContentId($contentId);

        return $viewer;
    }

    private function getBuilderContent(PdfViewBuilder $builder): array {
        if (method_exists($builder, 'isValid') && !$builder->isValid()) {
            abort(404, 'PDF not found for the given ID');
        }

        if (is_null($this->content)) {
            $this->content = $builder->content();
        }

        if (empty($this->content)) {
            throw new \Exception('Content cannot be empty');
        }

        return $this->content;
    }

    private function handleException(\Exception $e): Response {
        // Swap to logging or JSON response as needed
        return response(
            __('pdf-view.exception', ['message' => $e->getMessage()]),
            500
        );
    }

}
