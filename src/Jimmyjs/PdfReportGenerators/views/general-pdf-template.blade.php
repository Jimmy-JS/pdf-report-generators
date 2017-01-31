	<style>
		.wrapper {
			margin: -20px;
			padding: 0 15px;
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
		.bg-black {
			background-color: #000;
		}
		.f-white {
			color: #fff;
		}
	</style>

<body>
	<?php 
	$ctr = 1;
	$no = 1;
	$groupByData = null;
	$total = [];

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
			    				@if (array_key_exists($colName, $extraOptColumns))
			    					<th class="{{ isset($extraOptColumns[$colName]['class']) ? $extraOptColumns[$colName]['class'] : 'left' }}">{{ $colName }}</th>
			    				@else
				    				<th class="left">{{ $colName }}</th>
			    				@endif
			    			@endforeach
			    		</tr>
		    		</thead>
		    		<?php
					$query->chunk(300, function($results) use(&$ctr, &$no, &$total, &$groupByData, $headers, $columns, $limit, $extraOptColumns, $showTotalColumns, $groupBy) {
					?>
		    		@foreach($results as $result)
						<?php 
							if ($limit != null && $ctr == $limit + 1) return false;
		    				if ($groupBy != null && $result->$columns[$groupBy] != $groupByData) {
		    					if ($showTotalColumns != [] && $groupByData != null) {
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
		    					}

		    					$no = 1;
		    					foreach ($showTotalColumns as $showTotalColumn => $type) {
		    						$total[$showTotalColumn] = 0;
		    					}
		    					$groupByData = $result->$columns[$groupBy];
		    				}
						?>
			    		<tr align="center" class="{{ ($no % 2 == 0) ? 'even' : 'odd' }}">
			    			<td class="left">{{ $no }}</td>
			    			@foreach ($columns as $colName => $colData)
			    				<?php 
				    				$class = 'left';
				    				$colValue = $result->$colData;
				    				if (array_key_exists($colName, $extraOptColumns)) {
				    					if (isset($extraOptColumns[$colName]['class'])) {
				    						$class = $extraOptColumns[$colName]['class'];
				    					} 

				    					if (isset($extraOptColumns[$colName]['data']) && isClosure($extraOptColumns[$colName]['data'])) {
				    						$colValue = $extraOptColumns[$colName]['data']($result);
				    					} elseif (isset($extraOptColumns[$colName]['data']) && !isClosure($extraOptColumns[$colName]['data'])) {
				    						$colValue = $extraOptColumns[$colName]['data'];
				    					}
				    				}

				    				if (array_key_exists($colName, $showTotalColumns)) {
				    					$total[$colName] += $result->$colData;
				    				}
			    				?>
			    				<td class="{{ $class }}">{{ $colValue }}</td>
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
        if ( isset($pdf) ) {
        	$pdf->page_text(510, 810, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0,0,0));
            $pdf->page_text(30, 810, "Date Printed: " . date('d M Y H:i:s'), null, 10, array(0,0,0));
        }
    </script>
</body>