<?php

namespace Jimmyjs\PdfReportGenerators;

use PDF;

class PdfReportGenerator 
{
	private $headers;
	private $columns;
	private $query;
	private $limit = null;
	private $groupBy = null;
	private $paper = 'a4';
	private $orientation = 'portrait';
	private $extraOptColumns = [];
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
		$this->extraOptColumns[$columnName] = $option;

		return $this;
	}

	public function showTotal(Array $columns)
	{
		$this->showTotalColumns = $columns;

		return $this;
	}

	public function groupBy($column)
	{
		$this->groupBy = $column;

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
		$groupBy = $this->groupBy;
		$orientation = $this->orientation;
		$extraOptColumns = $this->extraOptColumns;
		$showTotalColumns = $this->showTotalColumns;
		$styles = $this->styles;

		$pdf = PDF::loadView('pdf-report-generators::general-pdf-template', compact('headers', 'columns', 'extraOptColumns', 'showTotalColumns', 'styles', 'query', 'limit', 'groupBy', 'orientation'));
		$pdf->setPaper($this->paper, $orientation);

		return $pdf;
	}
}