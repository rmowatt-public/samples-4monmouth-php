FM.UserRoot = {

	upload : function(el) {
		cpId = el.id.split('_');
		new AjaxUpload(el.id, {action: '/admin/uploaduser',
		onComplete : function(file, response){FM.UserRoot.uploadCallback(file, response, cpId[1])},
		data : { id : cpId[1]}});
	},

	uploadCallback : function(file, response, id){
		if(response != '0') {
			//if($('view_'+id).innerHTML.strip() == 'X') {
			//alert(response)
			$('view_'+id).innerHTML = '<div  onclick = "FM.UserRoot.viewUser(\'' + response + '\')"><img src="/images/root/img/Camera.gif" alt="" height="16" width="16"></div>';
			//}
		} else {
			alert('Upload was unsuccessful. Please refresh page and try again');
		}
	},
	
	checkuname : function(name) {
		pars = {
				uname : name
			}
			FM.doAjax('/root/ajaxcheckuname', $H(pars).toQueryString(),
			FM.UserRoot.checkunameCallback);
	},
	
	checkunameCallback : function(transport) {
		if(transport.responseText == 1){
			$('uname').value = '';
			alert('username is already taken');
		}
	},
	
	deleteUser : function(uId) {
		if(confirm('Are you sure you want to delete this user?')) {
			pars = {
				id : uId
			}
			FM.doAjax('/root/ajaxdeleteuser', $H(pars).toQueryString(),
			FM.UserRoot.deleteUserHandler);
		}
	},

	deleteUserHandler : function(transport) {
		//alert(transport.responseText)
		if(transport.responseText != '0') {
			sibs = $('tr_' + transport.responseText.strip()).nextSiblings();
			$('tr_' + transport.responseText.strip()).remove()
			sibs[0].remove();
			sibs[1].remove();
		} else {
			alert('Delete was unsuccessful. Please refresh page and try again');
		}
	},

	toggleUserEmail : function(el, uid) {
		pars = {
			uid : uid,
			email : (el.checked) ? '1' : '0'
		}
		FM.doAjax('/root/ajaxtoggleuseremail', $H(pars).toQueryString(),
		FM.UserRoot.toggleUserEmailHandler);
	},
	
	toggleUserEmailHandler : function(transport) {
		if(transport.responseText == 1) {
			FM.ajaxStatus('Email Preferences Have Been Updated', 'confirmation');
		} else {
			FM.ajaxStatus('Problem occured updating preferences. Please refresh the page and try again', 'confirmation');
		}
	},

	toggleUserActive : function(tid) {
		state = ($('useractive_' + tid).checked) ? '1' : '0';
		pars = {
			id : tid,
			active : state
		}
		FM.doAjax('/root/ajaxtoggleuseruser', $H(pars).toQueryString(),
		FM.UserRoot.toggleUserActiveHandler);
	},

	toggleUserActiveHandler : function(transport) {
		//alert(transport.responseText);
	},
	
	addToOrg : function(uId, orgId, dd) {
		if(orgId == ''){return false;}
			pars = {
			uid : uId,
			oid : orgId
		}
		FM.doAjax('/root/ajaxaddusertoorg', $H(pars).toQueryString(),
		function(transport){FM.UserRoot.addToOrgHandler(transport, orgId, dd)});
	},
	
	addToOrgHandler : function(transport, orgId, dd) {
		if(transport.responseText.strip() == '1') {
			orgName = dd.options[dd.selectedIndex].text
			div = new Element('div', {'id' : 'org_' + orgId});
			div.innerHTML = orgName + '<a onclick="FM.UserRoot.removeFromOrg($(\'uid\').value , ' +  orgId + ')"><img src="/images/icons/remove.png" height="16" width="16"></a>';
			$('orgs').insert({bottom : div})
			FM.ajaxStatus('User Added to Organization', 'confirmation');
		}
		else if(transport.responseText.strip() == '2') {
			FM.ajaxStatus('User Is Already Part Of Organization', 'confirmation');
		} else {
			FM.ajaxStatus('Problem occured updating organizations. Please refresh the page and try again', 'confirmation');
		}
		dd.selectedIndex = 0;
	},
	
	removeFromOrg : function(uId, oId) {
		pars = {
			uid : uId,
			oid : oId
		}
		FM.doAjax('/root/ajaxremoveuserfromorg', $H(pars).toQueryString(),
		function(transport){FM.UserRoot.removeFromOrgHandler(transport, oId)});
	},
	
	removeFromOrgHandler : function(transport, orgId) {
		if(transport.responseText.strip() == '1') {
			$('org_' + orgId).remove();
			FM.ajaxStatus('User Removed From Organization', 'confirmation');
		}
	},
	
	updatePassword : function() {
		if($('pwd1').value == '' ||$('pwd2').value == '' ) {
			alert('Please fill out both password fields before submission');
			return false;
		}
		if($('pwd1').value != $('pwd2').value) {
			alert('Passwords Do Not Match. Try Again');
			$('pwd1').value = '';
			$('pwd2').value = '';
			return false;
		}
		pars = {
			uid : $('uid').value,
			key : 'pwd',
			value : $('pwd1').value
		}
		FM.doAjax('/root/ajaxupdateuserinfo', $H(pars).toQueryString(),
		FM.UserRoot.updatePasswordHandler);
	},
	
	updatePasswordHandler : function(transport) {
		if(transport.responseText.strip() == '1') {
			FM.ajaxStatus('Password Updated', 'confirmation');
			$('pwd1').value = '';
			$('pwd2').value = '';
		} else {
			FM.ajaxStatus('Password failed to update. Please refresh page and try again', 'confirmation');
		}
	},
	
	updateDetails : function() {
		if($('details').selectedIndex == 0) {
			alert('Please choose a field to update');
			return false;
		}
		if($('newValue').value == '') {
			alert('Please supply a new value');
			return false;
		}
		
		pars = {
			uid : $('uid').value,
			key : $('details').value,
			value : $('newValue').value
		}
		//alert($H(pars).toQueryString()); 
		FM.doAjax('/root/ajaxupdateuserinfo', $H(pars).toQueryString(),
		function(transport){FM.UserRoot.updateDetailsHandler(transport, $('details').value, $('newValue').value)});
	},
	
	updateDetailsHandler : function(transport, id, value) {
		//alert(transport.responseText);
		if(transport.responseText.strip() == '1') {
			FM.ajaxStatus('Details Updated', 'confirmation');
			location.reload();
		}
		else {
			FM.ajaxStatus('Details failed to update. Please refresh page and try again', 'confirmation');
			$('details').selectedIndex = 0;
			$('newValue').value = '';
		}
	}
}