<?php

namespace Larangular\PdfView\Http\Controllers\PdfView;

interface PdfViewBuilder {

    public function setContentId($contentId);

    public function templatePath(): string;

    public function content(): array;

}
