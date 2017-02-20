<?php

namespace Jimmyjs\PdfReportGenerators;

use PDF;

class PdfReportGenerator 
{
	private $headers;
	private $columns;
	private $query;
	private $limit = null;
	private $groupByArr = [];
	private $paper = 'a4';
	private $orientation = 'portrait';
	private $editColumns = [];
	private $showTotalColumns = [];
	private $styles = [];

	public function of($title, Array $meta = [], $query, Array $columns)
	{
		$this->headers = [
			'title' => $title,
			'meta'  => $meta
		];

		$this->query = $query;
		$this->columns = $columns;

		return $this;
	}

	public function setPaper($paper)
	{
		$this->paper = strtolower($paper);

		return $this;
	}

	public function editColumn($columnName, Array $option)
	{
		$this->editColumns[$columnName] = $option;

		return $this;
	}

	public function showTotal(Array $columns)
	{
		$this->showTotalColumns = $columns;

		return $this;
	}

	public function groupBy($column)
	{
		if (is_array($column)) {
			$this->groupByArr = $column;
		} else {
			array_push($this->groupByArr, $column);
		}

		return $this;
	}

	public function limit($limit)
	{
		$this->limit = $limit;

		return $this;
	}

	public function setOrientation($orientation)
	{
		$this->orientation = strtolower($orientation);

		return $this;
	}

	public function setCss(Array $styles)
	{
		foreach ($styles as $selector => $style) {
			array_push($this->styles, [
				'selector' => $selector,
				'style' => $style
			]);
		}

		return $this;
	}

	public function make()
	{
		$headers = $this->headers;
		$query = $this->query;
		$columns = $this->columns;
		$limit = $this->limit;
		$groupByArr = $this->groupByArr;
		$orientation = $this->orientation;
		$editColumns = $this->editColumns;
		$showTotalColumns = $this->showTotalColumns;
		$styles = $this->styles;

		$html = \View::make('pdf-report-generators::general-pdf-template', compact('headers', 'columns', 'editColumns', 'showTotalColumns', 'styles', 'query', 'limit', 'groupByArr', 'orientation'))->render();

		try {
			$pdf = \App::make('snappy.pdf.wrapper');
		} catch (\ReflectionException $e) {
			$pdf = \App::make('dompdf.wrapper');
		}
		$pdf->loadHTML($html)->setPaper($this->paper, $orientation);

		if ($pdf instanceof \Barryvdh\Snappy\PdfWrapper) {
			$pdf->setOption('footer-font-size', 10);
			$pdf->setOption('footer-left', 'Page [page] of [topage]');
			$pdf->setOption('footer-right', 'Date Printed: ' . date('d M Y H:i:s'));
		}

		return $pdf;
	}
}