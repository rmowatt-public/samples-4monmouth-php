FM.CouponRoot = {
	
	test : function() {
		//alert('dude')
	},
	
	upload : function(el) {
		cpId = el.id.split('_');
		new AjaxUpload(el.id, {action: '/admin/uploadcoupon',
		onComplete : function(file, response){FM.CouponRoot.uploadCallback(file, response, cpId[1])},
		data : { id : cpId[1]}});
	},

	uploadCallback : function(file, response, id){
		if(response != '0') {
			//if($('view_'+id).innerHTML.strip() == 'X') {
			//alert(response)
			$('view_'+id).innerHTML = '<div  onclick = "FM.CouponRoot.viewCoupon(\'' + response + '\')"><img src="/images/root/img/Camera.gif" alt="" height="16" width="16"></div>';
			//}
		} else {
			alert('Upload was unsuccessful. Please refresh page and try again');
		}
	},

	markComplete : function(el) {
		cpId = el.id.split('_');
		//alert(cpId[1]);
		pars = {
			id : cpId[1]
		}
		FM.doAjax('/root/ajaxmarkcouponcomplete', $H(pars).toQueryString(),
		FM.CouponRoot.markCompleteHandler);
	},

	markCompleteHandler : function(transport) {
		//alert(transport.responseText);
	},

	deleteCoupon : function(cpId) {
		if(confirm('Are you sure you want to delete this coupon?')) {
			pars = {
				id : cpId
			}
			FM.doAjax('/root/ajaxdeletecoupon', $H(pars).toQueryString(),
			FM.CouponRoot.deleteCouponHandler);
		}
	},

	deleteCouponHandler : function(transport) {
		if(transport.responseText != '0') {
			//alert(transport.responseText)
			sibs = $('tr_' + transport.responseText.strip()).nextSiblings();
			$('tr_' + transport.responseText.strip()).remove()
			sibs[0].remove();
			sibs[1].remove();
		} else {
			alert('Delete was unsuccessful. Please refresh page and try again');
		}
	},

	deleteCouponTemplate : function(cpId) {
		if(confirm('Are you sure you want to delete this template?')) {
			pars = {
				id : cpId
			}
			FM.doAjax('/root/ajaxdeletecoupontemplate', $H(pars).toQueryString(),
			FM.CouponRoot.deleteCouponTemplateHandler);
		}
	},

	deleteCouponTemplateHandler : function(transport) {
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
		FM.doAjax('/root/ajaxtogglecoupontemplate', $H(pars).toQueryString(),
		FM.CouponRoot.toggleTemplateActiveHandler);
	},
	
	toggleTemplateActiveHandler : function(transport) {
		//alert(transport.responseText);
	}
}