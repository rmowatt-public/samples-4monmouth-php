FM.Utilities = {
	viewBanner : function(path) {
		Shadowbox.open({content:path, player:"img"});
	},

	setRegion : function(id) {
		pars = {
			id : id
		}
		FM.doAjax('/root/ajaxgetregion', $H(pars).toQueryString(),
		FM.Utilities.setRegionCallback );
	},

	deleteMedia : function(type, media, tool, el) {
		if(confirm("are you sure you want to delete this media?")) {
			pars = {
				type : type,
				media : media,
				tool : tool
			}
			FM.doAjax('/root/ajaxdeleteutilmedia', $H(pars).toQueryString(),
			function(transport){FM.Utilities.deleteMediaCallback(transport, el)});
		}
	},

	deleteMediaCallback : function(transport, el) {
		if(transport.responseText == '1'){
		el.up().remove();
		} else {
			alert("request failed. Please refresh page and try again")
		}
	},


	setRegionCallback : function(transport) {
		//alert(transport.responseText)
		if(transport.responseText != 0) {
			id = transport.responseText;
			regions = $A($('region-element').getElementsByTagName('input'));
			regions.each(function(el){el.checked = false;if(el.id == 'region_' + id){el.checked = true;}})
		}

	},

	toggleNpBanners : function(el) {
		pars = {
			active : (el.checked) ? '1' : '0'
		}
		FM.doAjax('/root/ajaxtogglenpbanners', $H(pars).toQueryString(),
		FM.Utilities.toggleNpBannersCallback );
	},

	toggleNpBannersCallback : function(transport) {
		if(transport.responseText == 1) {
			FM.ajaxStatus('Request Successful', 'confirmation')
		} else {
			//alert(transport.responseText)
		}
	},
	
	editSiteEmail : function(id, name, email) {
		$('name').value = name;
		$('email').value = email;
		$('id').value = id;
	},
	
	clearSiteEmail : function(id, name, email) {
		$('name').value = '';
		$('email').value = '';
		$('id').value = '';
	}

}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

