
OpenLayers.Map=OpenLayers.Class({Z_INDEX_BASE:{BaseLayer:100,Overlay:325,Feature:725,Popup:750,Control:1000},EVENT_TYPES:["preaddlayer","addlayer","removelayer","changelayer","movestart","move","moveend","zoomend","popupopen","popupclose","addmarker","removemarker","clearmarkers","mouseover","mouseout","mousemove","dragstart","drag","dragend","changebaselayer"],id:null,fractionalZoom:false,events:null,div:null,dragging:false,size:null,viewPortDiv:null,layerContainerOrigin:null,layerContainerDiv:null,layers:null,controls:null,popups:null,baseLayer:null,center:null,resolution:null,zoom:0,panRatio:1.5,viewRequestID:0,tileSize:null,projection:"EPSG:4326",units:'degrees',resolutions:null,maxResolution:1.40625,minResolution:null,maxScale:null,minScale:null,maxExtent:null,minExtent:null,restrictedExtent:null,numZoomLevels:16,theme:null,displayProjection:null,fallThrough:true,panTween:null,eventListeners:null,panMethod:OpenLayers.Easing.Expo.easeOut,paddingForPopups:null,initialize:function(div,options){this.tileSize=new OpenLayers.Size(OpenLayers.Map.TILE_WIDTH,OpenLayers.Map.TILE_HEIGHT);this.maxExtent=new OpenLayers.Bounds(-180,-90,180,90);this.paddingForPopups=new OpenLayers.Bounds(15,15,15,15);this.theme=OpenLayers._getScriptLocation()+'theme/default/style.css';OpenLayers.Util.extend(this,options);this.id=OpenLayers.Util.createUniqueID("OpenLayers.Map_");this.div=OpenLayers.Util.getElement(div);OpenLayers.Element.addClass(this.div,'olMap');var id=this.div.id+"_OpenLayers_ViewPort";this.viewPortDiv=OpenLayers.Util.createDiv(id,null,null,null,"relative",null,"hidden");this.viewPortDiv.style.width="100%";this.viewPortDiv.style.height="100%";this.viewPortDiv.className="olMapViewport";this.div.appendChild(this.viewPortDiv);id=this.div.id+"_OpenLayers_Container";this.layerContainerDiv=OpenLayers.Util.createDiv(id);this.layerContainerDiv.style.zIndex=this.Z_INDEX_BASE['Popup']-1;this.viewPortDiv.appendChild(this.layerContainerDiv);this.events=new OpenLayers.Events(this,this.div,this.EVENT_TYPES,this.fallThrough,{includeXY:true});this.updateSize();if(this.eventListeners instanceof Object){this.events.on(this.eventListeners);}
this.events.register("movestart",this,this.updateSize);if(OpenLayers.String.contains(navigator.appName,"Microsoft")){this.events.register("resize",this,this.updateSize);}else{this.updateSizeDestroy=OpenLayers.Function.bind(this.updateSize,this);OpenLayers.Event.observe(window,'resize',this.updateSizeDestroy);}
if(this.theme){var addNode=true;var nodes=document.getElementsByTagName('link');for(var i=0,len=nodes.length;i<len;++i){if(OpenLayers.Util.isEquivalentUrl(nodes.item(i).href,this.theme)){addNode=false;break;}}
if(addNode){var cssNode=document.createElement('link');cssNode.setAttribute('rel','stylesheet');cssNode.setAttribute('type','text/css');cssNode.setAttribute('href',this.theme);document.getElementsByTagName('head')[0].appendChild(cssNode);}}
this.layers=[];if(this.controls==null){if(OpenLayers.Control!=null){this.controls=[new OpenLayers.Control.Navigation(),new OpenLayers.Control.PanZoom(),new OpenLayers.Control.ArgParser(),new OpenLayers.Control.Attribution()];}else{this.controls=[];}}
for(var i=0,len=this.controls.length;i<len;i++){this.addControlToMap(this.controls[i]);}
this.popups=[];this.unloadDestroy=OpenLayers.Function.bind(this.destroy,this);OpenLayers.Event.observe(window,'unload',this.unloadDestroy);},unloadDestroy:null,updateSizeDestroy:null,destroy:function(){if(!this.unloadDestroy){return false;}
OpenLayers.Event.stopObserving(window,'unload',this.unloadDestroy);this.unloadDestroy=null;if(this.updateSizeDestroy){OpenLayers.Event.stopObserving(window,'resize',this.updateSizeDestroy);}else{this.events.unregister("resize",this,this.updateSize);}
this.paddingForPopups=null;if(this.controls!=null){for(var i=this.controls.length-1;i>=0;--i){this.controls[i].destroy();}
this.controls=null;}
if(this.layers!=null){for(var i=this.layers.length-1;i>=0;--i){this.layers[i].destroy(false);}
this.layers=null;}
if(this.viewPortDiv){this.div.removeChild(this.viewPortDiv);}
this.viewPortDiv=null;if(this.eventListeners){this.events.un(this.eventListeners);this.eventListeners=null;}
this.events.destroy();this.events=null;},setOptions:function(options){OpenLayers.Util.extend(this,options);},getTileSize:function(){return this.tileSize;},getBy:function(array,property,match){var test=(typeof match.test=="function");var found=OpenLayers.Array.filter(this[array],function(item){return item[property]==match||(test&&match.test(item[property]));});return found;},getLayersBy:function(property,match){return this.getBy("layers",property,match);},getLayersByName:function(match){return this.getLayersBy("name",match);},getLayersByClass:function(match){return this.getLayersBy("CLASS_NAME",match);},getControlsBy:function(property,match){return this.getBy("controls",property,match);},getControlsByClass:function(match){return this.getControlsBy("CLASS_NAME",match);},getLayer:function(id){var foundLayer=null;for(var i=0,len=this.layers.length;i<len;i++){var layer=this.layers[i];if(layer.id==id){foundLayer=layer;break;}}
return foundLayer;},setLayerZIndex:function(layer,zIdx){layer.setZIndex(this.Z_INDEX_BASE[layer.isBaseLayer?'BaseLayer':'Overlay']
+zIdx*5);},resetLayersZIndex:function(){for(var i=0,len=this.layers.length;i<len;i++){var layer=this.layers[i];this.setLayerZIndex(layer,i);}},addLayer:function(layer){for(var i=0,len=this.layers.length;i<len;i++){if(this.layers[i]==layer){var msg=OpenLayers.i18n('layerAlreadyAdded',{'layerName':layer.name});OpenLayers.Console.warn(msg);return false;}}
this.events.triggerEvent("preaddlayer",{layer:layer});layer.div.className="olLayerDiv";layer.div.style.overflow="";this.setLayerZIndex(layer,this.layers.length);if(layer.isFixed){this.viewPortDiv.appendChild(layer.div);}else{this.layerContainerDiv.appendChild(layer.div);}
this.layers.push(layer);layer.setMap(this);if(layer.isBaseLayer){if(this.baseLayer==null){this.setBaseLayer(layer);}else{layer.setVisibility(false);}}else{layer.redraw();}
this.events.triggerEvent("addlayer",{layer:layer});},addLayers:function(layers){for(var i=0,len=layers.length;i<len;i++){this.addLayer(layers[i]);}},removeLayer:function(layer,setNewBaseLayer){if(setNewBaseLayer==null){setNewBaseLayer=true;}
if(layer.isFixed){this.viewPortDiv.removeChild(layer.div);}else{this.layerContainerDiv.removeChild(layer.div);}
OpenLayers.Util.removeItem(this.layers,layer);layer.removeMap(this);layer.map=null;if(this.baseLayer==layer){this.baseLayer=null;if(setNewBaseLayer){for(var i=0,len=this.layers.length;i<len;i++){var iLayer=this.layers[i];if(iLayer.isBaseLayer){this.setBaseLayer(iLayer);break;}}}}
this.resetLayersZIndex();this.events.triggerEvent("removelayer",{layer:layer});},getNumLayers:function(){return this.layers.length;},getLayerIndex:function(layer){return OpenLayers.Util.indexOf(this.layers,layer);},setLayerIndex:function(layer,idx){var base=this.getLayerIndex(layer);if(idx<0){idx=0;}else if(idx>this.layers.length){idx=this.layers.length;}
if(base!=idx){this.layers.splice(base,1);this.layers.splice(idx,0,layer);for(var i=0,len=this.layers.length;i<len;i++){this.setLayerZIndex(this.layers[i],i);}
this.events.triggerEvent("changelayer",{layer:layer,property:"order"});}},raiseLayer:function(layer,delta){var idx=this.getLayerIndex(layer)+delta;this.setLayerIndex(layer,idx);},setBaseLayer:function(newBaseLayer){var oldExtent=null;if(this.baseLayer){oldExtent=this.baseLayer.getExtent();}
if(newBaseLayer!=this.baseLayer){if(OpenLayers.Util.indexOf(this.layers,newBaseLayer)!=-1){if(this.baseLayer!=null){this.baseLayer.setVisibility(false);}
this.baseLayer=newBaseLayer;this.viewRequestID++;this.baseLayer.visibility=true;var center=this.getCenter();if(center!=null){var newCenter=(oldExtent)?oldExtent.getCenterLonLat():center;var newZoom=(oldExtent)?this.getZoomForExtent(oldExtent,true):this.getZoomForResolution(this.resolution,true);this.setCenter(newCenter,newZoom,false,true);}
this.events.triggerEvent("changebaselayer",{layer:this.baseLayer});}}},addControl:function(control,px){this.controls.push(control);this.addControlToMap(control,px);},addControlToMap:function(control,px){control.outsideViewport=(control.div!=null);if(this.displayProjection&&!control.displayProjection){control.displayProjection=this.displayProjection;}
control.setMap(this);var div=control.draw(px);if(div){if(!control.outsideViewport){div.style.zIndex=this.Z_INDEX_BASE['Control']+
this.controls.length;this.viewPortDiv.appendChild(div);}}},getControl:function(id){var returnControl=null;for(var i=0,len=this.controls.length;i<len;i++){var control=this.controls[i];if(control.id==id){returnControl=control;break;}}
return returnControl;},removeControl:function(control){if((control)&&(control==this.getControl(control.id))){if(control.div&&(control.div.parentNode==this.viewPortDiv)){this.viewPortDiv.removeChild(control.div);}
OpenLayers.Util.removeItem(this.controls,control);}},addPopup:function(popup,exclusive){if(exclusive){for(var i=this.popups.length-1;i>=0;--i){this.removePopup(this.popups[i]);}}
popup.map=this;this.popups.push(popup);var popupDiv=popup.draw();if(popupDiv){popupDiv.style.zIndex=this.Z_INDEX_BASE['Popup']+
this.popups.length;this.layerContainerDiv.appendChild(popupDiv);}},removePopup:function(popup){OpenLayers.Util.removeItem(this.popups,popup);if(popup.div){try{this.layerContainerDiv.removeChild(popup.div);}
catch(e){}}
popup.map=null;},getSize:function(){var size=null;if(this.size!=null){size=this.size.clone();}
return size;},updateSize:function(){this.events.clearMouseCache();var newSize=this.getCurrentSize();var oldSize=this.getSize();if(oldSize==null){this.size=oldSize=newSize;}
if(!newSize.equals(oldSize)){this.size=newSize;for(var i=0,len=this.layers.length;i<len;i++){this.layers[i].onMapResize();}
if(this.baseLayer!=null){var center=new OpenLayers.Pixel(newSize.w/2,newSize.h/2);var centerLL=this.getLonLatFromViewPortPx(center);var zoom=this.getZoom();this.zoom=null;this.setCenter(this.getCenter(),zoom);}}},getCurrentSize:function(){var size=new OpenLayers.Size(this.div.clientWidth,this.div.clientHeight);if(size.w==0&&size.h==0||isNaN(size.w)&&isNaN(size.h)){var dim=OpenLayers.Element.getDimensions(this.div);size.w=dim.width;size.h=dim.height;}
if(size.w==0&&size.h==0||isNaN(size.w)&&isNaN(size.h)){size.w=parseInt(this.div.style.width);size.h=parseInt(this.div.style.height);}
return size;},calculateBounds:function(center,resolution){var extent=null;if(center==null){center=this.getCenter();}
if(resolution==null){resolution=this.getResolution();}
if((center!=null)&&(resolution!=null)){var size=this.getSize();var w_deg=size.w*resolution;var h_deg=size.h*resolution;extent=new OpenLayers.Bounds(center.lon-w_deg/2,center.lat-h_deg/2,center.lon+w_deg/2,center.lat+h_deg/2);}
return extent;},getCenter:function(){return this.center;},getZoom:function(){return this.zoom;},pan:function(dx,dy,options){options=OpenLayers.Util.applyDefaults(options,{animate:true,dragging:false});var centerPx=this.getViewPortPxFromLonLat(this.getCenter());var newCenterPx=centerPx.add(dx,dy);if(!options.dragging||!newCenterPx.equals(centerPx)){var newCenterLonLat=this.getLonLatFromViewPortPx(newCenterPx);if(options.animate){this.panTo(newCenterLonLat);}else{this.setCenter(newCenterLonLat,null,options.dragging);}}},panTo:function(lonlat){if(this.panMethod&&this.getExtent().scale(this.panRatio).containsLonLat(lonlat)){if(!this.panTween){this.panTween=new OpenLayers.Tween(this.panMethod);}
var center=this.getCenter();if(lonlat.lon==center.lon&&lonlat.lat==center.lat){return;}
var from={lon:center.lon,lat:center.lat};var to={lon:lonlat.lon,lat:lonlat.lat};this.panTween.start(from,to,50,{callbacks:{start:OpenLayers.Function.bind(function(lonlat){this.events.triggerEvent("movestart");},this),eachStep:OpenLayers.Function.bind(function(lonlat){lonlat=new OpenLayers.LonLat(lonlat.lon,lonlat.lat);this.moveTo(lonlat,this.zoom,{'dragging':true,'noEvent':true});},this),done:OpenLayers.Function.bind(function(lonlat){lonlat=new OpenLayers.LonLat(lonlat.lon,lonlat.lat);this.moveTo(lonlat,this.zoom,{'noEvent':true});this.events.triggerEvent("moveend");},this)}});}else{this.setCenter(lonlat);}},setCenter:function(lonlat,zoom,dragging,forceZoomChange){this.moveTo(lonlat,zoom,{'dragging':dragging,'forceZoomChange':forceZoomChange,'caller':'setCenter'});},moveTo:function(lonlat,zoom,options){if(!options){options={};}
var dragging=options.dragging;var forceZoomChange=options.forceZoomChange;var noEvent=options.noEvent;if(this.panTween&&options.caller=="setCenter"){this.panTween.stop();}
if(!this.center&&!this.isValidLonLat(lonlat)){lonlat=this.maxExtent.getCenterLonLat();}
if(this.restrictedExtent!=null){if(lonlat==null){lonlat=this.getCenter();}
if(zoom==null){zoom=this.getZoom();}
var resolution=this.getResolutionForZoom(zoom);var extent=this.calculateBounds(lonlat,resolution);if(!this.restrictedExtent.containsBounds(extent)){var maxCenter=this.restrictedExtent.getCenterLonLat();if(extent.getWidth()>this.restrictedExtent.getWidth()){lonlat=new OpenLayers.LonLat(maxCenter.lon,lonlat.lat);}else if(extent.left<this.restrictedExtent.left){lonlat=lonlat.add(this.restrictedExtent.left-
extent.left,0);}else if(extent.right>this.restrictedExtent.right){lonlat=lonlat.add(this.restrictedExtent.right-
extent.right,0);}
if(extent.getHeight()>this.restrictedExtent.getHeight()){lonlat=new OpenLayers.LonLat(lonlat.lon,maxCenter.lat);}else if(extent.bottom<this.restrictedExtent.bottom){lonlat=lonlat.add(0,this.restrictedExtent.bottom-
extent.bottom);}
else if(extent.top>this.restrictedExtent.top){lonlat=lonlat.add(0,this.restrictedExtent.top-
extent.top);}}}
var zoomChanged=forceZoomChange||((this.isValidZoomLevel(zoom))&&(zoom!=this.getZoom()));var centerChanged=(this.isValidLonLat(lonlat))&&(!lonlat.equals(this.center));if(zoomChanged||centerChanged||!dragging){if(!this.dragging&&!noEvent){this.events.triggerEvent("movestart");}
if(centerChanged){if((!zoomChanged)&&(this.center)){this.centerLayerContainer(lonlat);}
this.center=lonlat.clone();}
if((zoomChanged)||(this.layerContainerOrigin==null)){this.layerContainerOrigin=this.center.clone();this.layerContainerDiv.style.left="0px";this.layerContainerDiv.style.top="0px";}
if(zoomChanged){this.zoom=zoom;this.resolution=this.getResolutionForZoom(zoom);this.viewRequestID++;}
var bounds=this.getExtent();this.baseLayer.moveTo(bounds,zoomChanged,dragging);bounds=this.baseLayer.getExtent();for(var i=0,len=this.layers.length;i<len;i++){var layer=this.layers[i];if(!layer.isBaseLayer){var inRange=layer.calculateInRange();if(layer.inRange!=inRange){layer.inRange=inRange;if(!inRange){layer.display(false);}
this.events.triggerEvent("changelayer",{layer:layer,property:"visibility"});}
if(inRange&&layer.visibility){layer.moveTo(bounds,zoomChanged,dragging);layer.events.triggerEvent("moveend",{"zoomChanged":zoomChanged});}}}
if(zoomChanged){for(var i=0,len=this.popups.length;i<len;i++){this.popups[i].updatePosition();}}
this.events.triggerEvent("move");if(zoomChanged){this.events.triggerEvent("zoomend");}}
if(!dragging&&!noEvent){this.events.triggerEvent("moveend");}
this.dragging=!!dragging;},centerLayerContainer:function(lonlat){var originPx=this.getViewPortPxFromLonLat(this.layerContainerOrigin);var newPx=this.getViewPortPxFromLonLat(lonlat);if((originPx!=null)&&(newPx!=null)){this.layerContainerDiv.style.left=Math.round(originPx.x-newPx.x)+"px";this.layerContainerDiv.style.top=Math.round(originPx.y-newPx.y)+"px";}},isValidZoomLevel:function(zoomLevel){return((zoomLevel!=null)&&(zoomLevel>=0)&&(zoomLevel<this.getNumZoomLevels()));},isValidLonLat:function(lonlat){var valid=false;if(lonlat!=null){var maxExtent=this.getMaxExtent();valid=maxExtent.containsLonLat(lonlat);}
return valid;},getProjection:function(){var projection=this.getProjectionObject();return projection?projection.getCode():null;},getProjectionObject:function(){var projection=null;if(this.baseLayer!=null){projection=this.baseLayer.projection;}
return projection;},getMaxResolution:function(){var maxResolution=null;if(this.baseLayer!=null){maxResolution=this.baseLayer.maxResolution;}
return maxResolution;},getMaxExtent:function(options){var maxExtent=null;if(options&&options.restricted&&this.restrictedExtent){maxExtent=this.restrictedExtent;}else if(this.baseLayer!=null){maxExtent=this.baseLayer.maxExtent;}
return maxExtent;},getNumZoomLevels:function(){var numZoomLevels=null;if(this.baseLayer!=null){numZoomLevels=this.baseLayer.numZoomLevels;}
return numZoomLevels;},getExtent:function(){var extent=null;if(this.baseLayer!=null){extent=this.baseLayer.getExtent();}
return extent;},getResolution:function(){var resolution=null;if(this.baseLayer!=null){resolution=this.baseLayer.getResolution();}
return resolution;},getUnits:function(){var units=null;if(this.baseLayer!=null){units=this.baseLayer.units;}
return units;},getScale:function(){var scale=null;if(this.baseLayer!=null){var res=this.getResolution();var units=this.baseLayer.units;scale=OpenLayers.Util.getScaleFromResolution(res,units);}
return scale;},getZoomForExtent:function(bounds,closest){var zoom=null;if(this.baseLayer!=null){zoom=this.baseLayer.getZoomForExtent(bounds,closest);}
return zoom;},getResolutionForZoom:function(zoom){var resolution=null;if(this.baseLayer){resolution=this.baseLayer.getResolutionForZoom(zoom);}
return resolution;},getZoomForResolution:function(resolution,closest){var zoom=null;if(this.baseLayer!=null){zoom=this.baseLayer.getZoomForResolution(resolution,closest);}
return zoom;},zoomTo:function(zoom){if(this.isValidZoomLevel(zoom)){this.setCenter(null,zoom);}},zoomIn:function(){this.zoomTo(this.getZoom()+1);},zoomOut:function(){this.zoomTo(this.getZoom()-1);},zoomToExtent:function(bounds,closest){var center=bounds.getCenterLonLat();if(this.baseLayer.wrapDateLine){var maxExtent=this.getMaxExtent();bounds=bounds.clone();while(bounds.right<bounds.left){bounds.right+=maxExtent.getWidth();}
center=bounds.getCenterLonLat().wrapDateLine(maxExtent);}
this.setCenter(center,this.getZoomForExtent(bounds,closest));},zoomToMaxExtent:function(options){var restricted=(options)?options.restricted:true;var maxExtent=this.getMaxExtent({'restricted':restricted});this.zoomToExtent(maxExtent);},zoomToScale:function(scale,closest){var res=OpenLayers.Util.getResolutionFromScale(scale,this.baseLayer.units);var size=this.getSize();var w_deg=size.w*res;var h_deg=size.h*res;var center=this.getCenter();var extent=new OpenLayers.Bounds(center.lon-w_deg/2,center.lat-h_deg/2,center.lon+w_deg/2,center.lat+h_deg/2);this.zoomToExtent(extent,closest);},getLonLatFromViewPortPx:function(viewPortPx){var lonlat=null;if(this.baseLayer!=null){lonlat=this.baseLayer.getLonLatFromViewPortPx(viewPortPx);}
return lonlat;},getViewPortPxFromLonLat:function(lonlat){var px=null;if(this.baseLayer!=null){px=this.baseLayer.getViewPortPxFromLonLat(lonlat);}
return px;},getLonLatFromPixel:function(px){return this.getLonLatFromViewPortPx(px);},getPixelFromLonLat:function(lonlat){var px=this.getViewPortPxFromLonLat(lonlat);px.x=Math.round(px.x);px.y=Math.round(px.y);return px;},getViewPortPxFromLayerPx:function(layerPx){var viewPortPx=null;if(layerPx!=null){var dX=parseInt(this.layerContainerDiv.style.left);var dY=parseInt(this.layerContainerDiv.style.top);viewPortPx=layerPx.add(dX,dY);}
return viewPortPx;},getLayerPxFromViewPortPx:function(viewPortPx){var layerPx=null;if(viewPortPx!=null){var dX=-parseInt(this.layerContainerDiv.style.left);var dY=-parseInt(this.layerContainerDiv.style.top);layerPx=viewPortPx.add(dX,dY);if(isNaN(layerPx.x)||isNaN(layerPx.y)){layerPx=null;}}
return layerPx;},getLonLatFromLayerPx:function(px){px=this.getViewPortPxFromLayerPx(px);return this.getLonLatFromViewPortPx(px);},getLayerPxFromLonLat:function(lonlat){var px=this.getPixelFromLonLat(lonlat);return this.getLayerPxFromViewPortPx(px);},CLASS_NAME:"OpenLayers.Map"});OpenLayers.Map.TILE_WIDTH=256;OpenLayers.Map.TILE_HEIGHT=256;