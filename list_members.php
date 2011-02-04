<?php
	function field_name ($field) {
		$name = '';
		$words = explode("_", $field);
		foreach ($words as $word) $name .= $word.' ';	
		return ucwords($name);
	}
	
	//DIAG: echo '<pre>'; print_r($_POST); echo '</pre>';

	$table_name = $wpdb->prefix . "member_db";
	
	//get table info
	$sql = "SHOW COLUMNS FROM ".$table_name;
	$columns = $wpdb->get_results($sql, ARRAY_A);
	
	if (isset($_POST['id']) && $_POST['action']=='delete'){		
		foreach ($_POST['id'] as $key => $value) {
			$sql = "DELETE FROM ".$table_name." WHERE id = ".$value;
			$wpdb->query($sql);
			//DIAG: echo $value.', ';
		}
	}
	
	if ($_POST['action']=='sort') {
		$sql = "SELECT * FROM ".$table_name;
		if ($_POST['where'] != 'none'){
			$sql .= " WHERE ".$_POST['where'].$_POST['operator']."'".$_POST['value']."'";
		}
		$sql .= " ORDER BY ".$_POST['sortBy']." ".$_POST['ascdesc'];
	}
	else $sql = "SELECT * FROM ".$table_name." ORDER BY last_name";
?>

<script type="text/javascript" language="javascript">  
	function really() {
		var x=window.confirm("Do you really want to delete these members?")
		if (x)
			return true
		else
			return false
	}
	
	/* function cadged from http://www.hscripts.com/scripts/JavaScript/select-all-checkbox.php */
	checked=false;
	function checkedAll (frm) {
		var aa= document.getElementById('frm');
		if (checked == false) {
	           checked = true
		}
        else {
			checked = false
		}
		for (var i =0; i < aa.elements.length; i++) {
			aa.elements[i].checked = checked;
		}
	}
	
</script>

	<div class="wrap">
	<h2>List Members</h2>
		
	<form method="post">
		<input type="hidden" name="action" value="sort">
		<p>Select WHERE:
		<select name="where">
			<option value="none"></option>
			<?php
			foreach ($columns as $column){
				//DIAG: echo '<pre>'; print_r($column); echo '</pre>';
				printf ("<option value=\"%s\"", $column['Field']);
				if ( $column['Field'] == $_POST['where'] ) echo ' selected="selected"';
				printf (">%s</option>", field_name($column['Field']));
			}				
			?>
		</select>
		<select name="operator">
			<option value="=">=</option>
			<option value="!=">!=</option>
			<option value="LIKE">LIKE</option>
			<option value="NOT LIKE">NOT LIKE</option>
		</select>
		<input type="text" name="value" value="<?php echo $_POST['value']; ?>">
		</p>
		<p>Sort by:
		<select name="sortBy">
			<?php
			foreach ($columns as $column){
				//DIAG: echo '<pre>'; print_r($column); echo '</pre>';
				printf ("<option value=\"%s\"", $column['Field']);
				if ( $column['Field'] == $_POST['sortBy'] ) echo ' selected="selected"';
				printf (">%s</option>", field_name($column['Field']));
			}				
			?>
		</select>
		<input type="radio" name="ascdesc" value="asc"<?php if (!isset($_POST['ascdesc']) || $_POST['ascdesc']=='asc') echo ' checked="checked"'; ?>> Ascending <input type="radio" name="ascdesc" value="desc"<?php if ($_POST['ascdesc']=='desc') echo 'checked="checked"'; ?>> Descending <input type="submit" value="Sort">
		</p>
	</form>
	
	<form id="frm" action="admin.php?page=member-database/list_members.php" method="post">
		<input type="hidden" name="action" value="delete">
		<table style="width:400px;">
		<?php
			echo '<tr><td></td><td>';
			if (isset($_POST['sortBy'])) echo field_name ($_POST['sortBy']);
			echo '</td><td>Select all: <input type="checkbox" name="checkall" onclick="checkedAll(frm);"> <input type="submit" name="submit" value="Delete checked" onclick="return really();"></tr>';
				
			//DIAG: echo '<pre>'; print_r($wpdb); echo '</pre>';
			if ($members=$wpdb->get_results($sql, ARRAY_A)) {
				//now output the table of members
				foreach ($members as $value) {
					printf ("<tr><td>%s %s</td><td>", $value['first_name'], $value['last_name']);
					if (isset($_POST['sortBy'])) echo $value[$_POST['sortBy']];
					printf ("</td><td><a href=\"admin.php?page=member-database/edit_member.php&action=edit&id=%s\">Edit</a> | <input type=\"checkbox\" name=\"id[]\" value=\"%s\"></td></tr>"
						, $value['id'], $value['id']);
				}
			}
			else echo '<tr><td>No members found</td></tr>';
		?>
		</table>
	</form>
	</div>

		
