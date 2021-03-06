/*
 * jQuery users plugin
 * @requires jQuery v1.0.3
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id$
 * Version: .1
 */
  jQuery.fn.login = function(pid) {
	 	
	$jQ('#loginBox').empty();
	 	$jQ('#loginBox').load('users/v_login.php');
	 	$jQ('#loginBox').dialog({title:'Login',modal:true,width:470,height:180,close:function(event, ui){
	 			$jQ("#mMenu").load("header/menu.php",{async: false});
	 			if (pid){
	 				$jQ(this).gotoProject(pid);
	 			}else{
					$jQ("#mBody").load("home/index.php");
					$jQ("#rightSide").load("home/right.php");
				}
	 	}});


	
 }
 
  jQuery.fn.register = function(pid) {
 	$jQ('#loginBox').empty();
 	$jQ('#loginBox').load('users/v_register.php');
 	$jQ('#loginBox').dialog({title:'Register/Edit', modal:true,overlay:{opacity: 0.2,background: "black"} ,width:750,height:650,close:function(){
 			$jQ("#mMenu").load("header/menu.php",{async: false});
 			
 			if (pid){
 				$jQ(this).gotoProject(pid);
 			
 			}else{
				$jQ("#mBody").load("home/index.php");
				$jQ("#rightSide").load("home/right.php");
			}	
 	}});
	
 }
 
 
 jQuery.fn.logout = function() {
    $jQ.get("users/d_logout.php");
	$jQ("#mMenu").load("header/menu.php",{async: false});
	$jQ("#mBody").load("home/index.php");
	$jQ("#rightSide").load("home/right.php");
 }