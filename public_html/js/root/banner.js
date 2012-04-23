FM.BannerRoot = {

	upload : function(el) {
		cpId = el.id.split('_');
		new AjaxUpload(el.id, {action: '/admin/uploadbanner',
		onComplete : function(file, response){FM.BannerRoot.uploadCallback(file, response, cpId[1])},
		data : { id : cpId[1]}});
	},

	uploadCallback : function(file, response, id){
		if(response != '0') {
			$('view_'+id).innerHTML = '<div  onclick = "FM.BannerRoot.viewBanner(\'' + response + '\')"><img src="/images/root/img/Camera.gif" alt="" height="16" width="16"></div>';
			
		} else {
			FM.ajaxStatus('Upload was unsuccessful. Please refresh page and try again', 'confirmation');
		}
	},

	markComplete : function(el) {
		cpId = el.id.split('_');
		pars = {
			id : cpId[1]
		}
		FM.doAjax('/root/ajaxmarkbannercomplete', $H(pars).toQueryString(),
		FM.BannerRoot.markCompleteHandler);
	},

	markCompleteHandler : function(transport) {
		//alert(transport.responseText);
	},

	deleteBanner : function(cpId) {
		if(confirm('Are you sure you want to delete this banner?')) {
			pars = {
				id : cpId
			}
			FM.doAjax('/root/ajaxdeletebanner', $H(pars).toQueryString(),
			FM.BannerRoot.deleteBannerHandler);
		}
	},

	deletePayBanner : function(cpId) {
		if(confirm('Are you sure you want to delete this pay banner?')) {
			pars = {
				id : cpId
			}
			FM.doAjax('/root/ajaxdeletepaybanner', $H(pars).toQueryString(),
			FM.BannerRoot.deletePayBannerHandler);
		}
	},

	deleteFullBanner : function(cpId) {
		if(confirm('Are you sure you want to delete this fullsize banner?')) {
			pars = {
				id : cpId
			}
			FM.doAjax('/root/ajaxdeletefullbanner', $H(pars).toQueryString(),
			FM.BannerRoot.deleteFullBannerHandler);
		}
	},

	
	deleteFullBannerHandler : function(transport) {
		if(transport.responseText != '0') {
			//alert(transport.responseText)
			sibs = $('tr_' + transport.responseText.strip()).nextSiblings();
			$('tr_' + transport.responseText.strip()).remove()
			//sibs[0].remove();
			//sibs[1].remove();
		} else {
			alert('Delete was unsuccessful. Please refresh page and try again');
		}
	},
	
	deleteBannerHandler : function(transport) {
		if(transport.responseText != '0') {
			sibs = $('tr_' + transport.responseText.strip()).nextSiblings();
			$('tr_' + transport.responseText.strip()).remove()
			sibs[0].remove();
			sibs[1].remove();
		} else {
			alert('Delete was unsuccessful. Please refresh page and try again');
		}
	},
	
	deletePayBannerHandler : function(transport) {
		if(transport.responseText != '0') {
			$('tr_' + transport.responseText.strip()).remove()
		} else {
			alert('Delete was unsuccessful. Please refresh page and try again');
		}
	},

	deleteBannerTemplate : function(cpId) {
		if(confirm('Are you sure you want to delete this template?')) {
			pars = {
				id : cpId
			}
			FM.doAjax('/root/ajaxdeletebannertemplate', $H(pars).toQueryString(),
			FM.BannerRoot.deleteBannerTemplateHandler);
		}
	},

	deleteBannerTemplateHandler : function(transport) {
		if(transport.responseText != '0') {
			//alert(transport.responseText)
			$('tr_' + transport.responseText.strip()).remove()
		} else {
			alert('Delete was unsuccessful. Please refresh page and try again');
		}
	},

	toggleTemplateActive : function(tid) {
		state = ($('templateactive_' + tid).checked) ? '1' : '0';
		pars = {
			id : tid,
			active : state
		}
		FM.doAjax('/root/ajaxtogglebannertemplate', $H(pars).toQueryString(),
		FM.BannerRoot.toggleTemplateActiveHandler);
	},

	toggleUsePaybanner : function(tid) {
		state = ($('usepaybanners').checked) ? '1' : '0';
		pars = {
			active : state
		}
		FM.doAjax('/root/ajaxtoggleusepaybanner', $H(pars).toQueryString(),
		FM.BannerRoot.toggleActiveHandler);
	},
	
	toggleUseFullbanner : function(tid) {
		state = ($('usefullbanners').checked) ? '1' : '0';
		pars = {
			active : state
		}
		FM.doAjax('/root/ajaxtoggleusefullbanner', $H(pars).toQueryString(),
		FM.BannerRoot.toggleActiveHandler);
	},

	toggleActive : function(tid) {
		state = ($('active_' + tid).checked) ? '1' : '0';
		pars = {
			id : tid,
			active : state
		}
		FM.doAjax('/root/ajaxtogglebanner', $H(pars).toQueryString(),
		FM.BannerRoot.toggleActiveHandler);
	},

	togglePaybannerActive : function(tid) {
		state = ($('active_' + tid).checked) ? '1' : '0';
		pars = {
			id : tid,
			active : state
		}
		FM.doAjax('/root/ajaxtogglepaybanner', $H(pars).toQueryString(),
		FM.BannerRoot.toggleActiveHandler);
	},
	
	toggleFullbannerActive : function(tid) {
		state = ($('active_' + tid).checked) ? '1' : '0';
		pars = {
			id : tid,
			active : state
		}
		FM.doAjax('/root/ajaxtogglefullbanner', $H(pars).toQueryString(),
		FM.BannerRoot.toggleActiveHandler);
	},

	toggleActiveHandler : function(transport) {
		if(transport.responseText == '1') {
			FM.ajaxStatus('Update Successful!', 'statusMessage');
		}
	},

	toggleTemplateActiveHandler : function(transport) {
		if(transport.responseText == '1') {
			FM.ajaxStatus('Update Successful!', 'statusMessage');
		}
	},

	viewTemplate : function(path) {
		path = '/media/image/banner_templates/' + path;
		Shadowbox.open({content:path, player:"img"});
	}
}

FM.ImageBannerRoot = {

	currentLayout : null,

	update : function(id) {
		//alert(id);
	},
	updateImage : function(target, imgPath, imgId) {
		$(target).style.backgroundImage = 'url(' + imgPath + ')'
	}

}