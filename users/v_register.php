<?php 
	if (file_exists('../config.php'))
		include_once('../config.php'); 
	else 
		die('Unable to load init file (includes/config.php)');	
	
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";

	$u = new User;
	
	$userId=@$_COOKIE['idusers'];
	if ($userId and $_COOKIE['name'] != 'Nobody')	
		$user=$u->getUserById($userId);
?>

	<!-- content -->
<table>
            <tr>
                <td class="rightBorder"><img src="images/login.png" border="0"/></td>
                <td valign="top" align="center">
                    <div id="registerFormBox">	
                        <form id="registerForm" action="users/d_register_r.php" method="post">
                        <table align="center">
                            <tbody>
                                <tr>
                                    <td class="option">Name *</td>
                                    <td class="value">
                                        <input id="uname" name="uname" type="text" size="50" <?php if ($userId) echo 'value="'.$user->name.'" '?> title="<br><?=$provide_name?>" class="{required:true}" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option" >Company/Institution *</td>
                                    <td class="value">
                                        <input name="company" type="text" size="50" <?php if ($userId) echo 'value="'.$user->company.'" '?> title="<br><?=$provide_company?>" class="{required:true}" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Email *</td>
                                    <td class="value">
                                        <input name="email" type="text" size="50" <?php if ($userId) echo 'value="'.$user->email.'" '?> title="<br><?=$provide_email?>" class="{required:true}" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Password *</td>
                                    <td class="value">
                                        <input <?php if (!$userId) echo 'title="<br>'.$provide_passwd.'" class="{required:true}"' ?> name="passw" type="password" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Retype Password *</td>
                                    <td class="value">
                                        <input <?php if (!$userId) echo 'title="<br>'.$retype_passwd.'" class="{required:true}"' ?> name="passw1" type="password" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Address</td>
                                    <td class="value">
                                        <input  name="address" type="text" size="50" <?php if ($userId) echo 'value="'.$user->address.'" '?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">City</td>
                                    <td class="value">
                                        <input name="city" type="text" size="50" <?php if ($userId) echo 'value="'.$user->city.'" '?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Country</td>
                                    <td class="value">
                                        <select name="country">
                                        <option value="0">Please select one</option>
                                        <?php 
                                            $strSQL="SELECT * FROM country ORDER BY printable_name";
                                            $countries = $db->get_results($strSQL);
                                            foreach ($countries as $cts){
                                                echo '<option value="'.$cts->printable_name.'"';
                                                if ($userId) 
                                                    if ($cts->printable_name==$user->country)
                                                        echo ' selected ';
                                                else 
                                                    if ($cts->printable_name=='Spain')
                                                        echo ' selected ';
                                                echo '>'.$cts->printable_name.'</option>';
                                            }
                                        ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Phone</td>
                                    <td class="value">
                                        <input name="phone" type="text" size="50" <?php if ($userId) echo 'value="'.$user->phone.'" '?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Fax</td>
                                    <td class="value">
                                        <input  name="fax" type="text" size="50" <?php if ($userId) echo 'value="'.$user->fax.'" '?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Website</td>
                                    <td class="value">
                                        <input name="website" type="text" size="50" <?php if ($userId) echo 'value="'.$user->website.'" '?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Publish your data</td>
                                    <td class="value">
                                        <input type="radio" name="public_data" value="0" b:required="true" <?php if ($userId && $user->public_data == 0) echo 'checked="true"';?> /> Do not publish anything<br />
                                        <input type="radio" name="public_data" value="1" b:required="true" <?php if ($userId && $user->public_data == 1) {echo 'checked="true"';} elseif (!$userId) {echo 'checked="true"';} ?> /> Only the Name and Organization<br />
                                        <input type="radio" name="public_data" value="2" b:required="true" <?php if ($userId && $user->public_data == 2) echo 'checked="true"';?> /> All contact information <br />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Notify me when a new user is created</td>
                                    <td class="value">
                                        <input name="notify_users" type="checkbox" value="1" <?php if ($userId && $user->notify_users == 1) echo ' checked="true" '; else if (!$userId) echo ' checked="true" '; ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Notify me when a new project is created</td>
                                    <td class="value">
                                        <input name="notify_projects" type="checkbox" value="1" <?php if ($userId && $user->notify_projects == 1) echo 'checked="true"'; else if (!$userId) echo 'checked="true"'; ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Notify me when a new annotation is submitted</td>
                                    <td class="value">
                                        <input name="notify_annotations" type="checkbox" value="1" <?php if ($userId && $user->notify_annotations == 1) echo 'checked="true"'; else if (!$userId) echo 'checked="true"'; ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="option">Notify me when a new plug-in is uploaded </td>
                                    <td class="value">
                                        <input  name="notify_plugins" type="checkbox" <?php if ($userId && $user->notify_plugins == 1) echo 'checked="true"';?> />
                                    </td>
                                </tr>
                                <tr>
                                    <td />
                                    <td class="value">
                                        <input type="submit" value="Send" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </form>
                    </div>
                </td>
            </tr>
        </table>	

<script language="javascript">
  $jQ(document).ready(function() { 
          $jQ('#uname').focus(); 
          $jQ('#registerForm').ajaxForm({target: '#registerFormBox'})
  });
</script>