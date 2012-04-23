/**
* FM.js * Description: Extends Prototype 1.6.0.2
* @author TJ Eastmond <teastmond@efashionsolutions.com>
*/

var FM = {
	version : '1.0.0',
	requiredPrototypeVersion : '1.6',
	debug : false
};

/** Make sure we have the right version of Prototype loaded first.  Copied from Scriptaculos. */
if (typeof convertVersionString == 'undefined') {
	var convertVersionString = function(versionString) {
		var r = versionString.split('.');
		return parseInt(r[0]) * 100000 + parseInt(r[1]) * 1000 + parseInt(r[2]);
	}
}

if ((typeof Prototype == 'undefined') || (convertVersionString(Prototype.Version) < convertVersionString(FM.requiredPrototypeVersion))) {
	throw("script.aculo.us requires the Prototype JavaScript framework >= " + Scriptaculous.REQUIRED_PROTOTYPE);
}

/**
* FM.onDomReady - attach JavaScript events to elements
* before the page completely loads, but instead right
* after the DOM is ready.
* @author TJ Eastmond <teastmond@efashionsolutions.com>
*/
Object.extend(Event, {
	onReady : function(f) {
		if(document.body) f();
		else document.observe('dom:loaded', f);
	}
});

FM.onDomReady = function(func) { Event.onReady(func); };

FM.onDomReady(function(){if(error == true){FM.ajaxStatus('We\'re sorry, that page can not be found')}});

/**
* More accurate a method to check for true mouse hover event
* @author TJ Eastmond <teastmond@efashionsolutions.com>
*/
var mouseLeaveEnter = function(e, handler) {
	var t = e.relatedTarget ? e.relatedTarget : e.type == 'mouseout' ? e.toElement : e.fromElement;
	while (t && t != handler) { t = t.parentNode; }
	return (t != handler);
};

FM.closeTopBar = function() {
	status = jQuery("#statusMessage").hide("fast").empty();
};

/**
* Gets the elements from the query string as a hash
*
* You can use this to get the query string when the script is included:
* <script src="script.js?var1=foo&var2=bar"></script>
*
* @see scriptaculous.js
*
* @param  string src the name of your script
* @return Hash   the query
* @author Reha Sterbin <rsterbin@efashionsolutions.com>
*/
FM.getQuery = function(src) {
	var regex = new RegExp(src + "(\\?.*)?$");
	var found = $A(document.getElementsByTagName("script")).findAll(function(s) {
		return (s.src && s.src.match(regex))
	});
	if (found.length > 0) {
		var query = found[0].src.replace(/.*\?/, '');
		return $H(query.toQueryParams());
	} else {
		return $H({});
	}
};

FM.ajaxStatus = function(msg, attachTo){
	var status = jQuery("#statusMessage"),
	log = jQuery('<span id="msg">'+msg+'</span>');

	status.empty().append(log).show("slide", {direction : "down"}, 150);

	var timer = setTimeout(function() {
		FM.closeTopBar();
		clearTimeout(timer);
	}, 3000 );
};

FM.doAjax = function(target, params, callback, method){
	methodz = (method) ? method : 'post'
	myAjax = new Ajax.Request(
	target, {
		method: methodz,
		parameters: params,
		onComplete: function(transport){
			if (transport.responseText.indexOf('usernameLogin')> -1 ) {
				document.location = '/';
			} else if (transport.responseText.indexOf('Access Denied')> -1) {
				FM.ajaxStatus('User Was Denied Access to Ajax functionality at ' + target);
			} else {
				if (FM.debug == true) {
					alert('target = ' + target + "\n"
					+ 'params = ' + params + "\n"
					+ 'method = ' + methodz + "\n"
					+ 'result = ' + transport.responseText.stripTags()
					)
				}
				callback(transport)
			}
		}
	});
};

FM.addslashes = function(str) {
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\0/g,'\\0');
	return str;
}

FM.stripslashes = function(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\\0/g,'\0');
	str=str.replace(/\\\\/g,'\\');
	return str;
}



/**
* Shows an AJAX error and redirects, if required
*
* This method picks up AJAX errors reported by ajaxError() in controllers that
* extend FM_Controller_Tools_Base.  To use it, set this function as onFailure
* on an ajax request:
*
* new Ajax.Request(url, {
*     method: 'post',
*     parameters: { var1: 'val1', var2: 'val2' },
*     onSuccess: function (transport) {
*         // do stuff...
*     }
*     onFailure: FM.showAjaxError
* });
*
* @param  Ajax.Response response the response
* @author Reha Sterbin <rsterbin@efashionsolutions.com>
*/
FM.showAjaxError = function(response) {
	var json = response.responseJSON;

	if (json.message) {
		var message = json.message;
	} else {
		var message = 'There has been an error.';
	}

	var errorMarkup = '<div style="width:100%; height:100%; background:#fff;">'
	+ '<div style="padding: 10px;">'
	+ '<h3>There has been an error</h3>'
	+ '<p>' + message + '</p>'
	+ '</div>'
	+ '<div style="text-align: center; font-size: 0.9em; margin-top: 30px;">'
	+ '<p>Please contact an administrator to report this error.<br />'
	+ 'When you close this message, you will be redirected.</p>'
	+ '</div>'
	+ '</div>';

	var lines = Math.round(message.length / 80);
	var height = 200 + ((lines - 1) * 30);
	Shadowbox.close();
	Shadowbox.open.bind(Shadowbox).delay(0.4, {
		content: errorMarkup,
		type:    'html',
		width:   300,
		height:  height,
		options: {
			modal: true,
			onClose: function () {
				if (json.redirect) {
					window.location = json.redirect;
				}
			}
		}
	});
};

FM.showPage = function(e) {
	var	tablecount = 25,
	total = parseFloat(jQuery(".total").html()),
	current = parseFloat(e.target.innerHTML) -1,
	firstitem,
	lastitem;

	firstitem =  (current *  tablecount)  + 1;
	lastitem = (current *  tablecount)  + tablecount;
	lastitem = (lastitem >  total) ? total : lastitem;

	jQuery(this).addClass("selected").siblings().removeClass("selected");
	jQuery("table[id^='result_']").addClass("unactive").end().find("#result_"+ current).removeClass("unactive");
	jQuery("#resultCount").html( firstitem + "-" +lastitem );

	return false;

};

FM.checkEmail = function(email) {
	var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email)) {
		return false;
	}
	return true;
}

FM.Widget = (function(){

	widgetColorbox = function(){
		jQuery(".rss_link, .horoscope").colorbox({
			width:"900px",
			height : "800px",
			iframe : true,
			opacity : .4
		});
	}

	jQuery(function(){
		widgetColorbox();
	})

	return{}

})();

FM.stristr = function(haystack, needle, bool) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfxied by: Onno Marsman
    // *     example 1: stristr('Kevin van Zonneveld', 'Van');
    // *     returns 1: 'van Zonneveld'
    // *     example 2: stristr('Kevin van Zonneveld', 'VAN', true);
    // *     returns 2: 'Kevin '

    var pos = 0;

    haystack += '';
    pos = haystack.toLowerCase().indexOf( (needle+'').toLowerCase() );
    if (pos == -1){
        return false;
    } else{
        if (bool) {
            return haystack.substr( 0, pos );
        } else{
            return haystack.slice( pos );
        }
    }
}


/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
//var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e, el){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
//if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
//tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
//else if (curX<leftedge)
//tipobj.style.right="65px"
//else
//position the horizontal position of the menu where the mouse is positioned
//tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
//if (bottomedge<tipobj.offsetHeight)
//tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
//else
//tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
}
}


