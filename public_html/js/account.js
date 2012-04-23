FM.Account = {
	helpmessage : '',
	formElements : $A([/*'uname',*/'firstname', 'middlename', 'lastname', 'address1','city', 'state', 'zip', 'email', 'phone' ]),
	exemptElements : $A(['uname', 'state']),

	showhelp : function(el, code, e) {
		
			
		/**
		jQuery(el).colorbox({
		innerWidth:"300px",
		innerHeight : "200px",
		iframe : true,
		opacity : .4,
		href : 'http://4monmouth.com/helppopup/' + code
		});
		**/
		pars = {
			code : code
		}
		FM.doAjax('/utilities/ajaxhelppopup', $H(pars).toQueryString(), function(transport) {
			prepend = '<div style="text-align:right;"><img src="/images/icons/remove.png" onclick="hideddrivetip()" style="cursor:pointer" /></div>';
			ddrivetip(prepend + transport.responseText,'yellow', 275);
			positiontip(e, el)
			
		});
	}
,



edit : function() {
	if($A($('memberInfo').getElementsByTagName('input')).size()){return FM.Account.unedit()}
	var props = $A($('memberInfo').getElementsByTagName('span')).each(function(el){
		if(el.className != 'exempt'){
			el3 = new Element('span');
			el2 = new Element('input', {'type' : 'text', 'value' : el.innerHTML});
			el3.insert(el2, {position : 'bottom'})
			Element.replace(el, el2);
		}
	})
	$('editBtn').innerHTML = 'Submit';
},

unedit : function() {
	i = 0;
	pars = {};
	var props = $A($('memberInfo').getElementsByTagName('input')).each(function(el){
		el3 = new Element('span');
		el3.innerHTML = el.value;
		el.replace(el3)
		pars[FM.Account.formElements[i]] = el.value
		i++;
	})
	$('editBtn').innerHTML = 'Edit';

	FM.doAjax('/account/ajaxupdatemember', $H(pars).toQueryString(),
	FM.Account.editCompleteHandler);
},

editCompleteHandler : function(transport) {
	if(transport.responseText == '1') {
		FM.ajaxStatus('Update Successful!', 'statusMessage');
	} else {
		FM.ajaxStatus('Update failed. Please refresh the page and try again!', 'statusMessage');
	}
},

sportsSignIn : function(name, pd) {
	if(name == '' ||  pd == '' ) {
		alert('Please complete all fields');
		return false;
	}
	pars = {
		uname : name,
		pwd : pd,
		orgId : $('orgId').value
	}
	FM.doAjax('/ajaxsportssignin', $H(pars).toQueryString(), FM.Account.sportsSignInResponseHandler);
},

sportsSignInResponseHandler : function(transport) {
	if(transport.responseText == 1) {
		window.location.reload();
	} else {
		alert('Username or password is incorrect. Please try again.')
		$('mediauname').value = '';
		$('mediapwd').value = '';
	}
},

sportsSignOut : function() {
	FM.doAjax('/ajaxsportssignout', '', FM.Account.sportsSignOutResponseHandler);
},

toggleSportsProtected : function(el) {
	pars = {
		orgId : $('orgId').value,
		isProtected :(el.checked) ? '1' : '0'
	}
	FM.doAjax('/ajaxsportstoggleprotected', $H(pars).toQueryString(), FM.Account.toggleSportsProtectedResponseHandler);
},

toggleSportsProtectedResponseHandler : function(transport) {
	if(transport.responseText == '1') {
		FM.ajaxStatus("setting updated", 'confirmation');
	} else {
		FM.ajaxStatus("Update Failed. Please refresh and try again", 'confirmation')
	}
},

sportsShowUpdateBox : function() {
	$('sportsUpdatePassword').style.display = 'inline'
},

sportsRequestAccount : function(rname, remail, rnote) {
	if(rname == '' || remail == '') {
		alert('Please complete both uname and email fields');
		return false;
	}
	if(!FM.checkEmail(remail)) {
		alert('This is not a valid email');
		return false;
	}

	pars = {
		name : rname,
		email : remail,
		note : rnote,
		orgId : $('orgId').value
	}
	//alert($H(pars).toQueryString());
	FM.doAjax('/ajaxsportsrequestaccount', $H(pars).toQueryString(), FM.Account.sportsRequestAccountResponseHandler);

},

toggleSportsSignIn : function(show, hide) {
	$(show).style.display = 'inline';
	$(hide).style.display = 'none';
},

sportsRequestAccountResponseHandler : function(transport) {
	if(transport.responseText == 1) {
		FM.Account.toggleSportsSignIn('showsignin' , 'showrequest');
		FM.ajaxStatus('Your request was successful!', 'confirmation');
	} else {
		alert('There was a problem with your submission. Please try again.')
	}
	$('rname').value = '';
	$('remail').value = '';
	$('rnote').value = '';
},

deleteSportsEmail : function(emailId) {
	pars = {
		id : emailId,
		orgId : $('orgId').value
	}
	FM.doAjax('/ajaxdeletesportsemail', $H(pars).toQueryString(), function(transport){FM.Account.deleteSportsEmailResponseHandler(transport, emailId)});
},

deleteSportsEmailResponseHandler : function(transport, id) {
	if(transport.responseText == 1) {
		$('email_head_' + id).remove();
		FM.ajaxStatus('Email deleted', 'confirmation');
	} else {
		alert('There was an issue delting this email. Please refresh the page and try again');
	}
},

sportsChangePwd : function(uid) {
	if($('oldSPwdConf').value == '' || $('newSPwd').value == '' || $('newSPwdConf').value == '' ) {
		alert('Please complete all fields');
		return false;
	}
	if( $('newSPwd').value !=  $('newSPwdConf').value) {
		alert('Your Passwords Do Not Match');
		$('oldSPwdConf').value = ''
		$('newSPwd').value = '';
		$('newSPwdConf').value = '';
		return false;
	}

	pars = {
		id : uid,
		old : $('oldSPwdConf').value,
		newPwd : $('newSPwd').value,
		orgId : $('orgId').value
	}
	FM.doAjax('/ajaxsportschangepwd', $H(pars).toQueryString(), FM.Account.sportsChangePwdResponseHandler);
},

sportsRetrievePwd : function(uid) {
	pars = {
		orgId : $('orgId').value,
		id : uid
	}
	FM.doAjax('/ajaxsportsretrievepwd', $H(pars).toQueryString(), FM.Account.sportsRetrievePwdResponseHandler);
},


sportsChangePwdResponseHandler : function(transport) {
	//alert(transport.responseText);
	if(transport.responseText == '1') {
		FM.ajaxStatus('Your Update Was Succesful', 'confirmation');
		$('sportsUpdatePassword').style.display = 'none';
		$('oldSPwdConf').value = ''
		$('newSPwd').value = '';
		$('newSPwdConf').value = '';
		return false;
	}
	if(transport.responseText == '2') {
		alert('You entered the wrong value for old password');
		$('oldSPwdConf').value = ''
		$('newSPwd').value = '';
		$('newSPwdConf').value = '';
		return false;
	}
	alert('There was an issue updating your password. Please refresh the page and try again');
	$('oldSPwdConf').value = ''
	$('newSPwd').value = '';
	$('newSPwdConf').value = '';
	return false;
},

sportsRetrievePwdResponseHandler : function(transport) {
	if(transport.responseText != 0 && FM.checkEmail(transport.responseText)) {
		alert('Your password has been sent to ' + transport.responseText)
	}
},

sportsSignOutResponseHandler : function(transport) {
	//alert(transport.responseText);
	if(transport.responseText != 1) {
		window.location.reload();
	}
},

addSportsUser : function(mail, name,fname) {
	if(!FM.checkEmail(mail)) {
		alert('Please Enter A Valid Email Address');
		$('adduseremail').value = '';
		return false;
	}
	pars = {
		fullname : fname,
		email : mail,
		uname : name,
		orgId : $('orgId').value
	}
	//alert($H(pars).toQueryString());
	FM.doAjax('/ajaxaddsportsuser', $H(pars).toQueryString(), FM.Account.addSportsUserResponseHandler);
},

addSportsUserResponseHandler : function(transport) {
	if(transport.responseText == '2'){
		alert('username is already beaing used');
		return false;
	}
	Form.reset('addSportsUser');
	var json = transport.responseText.evalJSON();

	tr = new Element('tr', {'id' : 'sutr_' + json.id});

	td2 = new Element('td')
	td2.innerHTML = json.uname + ' (' + json.fullname + ')';
	tr.insert({bottom : td2})

	td1 = new Element('td')
	td1.innerHTML = json.email;
	tr.insert({bottom : td1})

	td3 = new Element('td', {'align' : 'center'})
	td3.innerHTML = '<a onclick="FM.Account.deleteSportsUser(' + json.id +' , \'' + json.uname + '\')"><img src="/images/icons/remove.png" /></a>';
	tr.insert({bottom : td3})

	td4 = new Element('td', {'align' : 'center'})
	td4.innerHTML = '<a onclick="FM.Account.sendSportsUserPassword(' + json.id +')"><img src="/images/icons/send.jpg" /></a>';
	tr.insert({bottom : td4})

	$('sportsusertable').insert({bottom : tr});
	FM.ajaxStatus("Member added. Password sent to new member.", 'confirmation')

	if(transport.responseText == 1) {
		window.location.reload();
	}
},

deleteSportsUser : function(uid, name) {
	if(confirm('Are you sure you want to delete user ' + name)) {
		pars = {
			id : uid,
			orgId : $('orgId').value
		}
		//alert($H(pars).toQueryString());
		FM.doAjax('/ajaxdeletesportsuser', $H(pars).toQueryString(), function(transport){FM.Account.deleteSportsUserResponseHandler(transport, uid)});
	}
},

deleteSportsUserResponseHandler : function(transport, uid) {
	if(transport.responseText == 1) {
		$('sutr_' + uid).remove();
	} else {
		alert('Operation failed, please refresh page and try again');
	}
},

sendSportsUserPassword : function(uid) {
	pars = {
		id : uid,
		orgId : $('orgId').value
	}
	//alert($H(pars).toQueryString());
	FM.doAjax('/ajaxsendpassword', $H(pars).toQueryString(), FM.Account.sendSportsUserPasswordResponseHandler);

},

sendSportsUserPasswordResponseHandler : function(transport) {
	if(transport.responseText == '1') {
		FM.ajaxStatus('email sent', 'confirmation');
	} else {
		alert('Operation failed, please refresh page and try again');
	}
},

email : function(mcontent, sub) {
	pars = {
		content : mcontent,
		subject : sub,
		orgId : $('orgId').value
	}
	//alert($H(pars).toQueryString());
	FM.doAjax('/ajaxsendemails', $H(pars).toQueryString(), FM.Account.sendEmailsResponseHandler);
},

sendEmailsResponseHandler : function(transport) {
	if(transport.responseText == '1'){
		$('suemailsubject').value = '';
		tinyMCE.get('semail').setContent('');
		FM.ajaxStatus('email sent', 'confirmation');
	} else {
		alert('email failed, try again');
	}
},

updatePassword : function(id) {
	if( $('newPwd').value !=  $('newPwdConf').value) {
		alert('Your Passwords Do Not Match');
		$('oldPwdConf').value = ''
		$('newPwd').value = '';
		$('newPwdConf').value = '';
		return false;
	}

	pars = {
		uid : id,
		old : $('oldPwdConf').value,
		newPwd : $('newPwd').value
	}

	FM.doAjax('/ajaxupdatepwd', $H(pars).toQueryString(), FM.Account.updatePwdHandler);
},

updatePwdHandler : function(transport) {
	if(transport.responseText == '1') {
		FM.ajaxStatus('Your Update Was Succesful', 'confirmation');
		$('oldPwdConf').value = ''
		$('newPwd').value = '';
		$('newPwdConf').value = '';
		return false;
	}
	if(transport.responseText == '2') {
		alert('You entered the wrong value for old password');
		$('oldPwdConf').value = ''
		$('newPwd').value = '';
		$('newPwdConf').value = '';
		return false;
	}
	alert('There was an issue updating your password. Please refresh the page and try again');
	$('oldPwdConf').value = ''
	$('newPwd').value = '';
	$('newPwdConf').value = '';
	return false;
},

toggleEmail: function(el, toggleId) {
	if($(toggleId). style.display == "none") {
		$(toggleId). style.display = 'inline';
		el.src = '/images/icons/close.png'
	} else {
		$(toggleId). style.display = 'none';
		el.src = '/images/icons/open.png'
	}
}

}

/**
* SEARCH
*/
FM.Search = (function(){

	regions = {
		1 : [3,17],
		2 : [16],
		3 : [7],
		4 : [6,11],
		5 : [1,8, 12, 13, 18],
		6 : [2, 9, 10, 14, 19],
		7 :  [4, 5, 15, 20, 21, 22, 23]
	},

	toggleSubcatAll = function(el, id) {
		if($('chkbx_all_' + id)){
			if(!el.checked) {
				$('chkbx_all_' + id).checked = false;
			}
		}
	},

	buildPagination = function(){
		jQuery(".pages a").bind("click", showPage);
		resetPagination(1);
	},
	getActive = function(id){
		var	selected = parseFloat(jQuery(".pages .selected").attr("rel")),
		target = {
			elem : null,
			id : null
		}
		target.id = parseFloat((id == "next") ? selected+1 : (id == "previous") ? selected-1 : id);
		target.elem = jQuery("a[rel='"+target.id+"']");
		return target;
	},
	resetPagination = function(id){
		var 	pagination = jQuery(".pages"),
		target = getActive(id);

		jQuery.each(pagination, function(){
			var pages = jQuery(this).find("a"),
			status = (target.id < 10) ? "low" : (target.id+10 > pages.length) ? "high" : "med";

			pages.removeClass("active selected sepa sepb").filter(function(){
				var	rel = jQuery(this).attr("rel"),
				length =  pages.length -2;
				if(rel == "previous" || rel == "next"){
					return (rel == "previous" && target.id!=1) || (rel == "next" && target.id != length);
				}
				rel = parseFloat(rel);
				switch (status){
					case "low":
					if(rel == 10) { jQuery(this).addClass("sepa")}
					return rel <= 10 || rel > length-2;
					break;
					case 'high':
					if(rel == 2) { jQuery(this).addClass("sepa")}
					return rel <= 2 || rel > length - 10;
					break;
					default:
					if(rel == target.id-4) { jQuery(this).addClass("sepb")}
					if(rel == target.id+4) { jQuery(this).addClass("sepa")}
					return rel <= 2 || rel > length-2 || rel == target.id || (rel < target.id && rel > target.id-5) || (rel > target.id && rel < target.id+5) ;
					break;
				}
			}).addClass("active");

			target.elem.addClass("selected");
		});
		return target.id
	},
	showPage = function(e){
		var rel = resetPagination(e.target.rel);
		changeCounter(rel);
		jQuery(".results").children().hide().end().find("#result_"+rel).show();
	},
	changeCounter = function(rel){
		jQuery(".resultCount").html(jQuery("#result_"+rel + " div.searchListing:first").attr("rel") + " - " +jQuery("#result_"+rel + " div.searchListing:last").attr("rel")+ " ");
	},
	createSubCatModals = function(){
		jQuery(".subcats").dialog({
			autoOpen : false,
			bgiframe: true,
			modal: true,
			resizable : false,
			beforeclose : toggleCat,
			buttons: {
			"select all" : selectAll,
			"deselect all" : deSelectAll,
			"Complete Selection" : closeDialog
			},
			close : moveDialog

		});
		moveDialog();
		jQuery('.catcbx').click(toggleSubcats);
		jQuery('.twncbx').click(toggleTown);
		jQuery('.regcbx').click(toggleReg);
		jQuery("#searchZip input").bind("focus keyup", toggleReg);
		jQuery("input[class$='cbx_all']").change(toggleAll);

		jQuery.each(regions, function(i,val){
			jQuery(".regcbx[name='chkbx_region_"+i+"']").bind("click", {val : val}, toggleRegions);
		})
	},
	checkAllToggle = function(){
		jQuery(".catcbx_all").attr("checked", !jQuery(".catcbx:checked").size());
	},
	closeDialog = function(){
		jQuery(this).dialog('close');
		moveDialog();
	},
	moveDialog = function(){
		jQuery(".ui-dialog").appendTo(jQuery("#search form"));
	},
	toggleAll = function(event){
		if(event.target.checked){
			jQuery("."+event.target.className.split("cbx_all")[0]+"cbx").attr("checked", false);
			jQuery(".subcbx").attr("checked", false);
		}
		if(!event.target.checked){event.target.checked = true;}
	},
	toggleCat = function(){
		var selected = jQuery(this).find('input:checked').size();
		jQuery("#"+jQuery(this).attr("id").split("sub")[1]).attr("checked", selected);
		checkAllToggle();
	},
	toggleReg = function(){
		var empty = !jQuery(".regcbx:checked").size() && jQuery("#searchZip input").val() == "";
		jQuery(".regcbx_all").attr("checked", empty);
	},
	toggleRegions = function(event){
		jQuery.each(event.data.val, function(i,val){
			//console.log(i);
			jQuery("#chkbx_town_"+val).attr("checked", event.target.checked);
		});
	},
	toggleTown = function(){
		jQuery(".twncbx_all").attr("checked", !jQuery(".twncbx:checked").size());
		jQuery(".regcbx_all").attr("checked", false);
	},
	toggleSubcats = function(e) {
		if(jQuery("#sub"+e.target.id).size()){
			jQuery("#sub"+e.target.id).dialog('open');
			return false;
		}
		checkAllToggle();
	},
	selectAll = function(el) {
		//alert(el.toString())
		jQuery(this).find('input').attr("checked", true);
		//jQuery(this).find('input').attr("name", '');
	},
	deSelectAll = function(el) {
		jQuery(this).find('input').attr("checked", false);
		//jQuery(this).find('input').attr("name", 'bob');
		//$('chkbx_all_').value = '1';
	}

	jQuery(function(){
		createSubCatModals();
		buildPagination();
		//console.log(regions);
	});

	return {
		deSelectAll : deSelectAll,
		selectAll : selectAll,
		showPage : showPage,
		toggleSubcatAll : toggleSubcatAll
	}
})();

FM.Search.Map = (function(){
	var bindEvents = function(){
		jQuery(".searchInfoMap").colorbox({
			innerWidth:"750px",
			innerHeight : "430px",
			inline:true,
			href:"#colorbox_gmap",
			opacity : .4,
			onOpen : setMap,
			onComplete : FM.Directions.init,
			onCleanup : FM.Directions.reset,
			transition : 'none'
		});
		return false;
	},
	setMap = function(){
		var address = jQuery(this).parent().find(".address").html();
		city = jQuery(this).parent().find(".city").html();

		jQuery("#direction_to").val(address +" "+city);
	}

	jQuery(function(){
		if(jQuery("#searchResults").size()){bindEvents()};
	})

	return {}
})()