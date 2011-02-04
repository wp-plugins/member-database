<?php  
    /* 
    Plugin Name: Member Database 
    Plugin URI: http://www.webpublishinggroup.com 
    Description: Plugin for managing and displaying information about an organization's members
    Author: Dan Romanchik
    Version: 1.0beta
    Author URI: http://www.webpublishinggroup.com 
    */  

	add_action('admin_menu', 'md_plugin_menu');
	add_action('admin_init', 'md_options_init_fn' );
	register_activation_hook(__FILE__,'member_db_install');
	add_shortcode('member_directory', 'output_member_directory');

	function output_member_directory() {
		global $wpdb;
		$table_name = $wpdb->prefix . "member_db";
		$options = get_option('md_options');
		if ($members = $wpdb->get_results("SELECT * FROM ".$table_name." ORDER BY ".$options['orderBy'], ARRAY_A)) {
			foreach ($members as $member) {
				$dir .= sprintf ("
						<p>
						%s%s%s%s%s%s%s%s<br /> 
						%s%s%s%s%s%s%s%s<br /> 
						%s%s%s%s%s%s%s%s<br /> 
						%s%s%s%s%s%s%s%s<br /> 
						</p>", 
						$options['text1'], $member[$options['field1']], $options['text2'], $member[$options['field2']], $options['text3'], $member[$options['field3']], $options['text4'], $member[$options['field4']], $options['text5'], $member[$options['field5']], $options['text6'], $member[$options['field6']], $options['text7'], $member[$options['field7']], $options['text8'], $member[$options['field8']], $options['text9'], $member[$options['field9']], $options['text10'], $member[$options['field10']], $options['text11'], $member[$options['field11']], $options['text12'], $member[$options['field12']], $options['text13'], $member[$options['field13']], $options['text14'], $member[$options['field14']], $options['text15'], $member[$options['field15']], $options['text16'], $member[$options['field16']]
					);
			}
			return $dir;
		}
		else return "No members found.";
	}

	function md_plugin_menu() {
		add_menu_page('Member Database', 'Member Database', 9, 'member-database-plugin', 'md_plugin_page');
		add_submenu_page('member-database-plugin', 'List Members', 'List Members', 9, 'member-database/list_members.php');
		add_submenu_page('member-database-plugin', 'Add Member', 'Add Member', 9, 'member-database/add_member.php');
		add_submenu_page('member-database-plugin', 'Edit Table', 'Edit Table', 9, 'member-database/edit_table.php');
		add_submenu_page('member-database-plugin', 'Import CSV File', 'Import CSV File', 9, 'member-database/upload-csv.php');
		add_submenu_page('', 'Edit Member', 'Edit Member', 9, 'member-database/edit_member.php');
		add_options_page('Member Database', 'Member Database', 'administrator', __FILE__, 'md_options_page_fn');
	}

//start setttings functions
// Register our settings. Add the settings section, and settings fields
function md_options_init_fn(){
	register_setting('md_options', 'md_options', 'md_options_validate' );
	add_settings_field('text1', 'Text 1', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text2', 'Text 2', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text3', 'Text 3', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text4', 'Text 4', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text5', 'Text 1', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text6', 'Text 2', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text7', 'Text 3', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text8', 'Text 4', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text9', 'Text 1', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text10', 'Text 2', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text11', 'Text 3', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text12', 'Text 4', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text13', 'Text 1', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text14', 'Text 2', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text15', 'Text 3', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('text16', 'Text 4', 'setting_string_fn', __FILE__, 'line_1');
	add_settings_field('field1', 'Field 1', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field2', 'Field 2', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field3', 'Field 3', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field4', 'Field 4', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field5', 'Field 1', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field6', 'Field 2', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field7', 'Field 3', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field8', 'Field 4', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field9', 'Field 1', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field10', 'Field 2', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field11', 'Field 3', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field12', 'Field 4', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field13', 'Field 1', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field14', 'Field 2', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field15', 'Field 3', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('field16', 'Field 4', 'setting_field_fn', __FILE__, 'line_1');
	add_settings_field('orderBy', 'Order By', 'setting_field_fn', __FILE__, 'line_1');
}

// Display the admin options page
function md_options_page_fn() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Member Directory Format</h2>
		<p>Use this page to specify the output format of the member directory.<br />You can have up to four lines per entry and display up to four fields per line.</p>
		<form action="options.php" method="post">
		<?php settings_fields('md_options'); ?>
		<?php //do_settings_sections(__FILE__); ?>
		<?php $options = get_option('md_options'); //echo '<pre>'; print_r($options); echo '</pre>'; ?>
		<table>
	        <tr valign="top">
				<td>Line 1: </td><td><input id="memberdb_text_string" name="md_options[text1]" size="2" type="text" value="<?php echo $options['text1']; ?>" /></td><td><?php setting_field_fn('field1'); ?></td><td><input id="memberdb_text_string" name="md_options[text2]" size="2" type="text" value="<?php echo $options['text2']; ?>" /></td><td><?php setting_field_fn('field2'); ?></td><td><input id="memberdb_text_string" name="md_options[text3]" size="2" type="text" value="<?php echo $options['text3']; ?>" /></td><td><?php setting_field_fn('field3'); ?></td><td><input id="memberdb_text_string" name="md_options[text4]" size="2" type="text" value="<?php echo $options['text4']; ?>" /></td><td><?php setting_field_fn('field4'); ?></td>
	        </tr>
	        <tr valign="top">
				<td>Line 2: </td><td><input id="memberdb_text_string" name="md_options[text5]" size="2" type="text" value="<?php echo $options['text5']; ?>" /></td><td><?php setting_field_fn('field5'); ?></td><td><input id="memberdb_text_string" name="md_options[text6]" size="2" type="text" value="<?php echo $options['text6']; ?>" /></td><td><?php setting_field_fn('field6'); ?></td><td><input id="memberdb_text_string" name="md_options[text7]" size="2" type="text" value="<?php echo $options['text7']; ?>" /></td><td><?php setting_field_fn('field7'); ?></td><td><input id="memberdb_text_string" name="md_options[text8]" size="2" type="text" value="<?php echo $options['text8']; ?>" /></td><td><?php setting_field_fn('field8'); ?></td>
	        </tr>
	        <tr valign="top">
				<td>Line 3: </td><td><input id="memberdb_text_string" name="md_options[text9]" size="2" type="text" value="<?php echo $options['text9']; ?>" /></td><td><?php setting_field_fn('field9'); ?></td><td><input id="memberdb_text_string" name="md_options[text10]" size="2" type="text" value="<?php echo $options['text10']; ?>" /></td><td><?php setting_field_fn('field10'); ?></td><td><input id="memberdb_text_string" name="md_options[text11]" size="2" type="text" value="<?php echo $options['text11']; ?>" /></td><td><?php setting_field_fn('field11'); ?></td><td><input id="memberdb_text_string" name="md_options[text12]" size="2" type="text" value="<?php echo $options['text12']; ?>" /></td><td><?php setting_field_fn('field12'); ?></td>
	        </tr>
	        <tr valign="top">
				<td>Line 4: </td><td><input id="memberdb_text_string" name="md_options[text13]" size="2" type="text" value="<?php echo $options['text13']; ?>" /></td><td><?php setting_field_fn('field13'); ?></td><td><input id="memberdb_text_string" name="md_options[text14]" size="2" type="text" value="<?php echo $options['text14']; ?>" /></td><td><?php setting_field_fn('field14'); ?></td><td><input id="memberdb_text_string" name="md_options[text15]" size="2" type="text" value="<?php echo $options['text15']; ?>" /></td><td><?php setting_field_fn('field15'); ?></td><td><input id="memberdb_text_string" name="md_options[text16]" size="2" type="text" value="<?php echo $options['text16']; ?>" /></td><td><?php setting_field_fn('field16'); ?></td>
	        </tr>
	        <tr valign="top">
				<td>Order By: </td><td><?php setting_field_fn('orderBy'); ?></td>
	        </tr>
	    </table>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>
<?php
}

// Callback functions
function  setting_field_fn($fld) {
	global $wpdb;
	$options = get_option('md_options');
	//get table info
	$table_name = $wpdb->prefix . "member_db";
	$sql = "SHOW COLUMNS FROM ".$table_name;
	$columns = $wpdb->get_results($sql, ARRAY_A);
	echo "<select id=\"md_options[".$fld."]\" name=\"md_options[".$fld."]\">";
	echo '<option value=""></option>';
	foreach($columns as $column) {
		$field_name = '';
		$words = explode("_", $column['Field']);
		foreach ($words as $word) $field_name .= $word.' ';
		if ( $column['Field'] != 'id' ) {
			$selected = ($options[$fld]==$column['Field']) ? 'selected="selected"' : '';
			echo "<option value=\"".$column['Field']."\" ".$selected.">".ucwords($field_name)."</option>";
		}
	}
	echo "</select>";
}


// Validate user data for some/all of your input fields
function md_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['text_string'] =  wp_filter_nohtml_kses($input['text_string']);	
	return $input; // return validated input
}
//end settings functions

	function md_plugin_page() {
		echo '<div class="wrap">';
		echo '<h2>Member Database Plugin</h2>';
		echo '<p>To list all members, click the "List Members" link in the Member Database menu.</p>';
		echo '<p>To edit a particular member\'s information, click the "List Members" link in the Member Database menu, then the "Edit" link for the particular member.</p>';
		echo '<p>To delete a member or members, click the "List Members" link in the Member Database menu, then check the members that you wish to delete. Finally, click the "Delete checked" box, and all of the members you selected will be deleted from the database. </p>';
		echo '<p>To place a listing of all members on a page or a post, use the shortcode "[member_directory]". Put this code wherever you want the directory to be displayed. To format the way in which the member information is displayed, click the "Member Database" link under the Settings menu. If you do not set up this format, no date will be displayed by [member-directoy].</p>';
		echo '<p>To download a comma-delimited file of the member data, <a href="../wp-content/plugins/member-database/list_members_csv.php">click here</a>.</p>';
		echo '</div>';
	}	
	
	global $member_db_db_version;
	$member_db_db_version = "1.0";
	function member_db_install () {
		global $wpdb;
		global $member_db_db_version;
		$table_name = $wpdb->prefix . "member_db";
		if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
	 		$sql = "CREATE TABLE " . $table_name . " (
				`id` int(11) NOT NULL auto_increment,
				`first_name` varchar(24) NOT NULL,
				`last_name` varchar(24) NOT NULL,
				`title` varchar(24) default NULL,
				`address_1` varchar(36) default NULL,
				`address_2` varchar(36) default NULL,
				`city` varchar(24) default NULL,
				`state` varchar(24) default NULL,
				`country` varchar(36) default NULL,
				`postal_code` varchar(10) default NULL,
				`phone` varchar(16) default NULL,
				`mobile` varchar(16) default NULL,
				`email` varchar(36) default NULL,
				`bio` text,
				`member_type` varchar(12) default NULL,
				`date_joined` date default NULL,
				`date_expires` date default NULL,
				`date_updated` date default NULL,
				PRIMARY KEY  (`id`)
				);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			add_option("member_db_db_version", $member_db_db_version);
		}
	}
	
?>