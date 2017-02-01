# Laravel 5 - Pdf Report Generators
Rapidly Generate Simple Pdf Report on Laravel 5 (Using [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf))

This package provides a simple pdf generators to speed up your workflow

## Installation

Add package to your composer:

	composer require jimmyjs/pdf-report-generators

Then, add the ServiceProvider to the providers array in config/app.php

    Jimmyjs\PdfReportGenerators\ServiceProvider::class,

You can optionally use the facade for shorter code. Add this to your facades:

    'PdfReportGenerator' => Jimmyjs\PdfReportGenerators\Facade::class