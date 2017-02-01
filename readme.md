# Laravel 5 - Pdf Report Generators
Rapidly Generate Simple Pdf Report on Laravel 5 (Using [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf))

This package provides a simple pdf generators to speed up your workflow

## Installation
Add package to your composer:

	composer require jimmyjs/pdf-report-generators

Then, add the ServiceProvider to the providers array in config/app.php

    Jimmyjs\PdfReportGenerators\ServiceProvider::class,

Also, you can use `PdfReportGenerator` facade for shorter code that already registered as an alias for `Jimmyjs\PdfReportGenerators\Facade` class.

## Usage
This package is make use of `chunk` method (Eloquent / Query Builder) so it can handle big data without memory exhausted.
```php
public function displayReport(Request $request) {
	// Retrieve any filters
	$fromDate = $request->input('from_date');
	$toDate = $request->input('to_date');
	$sortBy = $request->input('sort_by');

	// Report title
	$title = 'Registered User Report';

	// For displaying filters description on header
	$meta = [
		'Registered on' => $fromDate . ' To ' . $toDate,
		'Sort By' => $sortBy
	];

	// Do some querying..
	$queryBuilder = User::select(['name', 'total_point', 'balance', 'registered_at'])
						->whereBetween('registered_at', [$fromDate, $toDate])
						->orderBy($sortBy);

	// Set Column to be displayed
	$columns = [
		'Name' => 'name',
		'Registered At' => 'registered_at',
		'Total Point' => 'total_point'
		'Total Balance' => 'balance'
	];

	// Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
	return PdfReportGenerator::of($title, $meta, $queryBuilder, $columns)
				->editColumn('Registered At', [
					'data' => function($result) {
						return $result->registered_at->format('d M Y');
					}
				])
				->editColumn('Total Point', [
					'class' => 'right', 
					'data' => function($result) {
						return thousandSeparator($result->total_point);
					}
				])
				->editColumn('Total Balance', [
					'class' => 'right bold', 
					'data' => function($result) {
						return 'USD ' . thousandSeparator($result->balance);
					}
				])
				// showTotal is used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				->showTotal([
					'Total Point' => 'point',
					'Total Balance' => 'point'
				])
				// Limit your record
				->limit(20)
				// make method will producing DomPDF instance so you could do any other DomPDF method such as stream() or download()
				->make()
				->stream(); // or download() to download pdf
}
```

### Output Report
![Output Report with Grand Total](https://raw.githubusercontent.com/Jimmy-JS/pdf-report-generators/master/screenshots/report-with-total.png)