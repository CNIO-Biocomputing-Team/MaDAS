/*
* jQuery MaDas Map plugin
* @requires jQuery v1.0.3
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*
* Revision: $Id$
* Version: .1
*/

function init(){ 
    
    //base WMS layer
    var baseOptions = {   
         maxExtent: new OpenLayers.Bounds(0,-10000,size,10000),
         units     :'bp',
         tileSize: new OpenLayers.Size(436, 530),
/*          resolutions:[150000,75000,37500,18750,9375,4687,2343,1171,585,292,146,73,35,17,8,4,2,1,0.5,0.025,0.125,0.0625], */
		 resolutions: [size/872,size/1744,size/3488,size/6976,size/13952,size/27904,size/55808,size/111616,size/223232,size/446464,size/892928,size/1785856],
         controls: [new OpenLayers.Control.MouseDefaults()],
         theme: 'madas'
         
    }
    map = new OpenLayers.GenomeBrowser( $('map') ,baseOptions);
    
    layer = new OpenLayers.Layer.Genomic( "Annot" , server
         ,{'chr':chr,layers:"gene",'version':6,organism:org}
         ,{transitionEffect:'resize'}
         ,{isBaseLayer:true});
         
    map.addLayer(layer);
   
    
    /*zoomLevel */
    map.setCenter(new OpenLayers.BasePair(size/2), 1);
    bp = new OpenLayers.Control.MousePosition() 
    map.addControl(bp );
    map.addControl(new OpenLayers.Control.PanZoomBar());

    
    map.events.register('zoomend',map,updateZoom);
    map.events.triggerEvent('zoomend');

    map.events.register('mousedown',map,mapMouseDown);
    map.events.register('mouseup',map,mapMouseUp);


}

function mapMouseDown(e){
    map.lastMouseX = e.xy.x;
    map.lastMouseY = e.xy.y;
    $jQ("#bubble").hide();
	$jQ("#bubble").empty();
	$jQ("#bubbler").hide();
	$jQ("#bubbler").empty();
}

function mapMouseUp(e){
    var tx = e.xy.x;
    var ty = e.xy.y;
    var tot = Math.abs(map.lastMouseX - tx)+ Math.abs(map.lastMouseY - ty);
    if(2 > tot){
    	// dont need to figure out basepairs from pixels
        // just use the ones in the window already.
        getFeature(tx,ty,parseInt(bp.element.innerHTML));
    }
}

function updateZoom(){
    //$('resolution').innerHTML = map.getResolution() + 'bp/pixel';

}
function getFeature(tx,ty,sstart){
  
  var sstop = 0;
  
  if (parseFloat(map.getResolution()) >1){
  	
  	sstart 	= parseInt(sstart)-3*parseInt(map.getResolution());
   	sstop 	= parseInt(sstart)+3*parseInt(map.getResolution());
  
  }else{
  	
  	sstart 	= parseInt(sstart);
  	sstop	= sstart+1;
  }
  var h = ty;
  
  if (ty > 200){
      		

      $jQ('#bubble').load('plugins_dir/visualization/array_express/features.php?r=1&start='+sstart+'&stop='+sstop+'&h='+h,function(data){
      	
      	//open if we have results
      	if ($jQ('.fdetails').length > 0){
      	    
      	    $jQ("#bubble").css({
  		      left:tx+$jQ('#map').offset().left-68,
      		  top:170+ty-$jQ("#bubble").height()
        	}).show();
      	}
      });
  
  }else{
  

      $jQ('#bubbler').load('plugins_dir/visualization/array_express/features.php?r=0&start='+sstart+'&stop='+sstop+'&h='+h,function(){
       	
       	//open if we have results
      	if ($jQ('.fdetails').length > 0){

      	      
      	      $jQ("#bubbler").css({
		      	left:tx+$jQ('#map').offset().left-68,
		      	top:180+ty
		  
		      }).show();
		}
      });
  }

 }
 
function addTypes (){
    
    var cookie = '';
 	$jQ('#view_types option').each(function(i){
 		
 		if (this.selected == true){
 			cookie = cookie+','+this.value;
 		}
 	});
 	$jQ('#view_features').slideUp(400);
 	document.cookie = 'view_types =' + cookie;
 	
 	/* map.zoomTo(map.getZoom()); */
 	map.zoomIn();
 	map.zoomOut();
};

jQuery.fn.getPocket = function(x,y,start,stop,type)  {

	$jQ("#canvas").click(function(e){
      
      	if (type){
	        
	        $jQ("#bubble").hide();
	        $jQ("#bubbler").hide();
	        
      		if (e.pageY > 400){
      		
		      	$jQ("#bubble").css({
		
		        left:e.pageX-65,
		        top:e.pageY-$jQ("#bubble").height()
		
		    	}).show();
		    	$jQ('#bubble').load('plugins_dir/visualization/array_express/getPockets.php?r=1&start='+start+'&stop='+stop+'&type='+type);
	    	
	    	}else{

	    		$jQ("#bubbler").css({
		
		        	left:e.pageX-65,
		        	top:e.pageY
		
		    	}).show();
		    	$jQ('#bubbler').load('plugins_dir/visualization/array_express/getPockets.php?r=0&start='+start+'&stop='+stop+'&type='+type);
	    	}
	    	

	    	type= '';
	
	    	e.stopPropagation();
	    	
			$jQ(document).one("click", function(f) {
	            $jQ("#bubble").hide();
	            $jQ("#bubbler").hide();
    	    });

    	}
   	}); 

}

jQuery.fn.editF = function(parameters){
    
    $jQ("#bubble").hide();
	$jQ("#bubble").empty();
	$jQ("#bubbler").hide();
	$jQ("#bubbler").empty();

	if (parameters){
 		$jQ('#editF').load('plugins_dir/visualization/array_express/v_feature_edit.php?param='+parameters);
 	}else{
 		$jQ('#editF').load('plugins_dir/visualization/array_express/v_feature_edit.php');
 	}
	$jQ('#editF').dialog({title:'Add/Edit Feature', modal:true,overlay:{opacity: 0.2,background: "black"} ,width:500,height:460,close:function(){
 			/* $jQ('#editF').empty(); */
 	}});

}
