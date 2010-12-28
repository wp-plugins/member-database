<div class="wrap">
<h2>Add New Member</h2>
<p>All fields required.</p>
<form action="admin.php?page=member-db/edit_member.php" method="post">
	<input type="hidden" name="action" value="insert">
<table>
	<?php
	//get table info
	$table_name = $wpdb->prefix . "member_db";
	$sql = "SHOW COLUMNS FROM ".$table_name;
	$columns = $wpdb->get_results($sql, ARRAY_A);
	
	//output form 
	foreach ($columns as $column){
		//DIAG: echo '<pre>'; print_r($column); echo '</pre>';
		$field_name = '';
		$words = explode("_", $column['Field']);
		foreach ($words as $word) $field_name .= $word.' ';
		if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' ) {
			printf ("<tr><td align=\"right\">%s: </td>", ucwords($field_name));
			if ( substr($column['Type'],0,7)=='varchar' ){
				//figure out length
				$pos1 = strpos($column['Type'],'(');
				$pos2 = strpos($column['Type'],')');
				$size = substr($column['Type'], $pos1+1, $pos2-$pos1-1);
				printf ("<td><input type=\"text\" name=\"%s\" size=\"%s\"></td></tr>\r\n", $column['Field'], $size);
			}
			if ( substr($column['Type'],0,4)=='date' ){
				printf ("<td><input type=\"text\" name=\"%s\" size=\"12\"></td></tr>\r\n", $column['Field']);
			}
			if ( substr($column['Type'],0,4)=='text' ){
				printf ("<td><textarea name=\"%s\" rows =\"5\" cols=\"48\"></textarea></tr>\r\n", $column['Field']);
			}
		}
	}
	?>
	<tr><td></td><td><input type="submit" value="Submit"></td><td></td></tr>
</table>
</form>
</div>