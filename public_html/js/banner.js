FM.Banner = {
	edit : function(divId, el) {
		pars = {
			id :divId,
			action : 'edit'
		}
		FM.doAjax('/admin/banner/edit', $H(pars).toQueryString(), FM.Banner.editResponseHandler);
	},
	
	editResponseHandler : function(transport) {
		var json = transport.responseText.evalJSON();
		$('name').value = json.name;
		$('alt').value = json.alt;
		$('title').value = json.title;
		$('url').value = json.url;
		$('editid').value = json.id;
	}
}
