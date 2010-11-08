/*
* jQuery projects plugin
* @requires jQuery v1.0.3
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*
* Revision: $Id$
* Version: .1
*/

jQuery.fn.loadProjects = function() {
	$jQ("#mBody").load("projects/index.php");
	$jQ("#rightSide").load("projects/v_myProjects_list.php");
}

/* projects */
jQuery.fn.projectsList = function(query) {
  $jQ("#rightSide").load("projects/v_myProjects_list.php");	
  $jQ("#projectsCanvas").load("projects/v_projects_list.php",function(){
                      $jQ("#projectsC").flexigrid
                          (
                          {
                          url: 'projects/d_projects_list.php?pquery='+query,
                          dataType: 'xml',
                          colModel : [
                                  {display: 'ID', name : 'idprojects', width : 40, sortable : true, align: 'center', hide: true},
                                  {display: 'Name', name : 'p.name', width : 300, sortable : true, align: 'left'},
                                  {display: 'Category', name : 'category', width : 110, sortable : true, align: 'left'},
                                  {display: 'Members', name : 'members', width : 45, sortable : true, align: 'center'},
                                  {display: 'Created by', name : 'user', width : 180, sortable : true, align: 'left'},
                                  {display: 'Create Date', name : 'created', width : 130, sortable : true, align: 'right'}
                                  ],
                          buttons : [
                          {name: 'Join Project', bclass: 'delete', onpress : $jQ(this).projectsView}
                          ],
                          searchitems : [
                                  {display: 'Name', name : 'p.name', isdefault: true},
                                  {display: 'Category', name : 'pr.name', isdefault: true},
                                  {display: 'Created by', name : 'u.name', isdefault: true}

                                  ],
                          sortname: "created",
                          sortorder: "asc",
                          usepager: true,
                          title: 'PROJECT LIST',
                          useRp: true,
                          rp: 50,
                          showTableToggleBtn: false,
                          width: 830,
                          height: 350
                          }
                          );
                      });
}


jQuery.fn.projectsView = function(com,grid) {
	
	
	var items = $jQ('.trSelected');
        var id = '';
		
        if (items.length == 0){
                alert('Select one project to view');
        }else if (items.length >1){
                alert('Select only one project to view');
        }else{
                id = items[0].id.substring(3);
        }	
	
	if (id)	{
		$jQ('#projects_c').empty();
 		$jQ('#projects_c').load('projects/v_projects_view.php?pid='+id);
 		$jQ('#projects_c').dialog({title:'Project Details',modal:true,overlay:{opacity: 0.2,background: "black"} ,width:400,height:400});	      
	}
}

jQuery.fn.joinToProj = function(uid,pid,security) {
 
	if(!confirm("Are you sure that you want join this project?")) {return false;} 
	else {
		$jQ("#projectDetails").load("projects/d_projects_view_r.php?idusers=" + uid + "&pid=" + pid + "&security=" + security);
	}
}
 
jQuery.fn.projectsAdd= function() {
	
	$jQ('#projects_c').empty();
 	$jQ('#projects_c').load('projects/v_projects_add.php');
 	$jQ('#projects_c').dialog({title:'Create Project',modal:true,overlay:{opacity: 0.2,background: "black"} ,width:700,height:400,close:function(){

			$jQ("#rightSide").load("projects/v_myProjects_list.php");
 	}});
}


jQuery.fn.projectsEdit= function(pid) {
	
	$jQ('#projects_c').empty();
 	$jQ('#projects_c').load('projects/v_projects_add.php?pid='+pid);
 	$jQ('#projects_c').dialog({title:'Edit Project',modal:true,overlay:{opacity: 0.2,background: "black"} ,width:700,height:400});
}


/* my projects */
jQuery.fn.myProjectView= function (pid) {
	$jQ("#projectsCanvas").load("projects/myProjectWork.php?pid="+pid);
}


jQuery.fn.myProjectViewTransfer= function (pid) {
	project=$jQ(pid).attr('id');
	$jQ("#projectsCanvas").load("projects/myProjectWork.php?pid="+project,function(){
							$jQ("#1arrow").animate({opacity: '0.3',visibility: 'visible'},1);
							$jQ("#2arrow").animate({opacity: '0.3',visibility: 'visible'},1);	
																		 }
					 );
	$jQ("#rightSide").load("projects/myProjectView.php?pid="+project);
} 



jQuery.fn.myProjectDelete = function() {

	if(!confirm("This action will completely remove your project from MaDas. Are you sure that you want to do this?")) {
		return false;
	} else {
		$jQ.get("projects/deleteProject.php");
		$jQ("#mBody").load("projects/index.php");
		$jQ("#rightSide").load("projects/v_myProjects_list.php");
	}
}

jQuery.fn.makeProjDraggables = function() {
	$jQ("table.project").Draggable(
							{
								zIndex: 	1000,
								opacity: 	0.7,
								revert:     true,
								fx:			100,
								onStart:    function(){
									$jQ("#projectCanvas").Shake(2);
								}
							}
						); 
	$jQ('#projectCanvas').Droppable(
		{
			accept : 'project', 
			activeclass: 'projectCanvasactive', 
			hoverclass:	'projectCanvashover',
			ondrop:	function (drag) 
					{
						//alert(this); //the droppable
						//alert(drag); //the dragganle
						$jQ("#mBody").load("projects/myProjectWork.php?pid="+$jQ(drag).attr('id'));
						$jQ("#rightSide").load("projects/projectInfo.php?pid="+$jQ(drag).attr('id'));
					},
			fit: true
		}
	);
}


jQuery.fn.editMember = function(id,pid){
	if (id)	{
				
 		$jQ('#projects_c').load('projects/v_myProject_members_edit?id='+id);
 		$jQ('#projects_c').dialog({title:'Edit member',modal:true,overlay:{opacity: 0.2,background: "black"} ,width:300,height:300,close:function(){
 			$jQ("#rightSide").load("projects/myProjectView.php?pid="+pid);
 		}});	      
	}
}


jQuery.fn.activateDas = function() {
		$jQ("#1arrow").animate({opacity: '1',visibility: 'visible'},1);
		$jQ("#das").animate({opacity: '1',visibility: 'visible'},1);	
	        $jQ("#dasSelect").animate({opacity: '1',visibility: 'visible'},1);
}

jQuery.fn.activateRun = function() {
		$jQ("#2arrow").animate({opacity: '1',visibility: 'visible'},1);
		$jQ("#run").animate({opacity: '1',visibility: 'visible'},1);	
}

jQuery.fn.myProjectManageSource = function(f) {
	$jQ("#projectsCanvas").load("projects/myProjectManageSource.php?favorites="+f);
}
jQuery.fn.myProjectBrowseAnnotations = function(f) {
	$jQ("#projectsCanvas").load("projects/myProjectBrowseAnnotations.php?favorites="+f);
}
jQuery.fn.demoProject = function(f){
	$jQ("#mBody").css("visibility","hidden");
	$jQ("#mBody").load("projects/index.php",function(){
		$jQ("#projectsCanvas").css("visibility","hidden");
		$jQ("#projectsCanvas").load("projects/myProjectWork.php?pid=21",function(){
			
			$jQ("#projectsCanvas").load("projects/myProjectBrowseAnnotations.php?favorites=0",function(){
				
				$jQ('#projectsCanvas').load('projects/myProjectBrowseAnnotations.php?sid=2',function(){
					$jQ("#projectsCanvas").css('visibility','visible');
					$jQ("#mBody").css('visibility','visible');
				});
				
			});
		
		});
	});
	$jQ("#rightSide").load("projects/myProjectView.php?pid=21");
}


jQuery.fn.gotoProject = function(pid){
	$jQ("#mBody").css("visibility","hidden");
	$jQ("#mBody").load("projects/index.php",function(){
		$jQ("#projectsCanvas").css("visibility","hidden");
		$jQ("#projectsCanvas").load("projects/myProjectWork.php?pid="+pid,function(){
			$jQ("#projectsCanvas").css('visibility','visible');
			$jQ("#mBody").css('visibility','visible');
		});
	});
	$jQ("#rightSide").load("projects/myProjectView.php?pid="+pid);
}

jQuery.fn.gotoPlugin = function(projectid,pluginid){
	$jQ("#mBody").css("visibility","hidden");
	$jQ("#mBody").load("projects/index.php",function(){
		$jQ("#projectsCanvas").css("visibility","hidden");
		$jQ("#projectsCanvas").load("projects/myProjectWork.php?pid="+projectid,function(){
			
			$jQ("#projectsCanvas").load("projects/myProjectBrowseAnnotations.php?favorites=0",function(){
				
				$jQ('#projectsCanvas').load('projects/myProjectBrowseAnnotations.php?sid='+pluginid,function(){
					$jQ("#projectsCanvas").css('visibility','visible');
					$jQ("#mBody").css('visibility','visible');
				});
				
			});
		
		});
	});
	$jQ("#rightSide").load("projects/myProjectView.php?pid="+projectid);
}