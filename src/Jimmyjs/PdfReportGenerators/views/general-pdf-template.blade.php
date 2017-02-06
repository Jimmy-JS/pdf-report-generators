<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<style>
			.wrapper {
				margin: -20px;
				padding: 0px 15px 20px;
			}
		    .middle {
		        text-align: center;
			    font-family: Arial, Helvetica, sans-serif;;
			    font-size: 35px;
		    }
		    .pb-10 {
		    	padding-bottom: 10px;
		    }
		    .head {
		    	padding-bottom: 5px;
		    }
		    .head-content{
		    	padding-bottom: 4px;
		    	border-style: none none ridge none;
		    	font-size: 18px;
		    	font-family: Arial, Helvetica, sans-serif;
		    }
		    .table{
		    	font-size: 13px;
		    	font-family: Arial;
		    }
		    table {
		    	border-collapse:collapse;
		    }
			.page-break {
			    page-break-after: always;
			}
			tr.even {
				background-color: #eff0f1;
			}
			table .left {
				text-align: left;
			}
			table .right {
				text-align: right;
			}
			table .bold {
				font-weight: 600;
			}
			.bg-black {
				background-color: #000;
			}
			.f-white {
				color: #fff;
			}
			@foreach ($styles as $style)
			{{ $style['selector'] }} {
				{{ $style['style'] }}
			}
			@endforeach
		</style>
	</head>
	<body>
		<?php 
		$ctr = 1;
		$no = 1;
		$currentGroupByData = [];
		$total = [];
		$isOnSameGroup = true;

		foreach ($showTotalColumns as $column => $type) {
			$total[$column] = 0;
		}
		?>
		<div class="wrapper">
		    <div class="head">
			    <div class="middle pb-10">
			        {{ $headers['title'] }}
			    </div>
				<div class="head-content">
					<table cellpadding="0" cellspacing="0" width="100%" border="0">
						<?php $metaCtr = 0; ?>
						@foreach($headers['meta'] as $name => $value)
							@if ($metaCtr % 2 == 0)
							<tr>
							@endif
								<td style="color:#808080;">{{ $name }}</td>
								<td>: {{ $value }}</td>
							@if ($metaCtr % 2 == 1)
							</tr>
							@endif
							<?php $metaCtr++; ?>
						@endforeach
					</table>	    		
				</div>
		    </div>
		    <div class="content">
			    <div class="table">
			    	<table width="100%">
			    		<thead>
				    		<tr>
				    			<th class="left">No</th>
				    			@foreach ($columns as $colName => $colData)
				    				@if (array_key_exists($colName, $editColumns))
				    					<th class="{{ isset($editColumns[$colName]['class']) ? $editColumns[$colName]['class'] : 'left' }}">{{ $colName }}</th>
				    				@else
					    				<th class="left">{{ $colName }}</th>
				    				@endif
				    			@endforeach
				    		</tr>
			    		</thead>
			    		<?php
			    		$chunkRecordCount = ($limit == null || $limit > 300) ? 300 : $limit;
						$query->chunk($chunkRecordCount, function($results) use(&$ctr, &$no, &$total, &$currentGroupByData, &$isOnSameGroup, $headers, $columns, $limit, $editColumns, $showTotalColumns, $groupByArr) {
						?>
			    		@foreach($results as $result)
							<?php 
								if ($limit != null && $ctr == $limit + 1) return false;
								if ($groupByArr != []) {
									$isOnSameGroup = true;
									foreach ($groupByArr as $groupBy) {
										if (isClosure($columns[$groupBy])) {
					    					$thisGroupByData[$groupBy] = $columns[$groupBy]($result);
					    				} else {
					    					$thisGroupByData[$groupBy] = $result->$columns[$groupBy];
					    				}

					    				if (isset($currentGroupByData[$groupBy])) {
					    					if ($thisGroupByData[$groupBy] != $currentGroupByData[$groupBy]) {
					    						$isOnSameGroup = false;
					    					}
					    				}

					    				$currentGroupByData[$groupBy] = $thisGroupByData[$groupBy];
					    			}

					    			if ($isOnSameGroup === false) {
			    						echo '<tr class="bg-black f-white">
			    							<td><b>Grand Total</b></td>';
			    							foreach ($columns as $colName => $colData) {
			    								if (array_key_exists($colName, $showTotalColumns)) {
			    									if ($showTotalColumns[$colName] == 'point') {
			    										echo '<td class="right"><b>' . thousandSeparator($total[$colName]) . '</b></td>';
			    									} elseif ($showTotalColumns[$colName] == 'idr') {
			    										echo '<td class="right"><b>IDR ' . thousandSeparator($total[$colName]) . '</b></td>';
			    									}
			    								} else {
			    									echo '<td></td>';
			    								}
			    							}
			    						echo '</tr>';//<tr style="height: 10px;"><td colspan="99">&nbsp;</td></tr>';

										// Reset No, Reset Grand Total
			    						$no = 1;
			    						foreach ($showTotalColumns as $showTotalColumn => $type) {
			    							$total[$showTotalColumn] = 0;
			    						}
			    						$isOnSameGroup = true;
			    					}
				    			}
							?>
				    		<tr align="center" class="{{ ($no % 2 == 0) ? 'even' : 'odd' }}">
				    			<td class="left">{{ $no }}</td>
				    			@foreach ($columns as $colName => $colData)
				    				<?php 
					    				$class = 'left';
					    				// Check Edit Column to manipulate class & Data
					    				if (isClosure($colData)) {
					    					$generatedColData = $colData($result);
					    				} else {
					    					$generatedColData = $result->$colData;
					    				}
					    				$displayedColValue = $generatedColData;
					    				if (array_key_exists($colName, $editColumns)) {
					    					if (isset($editColumns[$colName]['class'])) {
					    						$class = $editColumns[$colName]['class'];
					    					} 

					    					if (isset($editColumns[$colName]['displayAs']) && isClosure($editColumns[$colName]['displayAs'])) {
					    						$displayedColValue = $editColumns[$colName]['displayAs']($result);
					    					} elseif (isset($editColumns[$colName]['displayAs']) && !isClosure($editColumns[$colName]['displayAs'])) {
					    						$displayedColValue = $editColumns[$colName]['displayAs'];
					    					}
					    				}

					    				if (array_key_exists($colName, $showTotalColumns)) {
					    					$total[$colName] += $generatedColData;
					    				}
				    				?>
				    				<td class="{{ $class }}">{{ $displayedColValue }}</td>
				    			@endforeach
				    		</tr>
			    			<?php $ctr++; $no++; ?>
			    		@endforeach
						<?php }); ?>
						@if ($showTotalColumns != [] && $ctr > 1)
							<tr class="bg-black f-white">
								<td><b>Grand Total</b></td> {{-- For Number --}}
								@foreach ($columns as $colName => $colData)
									@if (array_key_exists($colName, $showTotalColumns))
										@if ($showTotalColumns[$colName] == 'point')
											<td class="right"><b>{{ thousandSeparator($total[$colName]) }}</b></td>
										@elseif ($showTotalColumns[$colName] == 'idr')
											<td class="right"><b>IDR {{ thousandSeparator($total[$colName]) }}</b></td>
										@endif
									@else
										<td></td>
									@endif
								@endforeach
							</tr>
						@endif
			    	</table>
			    </div>
			</div>
		</div>
	    <script type="text/php">
	    	@if (strtolower($orientation) == 'portrait')
	        if ( isset($pdf) ) {
	            $pdf->page_text(30, ($pdf->get_height() - 26.89), "Date Printed: " . date('d M Y H:i:s'), null, 10);
	        	$pdf->page_text(($pdf->get_width() - 84), ($pdf->get_height() - 26.89), "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10);
	        }
		    @elseif (strtolower($orientation) == 'landscape')
		    if ( isset($pdf) ) {
		        $pdf->page_text(30, ($pdf->get_height() - 26.89), "Date Printed: " . date('d M Y H:i:s'), null, 10);
		    	$pdf->page_text(($pdf->get_width() - 84), ($pdf->get_height() - 26.89), "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10);
		    }
		    @endif
	    </script>
	</body>
</html>