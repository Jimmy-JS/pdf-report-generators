<?php

namespace Jimmyjs\PdfReportGenerators;

/**
 * @see \Jimmyjs\PdfReportGenerators\PdfReportGenerator
 */
class Facade extends \Illuminate\Support\Facades\Facade
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
