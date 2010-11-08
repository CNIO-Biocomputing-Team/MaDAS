/* Copyright (c) 2006 MetaCarta, Inc., published under the BSD license.
 * See http://svn.openlayers.org/trunk/openlayers/license.txt for the full
 * text of the license. */


OpenLayers.Layer.MapServer.prototype.initGriddedTiles = function(bounds) {
    if(this.grid.length == 0){ this.grid.push([]); }

    var viewSize = this.map.getSize();
    var minCols = Math.ceil(viewSize.w/this.tileSize.w) +
            Math.max(1, 2 * this.buffer);

    var extent = this.map.getMaxExtent();
    var resolution = this.map.getResolution();

    var tileLayout = this.calculateGridLayout(bounds, extent, resolution);

    var tileoffsetx   = Math.round(tileLayout.tileoffsetx);
    var tileoffsetlon = tileLayout.tileoffsetlon;
    var tilelon       = tileLayout.tilelon;

    this.origin = new OpenLayers.Pixel(tileoffsetx, 0);

    var startX   = tileoffsetx;
    var startLon = tileoffsetlon;

    var colidx = 0;


    var layerContainerDivLeft = parseInt(this.map.layerContainerDiv.style.left);
    var layerContainerDivTop = parseInt(this.map.layerContainerDiv.style.top);

    // follow the inner loop in the original OpenLayers version.
    do {
        var tileBounds = new OpenLayers.Bounds(tileoffsetlon,
                                                0,
                                                tileoffsetlon + tilelon,
                                                1);

        //var x = tileoffsetx;
        //x -= layerContainerDivLeft;
        //var y = 0;
        var px = new OpenLayers.Pixel(tileoffsetx - layerContainerDivLeft, 0);

        var tile = this.grid[0][colidx++];
        if (!tile) {
            tile = this.addTile(tileBounds, px);
            this.addTileMonitoringHooks(tile);
            this.grid[0].push(tile);
        } else {
            tile.moveTo(tileBounds, px, false);
        }

        tileoffsetlon += tilelon;
        tileoffsetx += this.tileSize.w;
    } while ((tileoffsetlon <= bounds.right + tilelon * this.buffer)
        || colidx < minCols)

    this.removeExcessTiles(2, colidx);
    this.spiralTileLoad();
};
OpenLayers.Layer.MapServer.prototype.buffer = 1;


OpenLayers.Layer.Genomic = OpenLayers.Class.create();
OpenLayers.Layer.Genomic.prototype = 
  OpenLayers.Class.inherit( OpenLayers.Layer.MapServer, {
    /** Hashtable of default parameter key/value pairs 
     * @final @type Object */
    DEFAULT_PARAMS: { },

    /**
     * @param {Object} obj
     * 
     * @returns An exact clone of this OpenLayers.Layer.Genomic
     * @type OpenLayers.Layer.Genomic
     */
    clone: function (obj) {
        
        if (obj == null) {
            obj = new OpenLayers.Layer.Genomic(this.name,
                                           this.url,
                                           this.params,
                                           this.options);
        }

        //get all additions from superclasses
        obj = OpenLayers.Layer.Grid.prototype.clone.apply(this, [obj]);

        // copy/set any non-init, non-simple values here

        return obj;
    },    
    
    /**
     * @param {OpenLayers.Bounds} bounds
     * 
     * @returns A string with the layer's url and parameters and also the 
     *           passed-in bounds and appropriate tile size specified as 
     *           parameters
     * @type String
     */
    getURL: function (bounds) {
        var bounds = this.adjustBounds(bounds);
        var xmin = Math.round(bounds.left);
        var xmax = Math.round(bounds.right);
        return this.getFullRequestString(
                     {
                     rand : Math.random(),
                     xmin : xmin,
                     start: xmin, // bah , just use xmin/max
                     xmax : xmax, // and start/stop.
                     stop : xmax,
                     width: this.map.tileSize.w
                      });
    },

    getExtent: function(resolution) {
        var extent = null;
        var center = this.map.getCenter();
        if (center != null) {

            if (resolution == null) {
                resolution = this.getResolution();
            }
            var size = this.map.getSize();
            var w_deg = size.w * resolution;

            extent = new OpenLayers.Bounds(center.lon - w_deg / 2,
                                           0,
                                           center.lon + w_deg / 2,
                                           1);
        }

        return extent;
    },
    /** @final @type String */
    CLASS_NAME: "OpenLayers.Layer.Genomic"
});
