<?php 
	if (file_exists('../config.php'))
		include_once('../config.php'); 
	else 
		die('Unable to load init file (includes/config.php)');	
?>
<!-- content -->
<table>
  <tr>
    <td class="rightBorder"><img src="images/login.png" border="0" alt="login" /></td>
    <td valign="top" align="center">
      <div id="loginFormBox">
        <form id="loginForm" action="users/d_login_r.php" method="post">
          <table align="center">
            <tbody>
              <tr>
                <td class="option">Email *</td>
                <td class="value">
                        <input id="email" name="email" type="text" style="width:220px;" title="<br><?=$provide_email?>" class="{required:true}" />
                </td>
              </tr>
              <tr>
                <td class="option">Password *</td>
                <td class="value">
                        <input name="passw" type="password" style="width:220px;" title="<br><?=$provide_passwd?>" class="{required:true}" />
                </td>
              </tr>
              <tr>
                <td />
                <td class="value">
                        <input type="submit" value="Login" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
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
  		  $jQ('#email').focus(); 
          $jQ('#loginForm').ajaxForm({target: '#loginFormBox'});	
  });
</script>