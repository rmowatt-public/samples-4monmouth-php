FM.Feedback = {
	active : null,
	
	toggleActive : function(el, fid) {
		var isActive = (el.checked) ? true : false;
		FM.Feedback.active = isActive;
		pars = {
			id : fid,
			active : isActive
		}
		FM.doAjax('/root/ajaxtogglefeedback', $H(pars).toQueryString(),
		FM.Feedback.toggleActiveHandler);
	},
	
	toggleActiveHandler : function(transport) {
		if(transport.responseText == '1') {
			var feedback = (FM.Feedback.active) ? "Feedback has been approved" : "Feedback has been unapproved";
			FM.ajaxStatus(feedback, 'statusMessage');
		}
	}
}