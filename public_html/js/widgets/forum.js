FM.Forum = {
	submitComment : function(form) {
		errors = false;
		pars = $H();
		$(form).getInputs().each(function(el){
			if(el.name != 'submit') {
				if(el.value == ''){
					//alert(el.name)
					errors = true;
				} else {
					if(el.name == 'femail') {
						pars.set('email', el.value)
					} else if(el.name == 'fname') {
						pars.set('name', el.value)
					}
					else{
						pars.set(el.name, el.value)
					}
				}
			}
		})
		if($('forumMessage').value == ''){errors = true;} else {pars.set('message', $('forumMessage').value)}
		if(errors){alert('Please complete all fields');return false;}
		//alert($H(pars).toQueryString());
		FM.doAjax('/ajaxforumadd', $H(pars).toQueryString(),
		FM.Forum.updateCallback );
	},

	updateCallback : function(transport) {
		//alert(transport.responseText);
		if($('femail').type != 'hidden') {
			$('femail').value = '';
		}
		if($('name').type != 'hidden') {
			$('name').value = '';
		}
		$('forumMessage').value = '';
		json = transport.responseText.evalJSON();
		admin = (json.admin) ? '<a onclick="FM.Forum.deleteComment(' + json.id + ')"><img src="/images/icons/remove.png" /></a>' : '';
		main = new Element('div', {'class' : 'forumWrap', 'id' : 'forumItem_' + json.id});
		nameDate = new Element('div', {'class' : 'forumNameDate'});
		mname = new Element('div', {'class' : 'name'});
		mname.innerHTML = json.name;
		mdate = new Element('div', {'class' : 'date'});
		mdate.innerHTML = json.date + ' ' + admin;
		message = new Element ('div', {'class' : 'forumMessage'});
		message.innerHTML = json.message;
		nameDate.insert({bottom : mname});
		nameDate.insert({bottom : mdate});
		main.insert({bottom : nameDate});
		main.insert({bottom : message});
		$('messages').insert({top : main});
		FM.ajaxStatus('Your comment has been posted!');


	},

	deleteComment : function(cid) {
		pars = {
			id : cid
		}
		FM.doAjax('/ajaxforumdelete', $H(pars).toQueryString(),
		FM.Forum.deleteCallback );
	},

	deleteCallback : function(transport) {
		if(transport.responseText != '0') {
			if($('forumItem_' + transport.responseText)) {
				$('forumItem_' + transport.responseText).remove();
				FM.ajaxStatus('Comment Removed')
			}
		} else {
			alert('there was a problem removing this comments. Please refresh the page and try again.')
		}
	}
}