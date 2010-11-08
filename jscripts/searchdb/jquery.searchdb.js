/**
 * searchdb 1.0 beta- jQuery searchdb plugin
 *
 * http://trirand.com/blog/
 * 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * construct a form and returns a string which can be used in SQL where clause
 */

(function ($) {
$.searchdb = function(settings) {

  settings = $.extend({
    capition: "Search Data Where",
    hideonSearch: false,
    disableClose: false,
    onSearch : null
  },settings || {} );

    var buttons = ['Search','Clear','Close' ];
    var odata = ['equal', 'not equal', 'less', 'less or equal','greater','greater or equal', 'begins with','ends with','contains' ];
///////////////////////////////////////////////////////////// 
    function sOptions( sopt, id ) {
      sopt = sopt ? sopt.split(";") : ['eq','ne','lt','le','gt','ge','bw','ew','cn'];
      var s = "<select id="+id+">";
      for (var i=0;i<=sopt.length-1;i++) {
        s += sopt[i]=='eq' ? "<option value='='>"+odata[0]+"</option>" : "";
        s += sopt[i]=='ne' ? "<option value='<>'>"+odata[1]+"</option>" : "";
        s += sopt[i]=='lt' ? "<option value='<'>"+odata[2]+"</option>" : "";
        s += sopt[i]=='le' ? "<option value='<='>"+odata[3]+"</option>" : "";
        s += sopt[i]=='gt' ? "<option value='>'>"+odata[4]+"</option>" : "";
        s += sopt[i]=='ge' ? "<option value='>='>"+odata[5]+"</option>" : "" ;      
        s += sopt[i]=='bw' ? "<option value='like...%'>"+odata[6]+"</option>" : "";
        s += sopt[i]=='ew' ? "<option value='like%...'>"+odata[7]+"</option>" : "";
        s += sopt[i]=='cn' ? "<option value='like%.%'>"+odata[8]+"</option>" : "";      
      }
      s +="</select>";    
      return s;
    }
    function consructWhere(){
      var s="";
      var i =0;
      prchar = escape('%');
      $('input:text', searchdata).each( function () {
        if( $(this).val() )  {
          i++;
          if (i > 1) s +=" AND ";
          s += $(this).attr("dbname");
          var uid = $(this).attr("uid");	
          var d = $(this).attr("dtype");
          d = d =='N' ? "" : "'";
          var dval = $(this).val();
          uid = $("#"+uid+" option[@selected]",searchdata).val();
          switch (uid) {
            case 'like...%': s += ' LIKE '+d+dval+prchar+d; break;
            case 'like%...': s += ' LIKE '+d+prchar+dval+d; break;
            case 'like%.%': s += ' LIKE '+d+prchar+dval+prchar+d; break;
            default: s += " "+uid+" "+d+dval+d;
          }
          //s += " " + $("#"+uid+" option[@selected]",searchdata).val();
          //s += " '"+ $(this).val()+"'";
        } 
      });
      //alert(s);
      if(settings.hideonSearch) $(searchdata).hide(); 
      return s;
    }
    function clearValues(){
      $('input:text', searchdata).each(function() { $(this).val('')} ) 
    };
    function hideForm() {
      $(searchdata).hide();
    };
     
    return {   
      createForm : function ( aModel, onSearch, hideClose ) {
        frm = $("<table class='table-search' border='0' cellpadding='1' cellspacing='0'><tr><td colspan='3' class='table-label'>"+settings.capition+"</td></tr></table>");
        for(var i=0;i<=aModel.length-1;i++) {
          var tr = $('<tr></tr>');
          var tdl = $('<td></td>').attr('class','table-label').html( aModel[i]['label']+":" );
          tr.append(tdl);
          var tsd =$('<td></td>').attr( 'class','table-data').append( sOptions( aModel[i]['dopt'], i+"_" ) );
          var tdd = $('<td></td>').attr( 'class','table-data');
          var inp = $('<input></input>').attr({ maxLength: aModel[i]['maxsize'] ? aModel[i]['maxsize'] : "15", 
                      size: aModel[i]['size'] ? aModel[i]['size'] : "15", 
                      type: "text",
                      value: aModel[i]['defval'] ? aModel[i]['defval'] : "",
                      dbname: aModel[i]['dbname'],
                      uid: i+"_",
                      id: i+"_jqs",
                      dtype: aModel[i]['dtype'] ? aModel[i]['dtype'] : "S"
                      });
          tdd.append(inp);
          tr.append(tsd);
          tr.append(tdd);
          frm.append(tr);
        }
      
        tr = $('<tr></tr>');
        tdd = $('<td></td>').attr('colSpan','3').attr('align','right');
        inp = document.createElement('input');
        $(inp).attr( {class:"button",type:"button",Name:"search", value: buttons[0]} ).click(function(){ 
                                                                              if (onSearch) {                                                                              
                                                                                onSearch(consructWhere());
                                                                               }
                                                                            });
        tdd.append(inp)      
        inp = document.createElement('input');
        $(inp).attr( {class:"button",type:"button",Name:"clear", value: buttons[1]} ).click(function(){ clearValues()} );
        tdd.append(inp)
        if( !hideClose) {      
          inp = document.createElement('input');
          $(inp).attr( {class:"button",type:"button",Name:"close", value: buttons[2]} ).click(function(){ hideForm() } );
          tdd.append(inp);
        }      
        tr.append(tdd);
        frm.append(tr);
        return frm;
      },
      setLanguage : function (aButtons, aOptions) {
        buttons = aButtons;
        odata = aOptions; 
      }
    };

}();

$.fn.searchdb = function(settings)
{
  this.each(function() {
  // create a form
    var onSearch = settings.onSearch;
    if(typeof onSearch != 'function') onSerach = false; 
    searchdata = this;
    if( settings.searchModel == null) return false; // no search model is given
    sDiv = document.createElement("div")
    var theform = $.searchdb.createForm( settings.searchModel, onSearch,settings.disableClose );
    $(sDiv).append(theform)
    $(searchdata).append(sDiv);
  });  
  return this;
};
})(jQuery);
