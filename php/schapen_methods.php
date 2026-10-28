<?php
include 'excel_reader.php';

function agenda() {
	$maanden = array('jan','feb','mrt','apr','mei','jun','jul','aug','sep','okt','nov','dec');
	$date_real = getdate();
	try {
		$dates = array();

		$tables = "";

		$excel = new PhpExcelReader;
		$excel->read('./INHOUD/schapendrijven/agenda.xls');
		$sheets = $excel->sheets;
		$i = 0;
		for($j=0; $j < count($sheets); $j++) {
			$sheet = $sheets[$j];
			$date_str = lower($excel->boundsheets[$j]['name']);
			$white_row = true;
			foreach( $sheet as $content ) {
				if( eq(gettype($content),"array")) {
					array_push($dates,$date_str);


                    foreach($content as $content){
                        if(!isset($content[1])){$content[1] = '';}
                        if(!isset($content[2])){$content[2] = '';}
                        if(!isset($content[3])){$content[3] = '';}
                        if(!isset($content[4])){$content[4] = '';}
                        if(!isset($content[5])){$content[5] = '';}
                        if(!isset($content[6])){$content[6] = '';}
                        if(!isset($content[7])){$content[7] = '';}
                        if(!isset($content[8])){$content[8] = '';}
                        if(!isset($content[9])){$content[9] = '';}
                        if(!isset($content[10])){$content[10] = '';}
                        if(!isset($content[11])){$content[11] = '';}
                        if(!isset($content[12])){$content[12] = '';}
                        if(!isset($content[13])){$content[13] = '';}
                        if(!isset($content[14])){$content[14] = '';}
                        if(!isset($content[15])){$content[15] = '';}
                        if(!isset($content[16])){$content[16] = '';}
                    }
					if( vandaag($date_real, $date_str, $maanden) ) {
						$tables .= "
						<table class='agenda_table' id='agenda_$i' style='display:table;'>";
					} else {
						$tables .= "
						<table class='agenda_table' id='agenda_$i' style='display:none;'>";
					}

                   
					if( eq($content[2],"1")) {
						$tables .= "
							<tr>
								<th>Dag:</th>
								<td>" . $date_str . "</td>
							</tr>";
						if( ! eq($content[1][2],"")) {
							$tables .= "
							<tr>
								<th>Trainer:</th>
								<td>" . $content[1][2] . "</td>
							</tr>";
						}
						$tables .= "							
							<tr><td><br></td></tr>
							<tr class='top_row'>
								<th>Tijd</th>
								<th>Ras</th>
								<th>Handler met hond</th>
								<th></th>
							</tr>";

						for( $x = 6; $x <= 56; $x++ ) {
                            $row = $content[$x];
							$row[1] = clean_string($row[1]);
							$row[2] = clean_string($row[2]);
							$row[3] = clean_string($row[3]);
							$row[4] = clean_string($row[4]);
							$nr_of_cells = sizeof($row);
							if( $nr_of_cells == 0 ) {
								if( ! $white_row ) {
									$tables .= "
									<tr>
										<td colspan='4'><br></td>
									</tr>
									";
									$white_row = true;
								}
							} else {
								$white_row = false;
							}
							if( $nr_of_cells == 1 ) {
								$tables .= "
								<tr>
									<td>" . str_replace('h',':',$row[1]) . "</td>
									<td>" . $row[2] . "</td>
									<td>" . $row[3] . "</td>
									<td>" . $row[4] . "</td>
								</tr>";
							}
							if( $nr_of_cells > 1 ) {
								$tables .= "
								<tr>
									<td>" . str_replace('h',':',$row[1]) . "</td>";
								if( eq(lower($row[2]),"prive")) {
									$tables .= "
									<td><i>Priv&eacute; gehoorzaamheid</i></td>";
								} else {
									$tables .= "
									<td>" . $row[2] . "</td>";
								}
									
								if( eq(lower($row[3]),"vrij")) {
									$tables .= "
									<td><strong>Vrij</strong></td>";
								} else {
									$tables .= "
									<td>" . $row[3] . "</td>";
								}

								if( ! eq($row[4],"") ) {
									$tables .= "
									<td>vast (" . $row[4] . ")</td>";
								} else {
									$tables .= "
									<td></td>";
								}

								$tables .= "
								</tr>
								";

								
							}
							
						}

						$tables .= "
							</table>
						";
					} else {
						$tables .= "
							<tr>
								<th colspan='1' width='120'>Dag:</th>
								<td colspan='3'>" . $date_str . "</td>
							</tr>
							<tr><td><br></td></tr>
							<tr class='top_row'>
								<th>Opmerking</th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
							<tr class='opmerking'>
								<td colspan='4'>
									<h4>". $content[2] ."</h4>
								</td>
							</tr>";

					}
					

					$i++;
				}
			}
		}
	} catch (Exception $e) {
		$output = $e->getMessage();
	}

	$output = "<div id='nr_of_days' style='display:none;'>" . sizeof($dates) . "</div>";
	$output .= datum_scroller( $dates );
	$output .= $tables;
	$output .= hori_scroll_script( sizeof($dates));
	$output .= show_dagrooster_script( sizeof($dates));
	return $output;
}

function datum_scroller( $dates ) {
	$maanden = array('jan','feb','mrt','apr','mei','jun','jul','aug','sep','okt','nov','dec');
	$date_real = getdate();

	$output = "
	<div id=\"datum_menu\" class=\"in_box\">
      <table>
		<tr>
			<td>
				<div id=\"earlier_button\">
          			<a class=\"btn btn-primary btn-lg\" href=\"#\" onclick=\"return false;\"><span class=\"glyphicon glyphicon-arrow-left\"></span></span></a>
        		</div>
			</td>
			<td style='width:0px;' id='scroll_cell'>
				<div id=\"wrapper\" style=\"width:0px;overflow:auto;\">
		          <div id=\"scrollmenu\">";
    
    $i = 0;
    foreach($dates as $date) {
    	if( vandaag($date_real, $date, $maanden) ) {
    		$output .= "<a href='#' onclick='return show_dagrooster($i);' id='datum_$i' style='font-weight:bold;'>$date</a>";
    	} else {
    		$output .= "<a href='#' onclick='return show_dagrooster($i);' id='datum_$i'>$date</a>";
    	}
    	$i += 1;
    }
    
    $output .= "
		          </div>
		        </div>
			</td>
			<td>
				<div id=\"later_button\">
		          <a class=\"btn btn-primary btn-lg\" href=\"#\" onclick=\"return false;\"><span class=\"glyphicon glyphicon-arrow-right\"></span></a>
		        </div>
			</td>
		</tr>
      </table>
    </div>";

    return $output;
}

function show_dagrooster_script( $nr_of_days ) {
	$output = "
	<script>
		function show_dagrooster( idx ) {
			for (var i = 0; i < $nr_of_days; i++) {
				if( i == idx ) {
					$('#datum_'+i).css('font-weight','bold');
					$('#agenda_'+i).css('display','table');
				}
				else {
					$('#datum_'+i).css('font-weight','normal');
					$('#agenda_'+i).css('display','none');
				}
			}
			return false;
		}
	</script>
	";
	return $output;
}

function hori_scroll_script( $nr_of_days ) {
	$output = "
	<script>
        var hori_idx = 0;
        document.getElementById('later_button').addEventListener('click',function(){
        	var o = document.getElementById('scrollmenu');
        	if( o.scrollWidth - o.offsetWidth - o.scrollLeft > 5 ) {
        		hori_idx += 2;
        		if( hori_idx > $nr_of_days-1 ) {hori_idx = $nr_of_days-1; }
		        var to_query = \"#datum_\".concat(hori_idx);
		        var pixels   = $(to_query).offset().left - $('#scrollmenu').offset().left + $('#scrollmenu').scrollLeft();
          		$(\"#scrollmenu\").animate({ scrollLeft: pixels}, 100);
        	}
        });
        document.getElementById('earlier_button').addEventListener('click',function(){
        	var o = document.getElementById('scrollmenu');

        	
        	if( o.scrollLeft > 1 ) {
        		hori_idx -= 2;
        		if( hori_idx < 0 ) { hori_idx = 0; }
        		var to_query = \"#datum_\".concat(hori_idx);
        		var pixels   = $(to_query).offset().left - $('#scrollmenu').offset().left + $('#scrollmenu').scrollLeft();
        		$(\"#scrollmenu\").animate({ scrollLeft: pixels}, 100);
        	}
        });
      </script>";
      return $output;
}

function vandaag( $date_real, $date_str, $maanden ) {
	$parts = explode(' ', $date_str);
	return ( $date_real['mday'] == $parts[1] && $maanden[$date_real['mon']-1] == $parts[2]);
}

function startsWithCapital( $str ) {
	$chr = mb_substr($str, 0, 1, "UTF-8");
	return mb_strtolower($chr, "UTF-8") != $chr;
}

function eq($str1, $str2) {
	return (strcmp($str1,$str2)==0);
}

function is_empty($str) {
	return (eq($str,""));
}

function clean_string($str) {
    return str_replace("é","&eacute",$str);
}

function lower($str) {
	return strtolower($str);
}

?>