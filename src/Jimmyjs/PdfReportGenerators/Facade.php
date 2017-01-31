<?php

namespace Jimmyjs\PdfReportGenerators;

use Illuminate\Support\Facades\Facade as IlluminateFacade;
/**
 * @see \Jimmyjs\PdfReportGenerators\PdfReportGenerator
 */
class Facade extends IlluminateFacade
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
