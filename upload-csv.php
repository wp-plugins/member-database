<?php

define('MEMBER_DATABASE_FOLDER', dirname(plugin_basename(__FILE__)));
define('MEMBER_DATABASE_URL', WP_PLUGIN_URL.'/'.MEMBER_DATABASE_FOLDER);
//DIAG: echo 'FOLDER: '.MEMBER_DATABASE_FOLDER.', URL: '.MEMBER_DATABASE_URL;

$table_name = $wpdb->prefix . "member_db";

function csv_to_db_url_to_absolute_path($src_file_url) {
	// Converts $src_file_url into an absolute file path, starting at the server's root directory.
	// Warning: Assumes $src_file_url is at, or below, the server's document root directory.  If the
	// $src_file_url is outside the scope of the server's document root directory, a FALSE value will be returned.
	// FALSE is also returned if $src_file_url is not a qualified URL.
	// -- The Assurer, 2010-10-06.
	if (preg_match("/^http/i", $src_file_url) != 1) return FALSE;	// Not a qualified URL.
	$domain_url = $_SERVER['SERVER_NAME'];				// Get domain name.
	$absolute_path_root = $_SERVER['DOCUMENT_ROOT'];		// Get absolute document root path.
	// Calculate position in $src_file_url just after the domain name...
	$domain_name_pos = stripos($src_file_url, $domain_url);
	if($domain_name_pos === FALSE) return FALSE;			// Rats!  URL is not on this server.
	$domain_name_length = strlen($domain_url);
	$total_length = $domain_name_pos+$domain_name_length;
	// Replace http*://SERVER_NAME in $src_file_url with the absolute document root path.
	return substr_replace($src_file_url, $absolute_path_root, 0, $total_length);	
}
	
function insertRecords($src_file,$table_name)
{
	global $wpdb;
	$errorMsg = "";

	if(empty($src_file)) {
		$errorMsg .= "<br />Input file is not specified";
		return $errorMsg;
	}

	//$file_handle = fopen("products.csv", "r");
	$file_path = csv_to_db_url_to_absolute_path($src_file);
	$file_handle = fopen($file_path, "r");
	
	//create $column_string
	$columns = $wpdb->get_results("SHOW COLUMNS FROM ".$table_name, ARRAY_A);
	$col1 = TRUE;
	foreach ($columns as $column){
		if ($col1) {
			$column_string = $column['Field'];
			$col1 = FALSE;
		}
		else $column_string .= ', '.$column['Field'];
	}
	
	while (!feof($file_handle) ) 
	{
		$line_of_text = fgetcsv($file_handle, 1024);
		$columns = count($line_of_text);
		//echo "<br />Column Count: ".$columns."<br />";
		
		if ($columns>1)
		{
	        	$query_vals = "'".$wpdb->escape($line_of_text[0])."'";
	        	for($c=1;$c<$columns;$c++)
	        	{
	                    $query_vals .= ",'".$wpdb->escape($line_of_text[$c])."'";
	        	}
	        	//echo "<br />Query Val: ".$query_vals."<br />";
                        $query = "INSERT INTO $table_name ($column_string) VALUES ($query_vals)";
	                
                        //echo "<br />Query String: ". $query;
                        $results = $wpdb->query($query);
                        if(empty($results))
                        {
                            $errorMsg .= "<br />Insert into the Database failed for the following Query:<br />";
                            $errorMsg .= $query;
                        }
	                //echo "<br />Query result".$results;
	    }
	}
	fclose($file_handle);
	
	return $errorMsg;
}


if(isset($_POST['file_upload'])) {
    $target_path = WP_CONTENT_DIR.'/plugins/'.MEMBER_DATABASE_FOLDER."/uploads/";
	$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

	//DIAG: echo "<br />Target Path: ".$target_path;
	echo '<div id="message" class="updated fade"><p><strong>';
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
	    echo "The file ".  basename( $_FILES['uploadedfile']['name'])." has been uploaded.";
	    $file_name = MEMBER_DATABASE_URL.'/uploads/'.basename( $_FILES['uploadedfile']['name']);
		$errorMsg = insertRecords($file_name,$table_name);
	    if(empty($errorMsg)) {
	        echo '</strong></p></div>';
			echo '<div id="message" class="updated fade"><p><strong>';
			echo 'File content has been successfully imported into the database!';
	    }
	    else {
	        echo '</strong></p></div>';
			echo "Error occured while trying to import!<br />";
	        echo $errorMsg;
	    }
	} 
	else {
	    echo "There was an error uploading the file, please try again!";
	}
	echo '</strong></p></div>';
}

?>

<div class="wrap">
<div id="poststuff"><div id="post-body">
	
<h2>Import CSV File</h2>

<!--
	<div class="postbox">
		<h3><label for="title">Quick Usage Guide</label></h3>
		<div class="inside">
			<p>1. Check that the columns in your .csv file match the columns in the database.</p>
    		<p>2. Select the .csv file with your membership information and upload it.</p>
    	</div>
	</div>
-->        
    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    	<input type="hidden" name="info_update" id="info_update" value="true" />
		<div class="postbox">
			<h3><label for="title">1. Check that the columns in your .csv file match the database columns</label></h3>
			<div class="inside">
				<p>To properly import your membership data, the columns in your .csv file must match exactly the columns in the database. Currently, the columns are as follows:</p>
				<p>
					<?php
					$columns = $wpdb->get_results("SHOW COLUMNS FROM ".$table_name, ARRAY_A);
					$col1 = TRUE;
					foreach ($columns as $column){
						$field_name = '';
						$words = explode("_", $column['Field']);
						foreach ($words as $word) $field_name .= $word.' ';
						if ($col1) {
							echo ucwords($field_name);
							$col1 = FALSE;
						}
						else echo ', '.ucwords($field_name);
					}
					?>
				</p>
				<p><strong>NOTE: </strong>If you are appending data to your membership database, make sure that the ID field is empty. When the new records are added, they will be given a new ID number. If the field is not empty, and you already have a record with that ID number, the database will generate an error.</p> 
			</div>
		</div>
	</form>
	
	<div class="postbox">
		<h3><label for="title">2. Upload the .csv file</label></h3>
		<div class="inside">
			<form enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
				<input type="hidden" name="file_upload" id="file_upload" value="true" />
				<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
				Choose .csv file to import: <input name="uploadedfile" type="file" /><br />
				<input type="submit" value="Upload File" />
			</form>
        </div>
	</div>
</div>
</div></div>
