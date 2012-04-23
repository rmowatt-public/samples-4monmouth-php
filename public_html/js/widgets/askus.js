FM.AskUs = {
	submitRequest : function() {
		errors = $A();
		info = new Hash();

		$('contactUsOrgForm').descendants().each( function(el) {
			if(el.name && el.value == '' && el.name != 'orgId') {
				errors.push(el.name);
			} else {
				if(el.name && el.value) {
					if(el.name == 'email' && !is_valid_email(el.value)){errors.push('email')}
					info.set(el.name, el.value)
				}
			}
		}
		)
		if(errors.size()){
			alert('please fill out ' + errors[0]);
			return false;
		}
		else{
			info.set('orgId', $('orgId').value);
			FM.doAjax('/utilities/ajaxcontactus', info.toQueryString(),
			FM.AskUs.submitRequestCallback );

		}
	},

	submitRequestCallback : function(transport) {
		if(transport.responseText.indexOf('24')) {
			$('contactUsOrgForm').descendants().each( function(el) {
				if(el.name && el.name != 'orgId' && el.name != 'submit') {
					el.value = ''
				}
			}
			);
		}
		FM.ajaxStatus(transport.responseText, 'confirmation')
		//FM.ajaxStatus(transport.responseText, 'contact')
	}
}


function is_valid_email (email) {
	return /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email);
}
