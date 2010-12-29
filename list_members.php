<?php
	$table_name = $wpdb->prefix . "member_db";
	
	if ($_GET['action']=='delete'){
		$sql = "DELETE FROM ".$table_name." WHERE id = ".$_GET['id'];
		$wpdb->query($sql);
	}
?>
<script type="text/javascript" language="javascript">  
	function really() {
		var x=window.confirm("Do you really want to delete this member?")
		if (x)
			return true
		else
			return false
	}
</script>

	<div class="wrap">
	<h2>List Members</h2>
	<table style="width:600px;">
<?php
	//DIAG: echo '<pre>'; print_r($wpdb); echo '</pre>';
	if ($members=$wpdb->get_results("SELECT * FROM ".$table_name." ORDER BY last_name", ARRAY_A)) {
		foreach ($members as $value) {
			printf ("<tr>
				<td>%s %s</td>
				<td><a href=\"admin.php?page=member-database/edit_member.php&action=edit&id=%s\">Edit</a> | <a href=\"admin.php?page=member-database/list_members.php&action=delete&id=%s\" onClick=\"return really();\">Delete</a></td></tr>"
				, $value['first_name'], $value['last_name'], $value['id'], $value['id']);
		}
	}
?>
	</table>
	</div>