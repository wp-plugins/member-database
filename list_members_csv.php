<?php
	//include some files
	include('../../../wp-blog-header.php');

	//DIAG: phpinfo();	 
	
	function fputcsv4($fh, $arr) {
		$csv = "";
		while (list($key, $val) = each($arr)) {
			$val = str_replace('"', '""', $val);
			$csv .= '"'.$val.'",';
		}
		$csv = substr($csv, 0, -1);
		$csv .= "\n";
		if (!@fwrite($fh, $csv)) 
			return FALSE;
	}
	
	//get member info and column data
	$table_name = $wpdb->prefix . "member_db";
	$year = date ('Y');
	$members = $wpdb->get_results("SELECT * FROM ".$table_name, ARRAY_A);
	$columns = $wpdb->get_results("SHOW COLUMNS FROM ".$table_name, ARRAY_A);
	//DIAG: echo 'SQL: '.$sql.', RESULT: '.$result.'<br>';
		
	//output headers
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"members.csv\"");
		
	//open output stream
	$output = fopen("php://output",'w'); 
		
	//output column headings
	$data[0] = "ID";
	$i = 1;
	foreach ($columns as $column){
		//DIAG: echo '<pre>'; print_r($column); echo '</pre>';
		$field_name = '';
		$words = explode("_", $column['Field']);
		foreach ($words as $word) $field_name .= $word.' ';
		if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' ) {
			$data[$i] = ucwords($field_name);
			$i++;
		}
	}
	$data[$i] = "Date Updated";
	fputcsv4($output, $data);
		
	//output member data
	foreach ($members as $member){
		//DIAG: echo '<pre>'; print_r($member); echo '</pre>';
		$data[0] = $member['id'];
		$i = 1;
		foreach ($columns as $column){
			//DIAG: echo '<pre>'; print_r($column); echo '</pre>';
			if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' ) {
				$data[$i] = $member[$column['Field']];
				$i++;
			}
		}
		$data[$i] = $member['date_updated'];
		//echo '<pre>'; print_r($data); echo '</pre>';
		fputcsv4($output, $data); 
	}
	fclose($output);
?>