<?php

namespace JimmyJS\PdfReportGenerators\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JimmyJS\Shortener
 */
class PdfReportGenerator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pdf-report-generator';
    }
}
