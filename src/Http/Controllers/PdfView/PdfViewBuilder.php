<?php

namespace Larangular\PdfView\Http\Controllers\PdfView;

interface PdfViewBuilder {

    public function setContentId($contentId): void;

    public function templatePath(): string;

    public function content(): array;

}
