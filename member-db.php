<?php  
    /* 
    Plugin Name: Member Database 
    Plugin URI: http://www.webpublishinggroup.com 
    Description: Plugin for managing and displaying information about an organization's members
    Author: Dan Romanchik
    Version: 0.1
    Author URI: http://www.webpublishinggroup.com 
    */  

	add_action('admin_menu', 'md_plugin_menu');
	register_activation_hook(__FILE__,'member_db_install');
	add_shortcode('member_directory', 'output_member_directory');

	function output_member_directory() {
		global $wpdb;
		$table_name = $wpdb->prefix . "member_db";
		if ($members = $wpdb->get_results("SELECT * FROM ".$table_name." ORDER BY last_name", ARRAY_A)) {
			foreach ($members as $member) {
				$dir .= sprintf ("
						<p>
							%s %s<br /> 
							%s, %s, %s<br />
							Phone: %s, Mobile: %s, E-mail: %s<br />
							%s 
						</p>", 
						$member['first_name'], $member['last_name'],
						$member['city'], $member['state'], $member['country'],
						$member['phone'], $member['mobile'], $member['email'],
						$member['bio']
					);
			}
			return $dir;
		}
		else return "No members found.";
	}
	
	function md_plugin_menu() {
		add_menu_page('Member Database', 'Member Database', 9, 'member-db-plugin', 'md_plugin_page');
			add_submenu_page('member-db-plugin', 'List Members', 'List Members', 9, 'member-db/list_members.php');
			add_submenu_page('member-db-plugin', 'Add Member', 'Add Member', 9, 'member-db/add_member.php');
			add_submenu_page('member-db-plugin', 'Edit Table', 'Edit Table', 9, 'member-db/edit_table.php');
			add_submenu_page('', 'Edit Member', 'Edit Member', 9, 'member-db/edit_member.php');
		add_options_page('Member Database', 'Member Database', 'manage_options', 'my-unique-identifier', 'md_plugin_options');
	}

	function md_plugin_options() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		echo '<div class="wrap">';
		echo '<p>Member Database option settings to go here.</p>';
		echo '</div>';
	}	

	function md_plugin_page() {
		echo '<div class="wrap">';
		echo '<h2>Member Database Plugin</h2>';
		echo '<p>To list all members, click the "List Members" link in the Member Database menu.</p>';
		echo '<p>To edit a particular member\'s information, click the "List Members" link in the Member Database menu, then the "Edit" link for the particular member.</p>';
		echo '<p>To delete a member, click the "List Members" link in the Member Database menu, then the "Delete" link for the particular member.</p>';
		echo '<p>To place a listing of all members on a page or a post, use the shortcode "[member_directory]". Put this code wherever you want the directory to be displayed.</p>';
		echo '<p>To download a comma-delimited file of the member data, <a href="../wp-content/plugins/member-db/list_members_csv.php">click here</a>.</p>';
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