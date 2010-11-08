(function ($) {
/**
 * jqGrid 1.1 beta- jQuery Grid plugin 01/06/2007
 *
 * http://trirand.com/blog/
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * 
 *
 */
$.fn.jqGrid = function( p ) {
	p = $.extend({
		url: null,
		height: 150,
		page: 1,
		rowNum: 20,
		pager: "",
		colModel: [],
		rowList: [],
		colNames: [],			
		sortorder: "asc",
		sortname: "",
		sortascimg :  "images/sort_asc.gif",
		sortdescimg : "images/sort_desc.gif",
		firstimg: "images/first.gif",
		previmg: "images/prev.gif",
		nextimg: "images/next.gif",
		lastimg: "images/last.gif",
		altRows: true,
		subGrid: false,
		subGridModel :[],
		lastpage: 0,
		lastsort: -1,
		selrow: null,
		onSelectRow: null,
		onSortCol: null,
		ondblClickRow: null
	}, p || {});

	var grid = {         
		headers: [],
		cols: [],
		dragStart: function(i,x) {
			this.resizing = { idx: i, startX: x};
			// opera does not support col-resize
			this.hDiv.style.cursor = "e-resize";
		},
		dragMove: function(x) {
			if(this.resizing) {
				var diff = x-this.resizing.startX;
				var h = this.headers[this.resizing.idx];
				var newWidth = h.width + diff;
				if(newWidth > 30) { 
					h.el.style.width = newWidth+"px";
					h.newWidth = newWidth; 
					this.cols[this.resizing.idx].style.width = newWidth+"px";
					this.newWidth = this.width+diff;
					$('table',this.bDiv).css("width",this.newWidth + "px");
					this.hTable.style.width = this.newWidth + "px";
					this.hDiv.scrollLeft = this.bDiv.scrollLeft;
				}
			}
		},
		dragEnd: function() {
			this.hDiv.style.cursor = "default";
			if(this.resizing) {
				var idx = this.resizing.idx;
				this.headers[idx].width = this.headers[idx].newWidth;
				this.width = this.newWidth;
				this.resizing = false;
			}
		},
		scroll: function() {
			this.hDiv.scrollLeft = this.bDiv.scrollLeft;
	  	}
	} // end grid
	this.getUrl = function() { return p.url; };           
	this.getSortName = function() { return p.sortname; };
	this.getSortOrder = function() { return p.sortorder; };
	this.getSelectedRow = function() {return p.selrow; };
	this.getPage = function() {return p.page; };
	this.getRowNum = function() {return p.rowNum; };

	this.setUrl = function (newurl) { p.url=newurl; };
	this.setSortName = function (newsort) { p.sortname=newsort; };
	this.setSortOrder = function (neword) { p.sortorder=neword; };
	this.setPage = function (newpage) { 
		if( typeof newpage === 'number' && newpage > 0) {p.page=newpage;}
	};
	this.setRowNum = function (newrownum) { 
		if( typeof newrownum === 'number' && newrownum > 0) {p.rowNum=newrownum;}
	};

	return this.each( function() {
		if(this.grid) {return false;}
		if( p.colNames.length === 0 || p.colNames.length !== p.colModel.length ) {
			alert("Length of colNames <> colModel or 0!");
			return false;
		}
		var onSelectRow = p.onSelectRow, ondblClickRow = p.ondblClickRow, onSortCol=p.onSortCol;
		if(typeof onSelectRow !== 'function') {onSelectRow=false;}
		if(typeof ondblClickRow !== 'function') {ondblClickRow=false;}
		if(typeof onSortCol !== 'function') {onSortCol=false;}

		var formatCol = function (elem, pos){
			var rowalign1 = p.colModel[pos].align;
			rowalign1 = rowalign1 ? rowalign1 : "left";
			$(elem).css("text-align",rowalign1);
		}

		var resizeFirstRow = function (t){
			$("tbody tr:eq(1) td",t).each( function( k ) {
				$(this).attr("width",grid.headers[k].width+"px");
				grid.cols[k] = this;
			});
		}
		var addXmlData = function addXmlData (xml,t) {
			if(xml) { $("tbody tr:gt(0)", t).remove(); } else { return false; }
			var row,td, gi=0;
			$("rows/page",xml).each( function() {  p.page = this.firstChild.nodeValue; });
			$("rows/total",xml).each( function() { p.lastpage = this.firstChild.nodeValue; }  );
			$("rows/row",xml).each( function( j ) {
			row = document.createElement("tr");
			row.id = this.getAttribute("id") || j;
			if (p.subGrid) { 
				td = document.createElement("td");
				$(td,t).html("<img src='./plus.gif'/>")
          		.toggle( function() { 
            		$(this).html("<img src='./minus.gif'/>"); 
            		var req = populatesubgrid( row.id );
   					var subdata = "<tr class='subgrid'><td><img src='line3.gif'/></td><td colspan='"+parseInt(p.colNames.length-1)+"'><div class='tablediv'>"; 
            		$(this).parent().after( subdata+ req +"</div></td></tr>" ); }, 
            		function() { $(this).parent().next().remove(".subgrid"); $(this).html("<img src='./plus.gif'/>");
            	});
				formatCol($(td,t), 0);
				row.appendChild(td);
				gi = 1;
        	}
			$("cell",this).each( function (i) {
				td = document.createElement("td");
				$(td,t).html(this.firstChild.nodeValue);
				formatCol($(td,t), i+gi);
				row.appendChild(td);
			});
			$("tbody",t).append(row);
			});
			if($.browser.mozilla || $.browser.opera ) { resizeFirstRow(t); }
		  	t.scrollTop = 0;
		 	if( p.altRows === true ) { $("tbody tr:odd", t).addClass("alt"); }
			grid.hDiv.loading = false;
			$("div.loading",grid.hDiv).fadeOut("fast");
			xml = null;
			if(p.pager) {$(p.pager).find('span').html(p.page+"&nbsp;"+"/"+"&nbsp;"+p.lastpage);}
			return false;
		}
		var populate = function () {
			if(!grid.hDiv.loading) {
				grid.hDiv.loading = true;
				$("div.loading",grid.hDiv).fadeIn("fast");
				$.get(p.url,{page: p.page, rows: p.rowNum, sidx: p.sortname, sord:p.sortorder}, function(xml) { addXmlData(xml,grid.bDiv); });
			}
		}
		var populatesubgrid = function( sid ) {
			var res;
			if(!grid.hDiv.loading) {
				grid.hDiv.loading = true;
				$("div.loading",grid.hDiv).fadeIn("fast");
				$.ajax({type:"GET", url: p.subGridUrl, dataType:"xml",data: "id="+sid, async: false,success: function(sxml) { res = subGridXml(sxml); } });
      		}
			return res;
    	}
    	var subGridXml = function( sxml ){
      		var trdiv, tddiv, result = "", i;
      		if (sxml){
        		var dummy = document.createElement("span");
        		trdiv = document.createElement("div");
        		trdiv.className="rowdiv";
        		for (i = 0; i<= p.subGridModel[0].name.length-1; i++) {
          			tddiv = document.createElement("div");
          			tddiv.className = "celldivth";
          			$(tddiv).html(p.subGridModel[0].name[i]);
          			$(tddiv).width( p.subGridModel[0].width[i]);
          			trdiv.appendChild(tddiv);
        		}
        		dummy.appendChild(trdiv);
        		$("rows/row", sxml).each( function(){
          			trdiv = document.createElement("div");
          			trdiv.className="rowdiv";
          			$("cell",this).each( function(i) {
            			tddiv = document.createElement("div");
            			tddiv.className = "celldiv";
            			$(tddiv).html(this.firstChild.nodeValue);
            			$(tddiv).width( p.subGridModel[0].width[i] );
            			trdiv.appendChild(tddiv);
					});
          			dummy.appendChild(trdiv);
        		});
        		result += $(dummy).html();
        		sxml = null
        		grid.hDiv.loading = false;
        		$("div.loading",grid.hDiv).fadeOut("fast");
      		}
      		return result;
    	}
		var setPager = function (){
			$(p.pager).append("&nbsp;<img id='first' src='"+p.firstimg+"'>&nbsp;&nbsp;<img id='prev' src='"+p.previmg+"'>&nbsp;<span></span>&nbsp;<img id='next' src='"+p.nextimg+"'>&nbsp;&nbsp;<img id='last' src='"+p.lastimg+"'>");
			if(p.rowList.length >0){
				var str="<SELECT class='selbox'>";
				for(var i=0;i<=p.rowList.length-1;i++){
					str +="<OPTION value="+p.rowList[i]+">"+p.rowList[i];
				}
				str +="</SELECT>";
				$(p.pager).append("&nbsp;&nbsp;"+str);
				$(p.pager).find("select").bind('change',function() { 
					p.rowNum = this.value>0 ? this.value : p.rowNum; populate();
					 if(onSelectRow) {onSelectRow(p.selrow = null);} else {p.selrow = null;}
				});
			}
			$(p.pager).find('img').click( function() {
				var cp = parseInt(p.page,10);
				var last = parseInt(p.lastpage,10);
				var fp=true; var pp=true; var np=true; var lp=true;
				if(last ===0 || last===1) {fp=false;pp=false;np=false;lp=false; }
				else if( last>1 && cp >=1) {
					if( cp === 1) { fp=false; pp=false; } 
					else if( cp>1 && cp <last){ }
					else if( cp===last){ np=false;lp=false; }
				} else if( last>1 && cp===0 ) { np=false;lp=false; cp=last-1;}
				if( $(this).attr('id') === 'first' && fp ) { p.page=1; populate(); } 
				if( $(this).attr('id') === 'prev' && pp) { p.page=(cp-1);populate(); } 
				if( $(this).attr('id') === 'next' && np) { p.page=(cp+1);populate(); } 
				if( $(this).attr('id') === 'last' && lp) { p.page=last;  populate(); }
				if(onSelectRow) {onSelectRow(p.selrow = null);} else {p.selrow = null;}
			}).hover(function() { $(this).addClass("jsHover"); },
				function () { $(this).removeClass("jsHover"); }  
			);
		}
		var sortData = function (index, idxcol){
			if( p.sortname === index) {				    
				if( p.sortorder === 'asc') {
					p.sortorder = 'desc';
			} else if(p.sortorder === 'desc') { p.sortorder='asc';}
			} else { p.sortorder='asc';}
			var imgs = p.sortorder==='asc' ? p.sortascimg : p.sortdescimg;
			imgs = "<img src='"+imgs+"'>";
			var thd= $("thead:first",grid.hDiv).get(0);
			$("tr th div#"+p.sortname+" img",thd).remove();
			$("tr th div#"+index,thd).append(imgs);
			p.lastsort = idxcol;
			p.sortname = index;
			p.page = 1;
			if(onSortCol) {onSortCol(index,idxcol);}
			populate();
			if(onSelectRow) {o(p.selrow = null);} else{p.selrow = null;}
		}
//
		if( p.subGrid ) {
		  p.colNames.unshift("");
		  p.colModel.unshift({name:'', width:30});
		}
		var thead = document.createElement("thead");
		var trow = document.createElement("tr");
		thead.appendChild(trow); 
		var i, th, idn, thdiv;
		for(i=0;i<=p.colNames.length-1;i++){
			th = document.createElement("th");
			idn = p.colModel[i].name;
			idn = idn ? idn : i+1;
			thdiv = document.createElement("div");
			thdiv.id = ""+idn+"";
			$(thdiv).html(p.colNames[i]+"&nbsp;");
			th.appendChild(thdiv);
			trow.appendChild(th);
		}
		this.appendChild(thead);
		thead = $("thead:first",this).get(0);
		var w, res, sort;
		$("tr:first th",thead).each(function ( j ) {
		w = p.colModel[j].width;
		w = w ? w : 150;
			res = document.createElement("span");
			$(res).html("&nbsp;");
			$(res).mousedown(function (e) {
				grid.dragStart( j ,e.clientX);
				return false;
			});
			$(this).css("width",w+"px").prepend(res);
			grid.headers[j] = { width: w, el: this };
		});
		$("tr:first th div",thead).each(function(l) {
			sort = p.colModel[l].sortable;
			if( sort !== null) {sort = sort;} else {sort =  true;}
			if(sort) { 
				$(this).css("cursor","pointer");
				$(this).click(function(){ sortData( this.id, l ); });
				}
		});

		var tbody = document.createElement("tbody");
		trow = document.createElement("tr");
		trow.style.display="none";
		tbody.appendChild(trow);
		var td, ptr;
		for(i=0;i<=p.colNames.length-1;i++){
			td = document.createElement("td");
			trow.appendChild(td);
		}
		this.appendChild(tbody);
		$("tbody tr:first td",this).each(function(ii) {
			w = p.colModel[ii].width;
			w = w ? w : 150;
			$(this).css("width",w+"px");
			grid.cols[ii] = this ;
		});
		
		grid.width = $.css ? $.css(this,"width") : $.css(this,"width");
		grid.bWidth = grid.width;
		grid.hTable = document.createElement("table");
		grid.hTable.cellSpacing="0"; 
		grid.hTable.className = "scroll";
		grid.hTable.appendChild(thead);
//		thead = null;
		grid.hDiv = document.createElement("div");
		$(grid.hDiv)
		  	.css({ width: grid.width+"px", overflow: "hidden"})
			.prepend('<div class="loading">Loading...</div>')					
			.append(grid.hTable)
			.bind("selectstart", function () { return false; });

		$(this).mouseover(function(e) {
			td = (e.target || e.srcElement);
			ptr = $(td).parents("tr");
			if($(ptr).attr("class") != "subgrid") {
			$(ptr).addClass("over");
			 td.title = td.innerHTML;
			}
		}).mouseout(function(e) {
			td = (e.target || e.srcElement);
			ptr = $(td).parents("tr");
			$(ptr).removeClass("over");
			td.title = "";
		}).css("width", grid.width+"px").before(grid.hDiv).click(function(e) {
			td = (e.target || e.srcElement);
			ptr = $(td).parents("tr");
			if( p.selrow ) { $("tbody tr#"+p.selrow,grid.bDiv).removeClass("selected");}
			p.selrow = $(ptr).attr("id");
			if($(ptr).attr("class") != "subgrid") $(ptr).addClass("selected");
			if( onSelectRow ) { onSelectRow(p.selrow); }
		}).dblclick(function (e) {
			td = (e.target || e.srcElement);
			if( ondblClickRow ) {ondblClickRow(td.parentNode.id);}
		}).bind('reloadGrid', function(e) {
			populate();
			if(onSelectRow) {onSelectRow(p.selrow = null);} else{ p.selrow=null;}
		});
		grid.bDiv = document.createElement("div");
		$(grid.bDiv)
		  	.scroll(function (e) {grid.scroll()})
			.css({ height: p.height+"px", padding: "0px", margin: "0px", overflow: "auto", width: (grid.width+20)+"px"})
			.append(this);
		// remove the two tbody elements when ie (bug in 1.1.2)
		if( $.browser.msie ) {
			if( $("tbody",this).size() === 2 ) { $("tbody:first",this).remove(); }
		}
		$(grid.hDiv).mousemove(function (e) {grid.dragMove(e.clientX);}).after(grid.bDiv);

		populate();

		if(p.pager){
			if( $(p.pager).attr("class") === "scroll") $(p.pager).css({ width: (grid.width-4)+"px", overflow: "hidden"});
			setPager();
		}
		$(document).mouseup(function (e) {grid.dragEnd();});
		this.grid = grid;
		// MSIE memory leak
		$(window).unload(function () {
			this.grid = null;
		});
	});
};
})(jQuery);
