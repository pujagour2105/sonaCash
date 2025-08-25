<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    public function generate($html, $filename = 'document.pdf', $stream = true, $paper = 'A4', $orientation = 'landscape')
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();

        // Add page number at bottom-right
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont("DejaVu Sans", "normal");

        // Adjust x and y for bottom-right (landscape A4)
        $canvas->page_text(730, 570, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10, [0, 0, 0]);

        if ($stream) {
            $dompdf->stream($filename, ['Attachment' => false]);
        } else {
            return $dompdf->output();
        }
    }


}