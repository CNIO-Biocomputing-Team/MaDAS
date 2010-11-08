
OpenLayers.GenomeBrowser = OpenLayers.Map;

OpenLayers.GenomeBrowser.prototype.pan = function(dx, dy) {
	var options;
    options = OpenLayers.Util.applyDefaults(options, {
        animate: true,
        dragging: false
    });
    // getCenter
    var centerPx = this.getViewPortPxFromLonLat(this.getCenter());

    // adjust
    var newCenterPx = centerPx.add(dx, 0);
    //newCenterLonLat.lat = 0
    
    // only call setCenter if there has been a change
    if (!options.dragging || !newCenterPx.equals(centerPx)) {
        var newCenterLonLat = this.getLonLatFromViewPortPx(newCenterPx);
        if(options.animate){
            this.panTo(newCenterLonLat);
        } else {
            // this is required or the tools wont cause map to pan
            this.setCenter(newCenterLonLat, null, options.dragging);
        }
    }
};

OpenLayers.GenomeBrowser.prototype.centerLayerContainer = function(lonlat){
            var originPx = this.getViewPortPxFromLonLat(this.layerContainerOrigin);
            var newPx = this.getViewPortPxFromLonLat(lonlat);
    
            if ((originPx != null) && (newPx != null)) {
                this.layerContainerDiv.style.left = (originPx.x - newPx.x) + "px";
            }


};

OpenLayers.Control.PanZoom.prototype.draw = function(px) {
    // initialize our internal div
    OpenLayers.Control.prototype.draw.apply(this, arguments);
    px = this.position.clone();

    // place the controls
    this.buttons = new Array(); 

    var sz = new OpenLayers.Size(18,18);
    var pansz = new OpenLayers.Size(27,54);
    
    var centered = new OpenLayers.Pixel(px.x+sz.w/2, px.y);
    px.y = centered.y+sz.h;
    this._addButton("panleft", "west-mini.png", px, pansz);
    this._addButton("panright", "east-mini.png", px.add(sz.w, 0), pansz);
    this._addButton("zoomin", "zoom-plus-mini.png", 
                    centered.add(0, sz.h*3+5), sz);
    this._addButton("zoomworld", "zoom-world-mini.png", 
                    centered.add(0, sz.h*4+5), sz); 
    this._addButton("zoomout", "zoom-minus-mini.png", 
                    centered.add(0, sz.h*5+5), sz);
    return this.div;
};
OpenLayers.Control.PanZoomBar.prototype.draw = function(px) {
    // initialize our internal div
    OpenLayers.Control.prototype.draw.apply(this, arguments);
    px = this.position.clone();

    // place the controls
    this.buttons = new Array();
    var sz = new OpenLayers.Size(18,18);
    var pansz = new OpenLayers.Size(27,54);
    var centered = new OpenLayers.Pixel(px.x+sz.w/2, px.y);

    px.y = centered.y+sz.h;
    this._addButton("panleft", "west-mini.png", px, pansz);
    this._addButton("panright", "east-mini.png", px.add(pansz.w, 0), pansz);
    this._addButton("zoomin", "zoom-plus-mini.png", centered.add(10, sz.h*3+25), sz);
    centered = this._addZoomBar(centered.add(10, sz.h*4 + 25));
    this._addButton("zoomout", "zoom-minus-mini.png", centered, sz);
    return this.div;
};

OpenLayers.Control.MousePosition.prototype.redraw = function(evt) {

    var lonLat;
    if (evt == null) {
        lonLat = new OpenLayers.LonLat(0, 0);
    } else {
        if (this.lastXy == null ||
            Math.abs(evt.xy.x - this.lastXy.x) > this.granularity)
        {
            this.lastXy = evt.xy;
            return;
        }

        lonLat = this.map.getLonLatFromPixel(evt.xy);
        this.lastXy = evt.xy;
    }
    
    var digits = parseInt(this.numdigits);
    var newHtml = parseInt(lonLat.lon) + this.map.units;

    if (newHtml != this.element.innerHTML) {
        this.element.innerHTML = newHtml;
    }
};

OpenLayers.Tile.Image.prototype.destroy = function() {
        if (this.imgDiv != null)  {
            OpenLayers.Event.stopObservingElement(this.imgDiv.id);
            if (this.imgDiv.parentNode == this.frame) {
                this.frame.removeChild(this.imgDiv);
                this.imgDiv.map = null;
            }
            this.imgDiv.layer = null;
        }
        this.imgDiv = null;
        if ((this.frame != null) && (this.frame.parentNode == this.layer.div)) {
            this.layer.div.removeChild(this.frame);
        }
        this.frame = null;
        OpenLayers.Tile.prototype.destroy.apply(this, arguments);
    };


OpenLayers.Tile.Image.prototype.draw = function() {
        if (this.layer != this.layer.map.baseLayer && this.layer.reproject) {
            this.bounds = this.getBoundsFromBaseLayer(this.position);
        }
        var drawTile = OpenLayers.Tile.prototype.draw.apply(this, arguments);
        if (OpenLayers.Util.indexOf(this.layer.SUPPORTED_TRANSITIONS, this.layer.transitionEffect) == -1) {
              if(drawTile){
            
                if(!this.backBufferTile){
                    this.backBufferTile = this.clone();
                    this.backBufferTile.hide();
                    this.backBufferTile.isBackBuffer = true;
                    this.events.register('loadend', this, this.resetBackBuffer);
                    this.layer.events.register("loadend", this, this.resetBackBuffer);
                }
                this.startTransition();
            }
            else {
                if(this.backBufferTile){
                    this.backBufferTile.clear();
                }
            }
        }
        else {
            if (drawTile && this.isFirstDraw){
                this.events.register('loadend', this, this.showTile);
                this.isFirstDraw = false;
            }
        }
        if(!drawTile){
            return false;   
        }
        if(this.isLoading){
            this.events.triggerEvent("reload");
        }
        else{
            this.isLoading = true;
            this.events.triggerEvent("loadstart");
        }
        return this.renderTile();
    };

/********************************************************/

OpenLayers.BasePair = function(bp){
    var ll = new OpenLayers.LonLat(bp,0);
    ll.basepair = ll.x;
    return ll;
}

function jfetch(url,t,o) {
  var req = jfetch.xhr();
  req.open("GET",url,true);
  req.onreadystatechange = function() {
    if(req.readyState == 4){
      var rsp = req.responseText;
      if(t.constructor == Function) return t.apply(o,[rsp]);
      t = document.getElementById(t);
      t[t.value ==undefined ? 'innerHTML': 'value'] = rsp;
      req = null;
    }
  };
  req.send(null);
}
jfetch.xhr =
    (window.ActiveXObject)
   ? function(){ return new ActiveXObject("Microsoft.XMLHTTP"); }
   : function(){ return new XMLHttpRequest()};
