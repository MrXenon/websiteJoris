<?php
include 'excel_reader.php';

function tijden() {
	try {
		$output = "
		<div class='in_box'>
		<table id='tijd_tabel'>
			<tr>
				<th>Tijd</th>
				<th>Cursus</th>
			</tr>

		";

		$excel = new PhpExcelReader;
		$excel->read('./INHOUD/cursussen/tijden.xls');
		$sheet = $excel->sheets[0];
		foreach( $sheet as $content ) {
			//actual content of sheet
			if( eq(gettype($content),"array") ) {
				//loop over rows in file
				foreach( $content as $row ) {
					if( eq(gettype($row),"array") ) {
						if( eq("$row[1]","Beschrijving") || eq("$row[1]","")) {
							continue;
						}
						else {
							$beschrijving = "<i>onbekend</i>"; $tijd = "<i>onbekend</i>"; $cancelled = false;
							if( !is_empty($row[1]) ) {
								$beschrijving = $row[1];
								if( ! startsWithCapital($beschrijving) ) { $beschrijving = "<i>" . $beschrijving . "</i>" ;}
							}
							if( !is_empty($row[2])) { $cancelled = (eq($row[2],"0"));}
							if( !is_empty($row[3])) { $tijd      = str_replace('h',':',$row[3]); }
							if( !is_empty($row[4])) { $tijd     .= ' - ' . str_replace('h',':',$row[4]);}
							

							if( $cancelled ) {
								$output .= "
								<tr>
									<td class='red_text'>(afgelast)</td>
									<td class='red_text'><del>$beschrijving</del></td>
								</tr>
								";
							}
							else {
								$output = $output . "
								<tr>
									<td>$tijd</td>
									<td>$beschrijving</td>
								</tr>
								";
							}
						}
					}
				}
			}
		}
		$output .= "</table></div>";
		return $output;
	} catch( Exception $e ) {
		$ouput = "
		<div class='red_text in_box'>
			<p>
				Er is iets misgegaan bij het ophalen uit de database. Excuses voor het ongemak.
			</p>
			<h4>Foutmelding:</h4>
			<p>
			" . ($e->getMessage()) . "
			</p>
		</div>";
		return $output;
	}
	return "";
}

function data() {

	$col = 1;

	$output = "
	<div class='row'>
	";

	$excel = new PhpExcelReader;
	$excel->read('./INHOUD/cursussen/data.xls');
	$sheets = $excel->sheets;

	for( $i = 0; $i < count($sheets); $i++ ) {
		if( $col == 2 ) {
			$output .= "<div class='col-md-2'><br></div>";
		}
		if( $col == 3 ) {
			$output .= "</div>
<div class='margin' style='margin-top: 0px; width:100%; padding:12px;'></div>
			<div class='row'>";
			$col = 1;
		}
		$col += 1;

		foreach( $sheets[$i] as $content ) {
			if(eq(gettype($content),"array")) {
				$output .= leesDataSheet($content);
			}
		}

	}

    $output .= "
	
	</div>

    ";

    return $output;
}

function leesDataSheet( $sheet ) {
	$output = "
		<div class='in_box'>
			<div class='col-md-6' style='width:100%;'>
	";
	$r = 1;

	$section = 1; $do_th = true;
	foreach( $sheet as $row ) {
		$field = $row[1];
		if( $section == 1) {
			if( eq($field,"Algemene Beschrijving:") || eq($field,"") ) {continue;}
			elseif( eq($field,"Lesbeschrijvingen:") ) {$section = 2;}
			else {
				$output .= "
				<p><strong>$field</strong></p>
				";
			}
		}
		elseif ($section == 2) {
			if(eq($field,"") ) {continue;}
			elseif (eq($field,"Les op:")) { $section = 3;}
			else {
				$output .= "
				<p>$field</p>
				";
			}
		}
		elseif ($section == 3) {
			if(eq($field,"") ) {continue;}
			elseif ($do_th) {
				$do_th = false;

				$output .= "
				<br>
				<table style='width:100%; margin-bottom:auto;'>
					<tr style='border-top: 3px dashed #dddddd;'><td><br></td></tr>
					<tr><th>Les op:</th><td>$field</td></tr>
				";
			}
			elseif(eq($field,"Geen les op:")) {$section = 4; $do_th = true;}
			else {
				$output .= "
					<tr><td></td><td>$field</td></tr>
				";
			}
		}
		else {
			if(eq($field,"") ) {continue;}
			elseif ($do_th) {
				$do_th = false;

				$output .= "
					<tr><td><br></td></tr>
					<tr style='border-top: 3px dashed #dddddd;'><td><br></td></tr>
					<tr><th>Geen les op:</th><td>$field</td></tr>
				";
			}
			elseif(eq($field,"Geen les op:")) {$section = 4;}
			else {
				$output .= "
					<tr><td></td><td>$field</td></tr>
				";
			}
		}
	}

	$output .= "
				</table>
			</div>
		</div>
	";
	return $output;
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

/*
function tijden() {
	$file = fopen("./INHOUD/cursussen/tijden.csv","r");

	$output = "
	<div class='in_box'>
	<table id='tijd_tabel'>
		<tr>
			<th>Tijd</th>
			<th>Cursus</th>
		</tr>

	";

	//discard first line of .csv file
	fgetcsv($file);

	//read data line by line
	for( $i = 0; $i < 20 && !feof($file); $i++ ) {
		$line = fgetcsv($file)[0];
		if( $line ) {

			$fields = explode(';',$line);
			$beschrijving = "<i>onbekend</i>"; $tijd = "<i>onbekend</i>"; $cancelled = false;
			if( count($fields) >= 1 ) {
				$beschrijving = $fields[0];
				if( ! startsWithCapital($beschrijving) ) { $beschrijving = "<i>" . $beschrijving . "</i>" ;}
			}
			if( count($fields) >= 2 ) { $cancelled = (strcmp($fields[1],"FALSE")==0);}
			if( count($fields) >= 3 && !eq($fields[2],"")) {	$tijd = str_replace('h',':',$fields[2]); }
			if( count($fields) >= 4 && !eq($fields[3],"")) { $tijd .= ' - ' . str_replace('h',':',$fields[3]);}
			

			if( $cancelled ) {
				$output .= "
				<tr>
					<td class='red_text'>(afgelast)</td>
					<td class='red_text'><del>$beschrijving</del></td>
				</tr>
				";
			}
			else {
				$output = $output . "
				<tr>
					<td>$tijd</td>
					<td>$beschrijving</td>
				</tr>
				";
			}

		}
	}
	$output .= "

	</table>
	</div>

	";
	return $output;
}


 */

?>