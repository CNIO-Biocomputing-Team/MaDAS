<?php 
  //requiered initializations
  session_start();
  ini_set('include_path',$_SESSION['include_path']);

  //includes  clases
  include_once "ez_sql.php";
  include_once "class.user.php";

  $u = new User;
  $userId=@$_SESSION['idusers'];
?>
<table height="100%" cellspacing="10">
  <tr>
    <td valign="top" align="left">
      <a onClick='javascript:$jQ(this).loadProjects()'>&lt;&lt; Back</a><br><br>
      <p style="text-align:justify">
        To <b>join a project</b> just select the project and then click on the <b>"Join Project" button</b>. You will need the <b>project leader approval</b> to complete the process. 
      </p>
      <br />
      <table id="projectsC" style="display:none"></table>
    </td>
  </tr>
</table>
