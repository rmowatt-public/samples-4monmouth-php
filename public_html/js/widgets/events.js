FM.Events = (function(){

	var bindEvents = function(){
		jQuery("#addEvent").submit(function(){
			var params  = $(this).serialize();
			saveEvent(params);
			return false;
		});

		jQuery(".events .event").live("click", expand);

	},

	edit = function(id){
		vars = {
			id : id
		}
		FM.doAjax('/utilities/ajaxgetevent', $H(vars).toQueryString(), editCallback);
	},

	editCallback = function(transport) {
		//alert(transport.responseText);
		var json = transport.responseText.evalJSON();
		$('datepicker_input').value = json.formattedDate;
		$('datetag').value = json.datetag;
		$('cename').value = json.name;
		$('celocation').value = json.location;
		$('cedescription').value = json.description;
		$('frontPage').checked = (json.frontPage == '1');

		starttime = json.starttime.split(':')
		$('stimeh').value = starttime[0];
		$('stimem').value = starttime[0];
		endtime = json.endtime.split(':')
		$('etimeh').value = endtime[0];
		$('etimem').value = endtime[1];
		$('ceid').value = json.eventId;
		
		if($('calendarAdmin').style.display != 'block'){$('calendarAdmin').style.display = 'block'}
		//if($('calendarAdmin').style.display != 'inline'){$('calendarAdmin').style.display = 'inline'}
	},

	// to do
	validateForm = function(){
		return true;
	},

	saveEvent = function(vars){
		//alert(vars)
		FM.doAjax('/utilities/ajaxaddevent', vars, savedCallBack);
	},

	savedCallBack = function(transport){
		//alert(transport.responseText)
		if (transport.responseText != '0') {
			Form.reset('addEvent');
			$('ceid').value = '';
			var json = transport.responseText.evalJSON();
			tr = new Element('tr', {'id' : 'manageevents_' + json.eventId, 'class':'new'});
			//id = new Element('td');
			//id.innerHTML = json.eventId;
			//tr.insert({bottom : id});
			mname = new Element('td');
			mname.innerHTML = json.name;
			tr.insert({bottom : mname})
			date = new Element('td');
			date.innerHTML = json.date;
			tr.insert({bottom : date})
			time = new Element('td');
			time.innerHTML = json.time;
			tr.insert({bottom : time})
			local = new Element('td');
			local.innerHTML = json.location;
			tr.insert({bottom : local})
			ee = new Element('td');
			ee.innerHTML = '<a onclick="FM.Events.edit(\''+ json.eventId +'\')" class="edit_event_'+ json.eventId +'"><img src="/images/icons/edit.png"></a>';
			tr.insert({bottom : ee})
			deleteMe = new Element('td');
			deleteMe.innerHTML = '<a onclick="FM.Events.remove(\''+ json.eventId +'\')" class="delete_event_'+ json.eventId +'"><img src="/images/icons/remove.png"></a>';
			tr.insert({bottom : deleteMe})
			tr2 = new Element('tr', {'id' : 'manageeventsdesc_' + json.eventId, 'class':'new'});
			desc = new Element('td', {'colspan' : '6', 'style' : 'padding-bottom:1em'});
			desc.innerHTML = '<b><i>Description : </i></b> ' + json.description;
			tr2.insert({bottom : desc})
			
			if($('manageevents_' + json.eventId)) {
				$('manageevents_' + json.eventId).replace(tr)
				$('manageeventsdesc_' + json.eventId).replace(tr2)
				FM.ajaxStatus('Your event has been updated', 'admin');
			} else {
				$('manageeventsheaders').insert({after : tr});
				tr.insert({after : tr2});
				FM.ajaxStatus('Your event has been added', 'admin');
			}
			//alert('')


		}
		else {
			FM.ajaxStatus('There was a problem saving your event. Please check the form and try again.', 'admin');
		}
	},

	getMonth = function(cmonth, cyear){
		vars = {
			month: cmonth,
			year: (cyear) ? cyear : 2010,
			orgId: $('orgId').value
		}
		FM.doAjax('/utilities/ajaxgetcalendar', $H(vars).toQueryString(), getMonthCallback);
	},

	getMonthCallback = function(transport){
		//alert(transport.responseText);
		$('calendar').innerHTML = transport.responseText;
	},

	expand = function(e){
		jQuery.fn.colorbox({
			inline : true,
			href : jQuery(this).children("div"),
			width : "400",
			open : true,
			opacity : .4
		});
		return false;
	}


	jQuery(function(){
		bindEvents();
	});

	return {
		getNext : getMonth,
		edit : edit

	}
})();

FM.Events.remove = function(e) {
	vars = {
		id : e,
		orgId: $('orgId').value
	}
	FM.doAjax('/utilities/ajaxdeleteevent', $H(vars).toQueryString(), function(transport){FM.Events.removeCallback(transport, e);})
}

FM.Events.removeCallback = function(transport, id) {
	if(transport.responseText == '1') {
		if($('manageevents_' + id)) {
			$('manageevents_' + id).remove()
			$('manageeventsdesc_' + id).remove()
		}
	} else {
		alert('Action failed, please refresh and try again')
	}
}