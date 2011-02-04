<?php
	$table_name = $wpdb->prefix . "member_db";
	$columns = $wpdb->get_results("SHOW COLUMNS FROM ".$table_name, ARRAY_A);

	if ($_POST['action']=='update') {
		$sql = "UPDATE ".$table_name." SET date_updated = CURDATE(), ";
		foreach ($columns as $column){
			if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' ) $sql .=  $column['Field']." = '".$_POST[$column['Field']]."', ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= " WHERE id = ".$_POST['id'];
		$member_id = $_POST['id'];
		//DIAG: echo $sql;
		$wpdb->query($sql);
	}
	else $member_id = $_GET['id'];

	if ($_POST['action']=='insert') {
		$sql = "INSERT INTO ".$table_name." (date_updated, ";
		foreach ($columns as $column){
			if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' ) $sql .=  $column['Field'].", ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .=") VALUES (CURDATE(), ";
		foreach ($columns as $column){
			if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' ) $sql .= "'".$_POST[$column['Field']]."', ";
		}
		$sql = substr($sql,0,strlen($sql)-2);
		$sql .= ")";
		$wpdb->query($sql);
		$member_id = $wpdb->insert_id;
		//DIAG: echo '<pre>'; print_r($_POST); echo '</pre>';
		//DIAG: echo 'SQL: '.$sql.', MEMBER ID: '.$member_id;
	}
	
	//get the member information
	$sql = "SELECT * FROM ".$table_name." WHERE id = ".$member_id;
	$member = $wpdb->get_row($sql);
	//DIAG: echo 'SQL: '.$sql;
?>

<div class="wrap">
<h2>Edit Member</h2>
<form action="admin.php?page=member-database/edit_member.php" method="post">
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="id" value="<?php echo $member->id; ?>">
<table>
	<?php
	//get table info
	$table_name = $wpdb->prefix . "member_db";
	$sql = "SHOW COLUMNS FROM ".$table_name;
	$columns = $wpdb->get_results($sql, ARRAY_A);
	
	//output form using column info
	foreach ($columns as $column){
		//DIAG: echo '<pre>'; print_r($column); echo '</pre>';
		$field_name = '';
		$words = explode("_", $column['Field']);
		foreach ($words as $word) $field_name .= $word.' ';
		if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' ) {
			printf ("<tr><td valign=\"top\" align=\"right\">%s: </td>", ucwords($field_name));
			if ( substr($column['Type'],0,7)=='varchar' ){
				//figure out length
				$pos1 = strpos($column['Type'],'(');
				$pos2 = strpos($column['Type'],')');
				$size = substr($column['Type'], $pos1+1, $pos2-$pos1);
				printf ("<td><input type=\"text\" name=\"%s\" size=\"%s\" value=\"%s\"></td></tr>\r\n", $column['Field'], $size, $member->$column['Field']);
			}
			if ( substr($column['Type'],0,4)=='date' ){
				printf ("<td><input type=\"text\" name=\"%s\" size=\"12\" value=\"%s\"> yyyy-mm-dd</td></tr>\r\n", $column['Field'], $member->$column['Field']);
			}
			if ( substr($column['Type'],0,4)=='text' ){
				printf ("<td><textarea name=\"%s\" rows =\"5\" cols=\"48\">%s</textarea></tr>\r\n", $column['Field'], $member->$column['Field']);
			}
		}
	}
	?>
	<tr><td></td><td><input type="submit" value="Submit"></td><td></td></tr>
</table>
</form>
</div>