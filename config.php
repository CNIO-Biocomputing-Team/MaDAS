<?php 
	#general configuration
	$debug = 0;
	$MaDAS_url = 'http://localhost/madas/';
	$MaDAS_home = '/Users/victordelatorre/Dropbox/Trabajo/CNIO/MaDAS/html';
	$include_path = '/Users/victordelatorre/Dropbox/Trabajo/CNIO/MaDAS/html/libs';
	#$MaDAS_url = 'http://madas2.bioinfo.cnio.es/';
	#$MaDAS_home = '/www-raid/biotools/madas2/';
	#$include_path = '/www-raid/biotools/madas2/libs';
	$session_expire_time = 60;
	$contact_email= 'vdelatorre@cnio.es';
	$mail_note='MaDas 2.0 Release.';
	$max_file_size = 2000000;
	$plugins_dir = 'plugins_dir';
	$data_sources_dir = 'plugins_dir/dataSources';
	$das_servers_dir = 'plugins_dir/dasServers';
	$visualization_dir = 'plugins_dir/visualization';
	$uploads_path = 'uploads';
	#errors
	$provide_email = 'Please enter a valid email address';
	$provide_passwd = 'Please provide a password';
	$retype_passwd = 'Please retype your password';
	$provide_name = 'Please provide your name';
	$provide_company = 'Please provide your company or institution';
	$duplicated_user = 'Sorry but your email is already registered in MaDas.';
	$user_created = 'Your account has been created successfully. Welcome to the MaDas system.';
	$user_updated = 'Your profile has been updated successfully.';
	$demo_restricted = 'Sorry, you need to register in MaDAS to add or edit annotations. ';
	$invalid_email = 'Your email is not registered in the MaDas system.';
	$invalid_user_password = 'Invalid username or password.';
	$password_changed = 'Your password has been changed. You will receive an email with the new password.';
	$user_logged= 'Welcome to MaDas. Have fun!';
	$must_be_logged = 'Sorry but you must be logged in to use this function.';
	$required_fields = 'Please fill in all required fields.';
	$mail_sent = 'Your e-mail has been sent';
	#projects
	$duplicated_project_name = 'Sorry but a project with this name is already registered in MaDas.';
	$project_created = 'Your project has been created successfully. Enjoy it.';
	$project_updated = 'Your project has been updated successfully.';
	$project_deleted = 'Your project has been deleted.';
	$project_member_added = 'You have been successfully added to the project.';
	$project_member_exist = 'You are already member of this project.';
	$project_member_queue = 'Your petition to join the project have been sent to the project leader.';
	$project_configured = 'Your project has been configured successfully';
	#plugins
	$duplicated_plugin_name = 'Sorry but a plugin with this name is already registered in MaDas.';
	$plugin_created = 'Your plugin has been uploaded successfully. Thanks!.';
	$plugin_upload_failed = 'ERROR: Some error ocurred, your plugin could not be uploaded.';
	$plugin_not_found = 'ERROR: The selected plugin has not be found.';
	$file_required = 'ERROR: You must provide a file.';
	$invalid_file_type = 'ERROR: Invalid file type.';
	$max_file_exceded = 'ERROR: Max file size exceded ('.$max_file_size.')';
	$information_not_available = 'The information is not available for security reasons.';
	$unexpected_error = 'Sorry an unexpected error has occurred, please wait a few minutes and try again.';
	$favorite_created = 'Your selection has been added to your favorites.';
	$favorite_deleted = 'Your selection has been deleted from your favorites.';
	$favorite_exist = 'Your selection is already in your favorites.';
	
	
?>