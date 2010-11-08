/*
 * jQuery plugins plugin
 * @requires jQuery v1.0.3
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id$
 * Version: .1
 */
 
jQuery.fn.loadPlugins = function() {
	$jQ("#mBody").load("plugins/index.php");
	$jQ("#rightSide").load("plugins/right.php");
}
 
/* data sources */ 
jQuery.fn.dataSourcesList = function(f) {
	$jQ("#pluginsCanvas").load("projects/myProjectManageSource.php?favorites="+f+"&plugin=on");

}


/* data sources */ 
jQuery.fn.visualizationList = function(f) {
	$jQ("#pluginsCanvas").load("projects/myProjectBrowseAnnotations.php?favorites="+f+"&plugin=on");

}
 



 