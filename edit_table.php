<?php
	$table_name = $wpdb->prefix . "member_db";
	//DIAG: echo '<pre>'; print_r($_POST); echo '</pre>';
	
	if ($_POST['action']=='add'){
		$column_name = '';
		$words = explode (" ", $_POST['column_name']);
		foreach ($words as $word) $column_name .= strtolower($word)."_"; 
		$column_name = substr ($column_name,0,strlen($column_name)-1);
		if ($_POST['type'] == 'varchar') {
			$sql = "ALTER TABLE ".$table_name." ADD COLUMN ".$column_name." ".$_POST['type']."(".$_POST['size'].")";
			$wpdb->query($sql);
			$sql = "ALTER TABLE ".$table_name." MODIFY ".$column_name." ".$_POST['type']."(".$_POST['size'].") AFTER ".$_POST['add_after'];
			$wpdb->query($sql);
		}
		else {
			$sql = "ALTER TABLE ".$table_name." ADD COLUMN ".$column_name." ".$_POST['type'];
			$wpdb->query($sql);
			$sql = "ALTER TABLE ".$table_name." MODIFY ".$column_name." ".$_POST['type']." AFTER ".$_POST['add_after'];
			$wpdb->query($sql);
		}
	}

	if ($_POST['action']=='modify'){
		if ($_POST['type'] == 'varchar') $sql = "ALTER TABLE ".$table_name." MODIFY ".$_POST['column_name']." varchar(".$_POST['length'].")";
		else if ($_POST['type'] == 'text') $sql = "ALTER TABLE ".$table_name." MODIFY ".$_POST['column_name']." text";
		else if ($_POST['type'] == 'date') $sql = "ALTER TABLE ".$table_name." MODIFY ".$_POST['column_name']." date";
		else echo 'Type must be either varchar, date, or text.';
		$wpdb->query($sql);
	}
	
	if ($_POST['action']=='delete'){
		$sql = "ALTER TABLE ".$table_name." DROP COLUMN ".$_POST['column_name'];
		$wpdb->query($sql);
	}
	
	//DIAG: echo $sql;
?>
<script type="text/javascript" language="javascript">  
	function really() {
		var x=window.confirm("Do you really want to delete this column? Deleting it will delete all of the data in that column.")
		if (x)
			return true
		else
			return false
	}
</script>

<div class="wrap">
<h2>Edit Table</h2>
<?php
	//get table info
	$table_name = $wpdb->prefix . "member_db";
	$sql = "SHOW COLUMNS FROM ".$table_name;
	$columns = $wpdb->get_results($sql, ARRAY_A);
	//DIAG: echo 'SQL: '.$sql.'<br>';
	
	echo "<table>\r\n";
	echo "<tr><td><b>Field</b></td><td><b>Type</b></td><td></td></tr>";
		
	//output form 
	foreach ($columns as $column){
		//DIAG: echo '<pre>'; print_r($column); echo '</pre>';
		$field_name = '';
		$words = explode("_", $column['Field']);
		foreach ($words as $word) $field_name .= $word.' ';
		if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' ) {
			printf ("<tr><td>%s</td>", ucwords($field_name));
			if ( substr($column['Type'],0,7)=='varchar' ){
				//figure out length
				$pos1 = strpos($column['Type'],'(');
				$pos2 = strpos($column['Type'],')');
				$size = substr($column['Type'], $pos1+1, $pos2-$pos1-1);
				printf ("<td>
					<form action=\"admin.php?page=member-database/edit_table.php\" method=\"post\">
						<input type=\"hidden\" name =\"action\" value=\"modify\">
						<input type=\"hidden\" name =\"column_name\" value=\"%s\">
						<input type=\"text\" name=\"type\" value=\"varchar\" size=\"8\">
						<input type=\"text\" name=\"length\" size=\"3\" value=\"%s\">
						<input type=\"submit\" value=\"Modify Field\">
					</form>
					</td>
					<td>
						<form action=\"admin.php?page=member-database/edit_table.php\" method=\"post\">
							<input type=\"hidden\" name =\"action\" value=\"delete\">
							<input type=\"hidden\" name =\"column_name\" value=\"%s\">
							<input type=\"submit\" value=\"Delete Field\" onClick=\"return really();\">
						</form>
					</td>
					</tr>\r\n", $column['Field'], $size, $column['Field']);
			}
			if ( substr($column['Type'],0,4)=='date' ){
				printf ("<td>
					<form action=\"admin.php?page=member-database/edit_table.php\" method=\"post\">
						<input type=\"hidden\" name =\"action\" value=\"modify\">
						<input type=\"hidden\" name =\"column_name\" value=\"%s\">
						<input type=\"text\" name=\"type\" value=\"date\" size=\"8\">
						<input type=\"text\" name=\"length\" size=\"3\">
						<input type=\"submit\" value=\"Modify Field\">
					</form>
					</td>
					<td>
						<form action=\"admin.php?page=member-database/edit_table.php\" method=\"post\">
							<input type=\"hidden\" name =\"action\" value=\"delete\">
							<input type=\"hidden\" name =\"column_name\" value=\"%s\">
							<input type=\"submit\" value=\"Delete Field\" onClick=\"return really();\">
						</form>
					</td>
					</tr>\r\n", $column['Field'], $column['Field']);
			}
			if ( substr($column['Type'],0,4)=='text' ){
				printf ("<td>
					<form action=\"admin.php?page=member-database/edit_table.php\" method=\"post\">
						<input type=\"hidden\" name =\"action\" value=\"modify\">
						<input type=\"hidden\" name =\"column_name\" value=\"%s\">
						<input type=\"text\" name=\"type\" value=\"text\" size=\"8\">
						<input type=\"text\" name=\"length\" size=\"3\">
						<input type=\"submit\" value=\"Modify Field\">
					</form>
					</td>
					<td>
						<form action=\"admin.php?page=member-database/edit_table.php\" method=\"post\">
							<input type=\"hidden\" name =\"action\" value=\"delete\">
							<input type=\"hidden\" name =\"column_name\" value=\"%s\">
							<input type=\"submit\" value=\"Delete Field\" onClick=\"return really();\">
						</form>
					</td>
					</tr>\r\n", $column['Field'], $column['Field']);
			}
		}
	}
	echo '</table>';
?>
<p><b>Add a New Field</b></p>
<form action="admin.php?page=member-database/edit_table.php" method="post">
	<input type="hidden" name="action" value="add">
	<table>
		<tr><td align="right">Name: </td><td><input type="text" name="column_name"></td></tr>
		<tr>
			<td align="right" valign="top">Type: </td>
			<td valign="top">
				<select name="type">
					<option>varchar</option>
					<option>text</option>
					<option>date</option>
				</select>
			</td>
			<td>Select varchar to add a field with a single line of text,<br />text for a text block,<br /> and date to add a date field.
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">Size: </td>
			<td valign="top"><input type="text" name="size" size="3"></td>
			<td>If adding a varchar field, specify the number of characters.<br />Leave blank if adding a text or date field.</td>
		</tr>
		<tr>
			<td align="right">Add After: </td>
			<td>
				<select name="add_after">
					<option value="id">Beginning of table</option>
					<?php
					foreach ($columns as $column){
						//DIAG: echo '<pre>'; print_r($column); echo '</pre>';
						$field_name = '';
						$words = explode("_", $column['Field']);
						foreach ($words as $word) $field_name .= $word.' ';
						if ( $column['Field'] != 'id' && $column['Field'] != 'date_updated' )
							printf ("<option value=\"%s\">%s</option>", $column['Field'], ucwords($field_name));
					}
					?>
				</select>				
			</td>
		</tr>
		<tr><td></td><td><input type="submit" value="Submit"></td></tr>
	</table>
</form>
</div>