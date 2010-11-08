<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";
	include_once "class.projects.php";

	$u = new User;
	$p = new Project;
	
	$userId=@$_SESSION['idusers'];
	$id=$_REQUEST['id'];
	
	
	
	$user = $u->getUserById($userId);
	$member = $p->getProjectMemberById($id);
?>
<div id="projectDetails">
<form name="f-member-edit" method="post" id="f-member-edit" action="projects/v_myProject_members_edit_r.php">
   <input type="hidden" name="id" value="<?=$id?>" />
   <input type="hidden" name="n_value_modified" value="now()" />
   <table height="100%" align="center" cellpadding="10">
       <tr>
       	<td class="option">Name:</td>
       	<td  class="value">
       		<?=$member->name?>
       	</td>
       </tr>
       <tr>
       	<td class="option">Organization:</td>
       	<td  class="value">
       		<?=$member->company?>
       	</td>
       </tr>
       <tr>
            <td class="option">Rol:</td>
            <td class="value">
                <?
   		     	$name = 'n_value_idroles';
   		     	$class = 'selected';
                   include('ajax-select-general.php');
                ?>
            </td>
       </tr>
       <tr>
            <td class="option">Status:</td>
            <td class="value">
                <?
   		     	$name = 'n_value_iduser_status';
   		     	$class = 'selected';
                   include('ajax-select-general.php');
                ?>
            </td>
       </tr>
       <tr>
       	<td class="option">Join day:</td>
       	<td class="value">
       		<?=$member->created;?>
       	</td>
       </tr>
   	<tr>
           <td>&nbsp;</td>
           <td class="option"><input class="button" type="submit" name="save" id="save" value="Save"></td>
       </tr>
   </table>
</form>
</div>
<script type="text/javascript">

	// pre-submit callback 
	function showRequest(formData, jqForm, options) { 
		
		//requeridos
		var flag = 0;
		$jQ('.required').each(
			function( intIndex ){
				if ($jQ(this).val() == ''){
					$jQ(this).addClass("red");
					flag=1;
				}else{
					$jQ(this).removeClass("red");
				}
			} 
		)
		
		if (flag){
			alert('Please fill all the required filds');
			return false;
		}	
		
		
		
		return true; 
	} 
	 
	
  $jQ(document).ready(function(){
		
		 //role
		 <?
			  $table = 'roles';
			  $vfield = 'idroles';
			  $master = '';
			  $masterv = '';
			  $nfield = 'role';
  			  $selected =@$member->idroles;
		?>
		$jQ("#n_value_idroles").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-options-general.php?table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>");
		
		//status
		 <?
			  $table = 'user_status';
			  $vfield = 'iduser_status';
			  $master = '';
			  $masterv = '';
			  $nfield = 'status';
  			  $selected =@$member->iduser_status;
		?>
		
		$jQ("#n_value_iduser_status").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-options-general.php?table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>");
		
		
		/*form send*/
		var options = { 
			target:   '#projectDetails',   // target element(s) to be updated with server response 
			beforeSubmit:  showRequest
		}; 
	 
		// bind form using 'ajaxForm' 
		$jQ('#f-member-edit').ajaxForm(options); 
		
  });
</script>