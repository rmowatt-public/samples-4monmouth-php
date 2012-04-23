FM.Quickadd = {

	exempt : $A(['address1','address2', 'middlename', 'city', 'state', 'zip',  'submit']),
	error : 0,

	createUser : function() {
		pars = $H();
		$('newUserForm').getElements().each(
		function(el){
			if(FM.Quickadd.exempt.indexOf(el.name) == -1){
				if(el.value == '' && FM.Quickadd.error == 0) {
					FM.Quickadd.error = el.name;
				}
			}
			if(FM.Quickadd.error == 0 && el.name != 'submit') {
				pars.set(el.name, el.value)
			}
		})
		if(FM.Quickadd.error != 0){alert('please complete ' + FM.Quickadd.error + ' field'); FM.Quickadd.error = 0;return false;}
		else {
			FM.doAjax('/root/ajaxadduser', pars.toQueryString(),
			FM.Quickadd.createUserHandler);
			return false;
		}
	},

	createUserHandler : function(transport) {
		//alert(transport.responseText)
		parent.FM.BusinessClient.addOption(transport.responseText, $('uname').value + ' (' + $('email').value + ')');
	}
}