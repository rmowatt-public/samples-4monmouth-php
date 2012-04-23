FM.CouponWidget = {

	selected : 0,

	uploaded : false,

	editMode : false,

	create : function() {
		if($A($('managecoupontable').getElementsByTagName('tr')).size() > 12) {
			alert('There is a limit of 12 coupons. You must delete at least 1 before you may create a new coupon.')
			return false;
		}

		if(!this.selected.value) {
			alert('Please Choose A Coupon Type');
			return false;
		}
		if(!$('bannerlogoimg') || !$('bannerlogoimg').src) {
			alert('Please Upload A Logo');
			return false;
		}
		if(!$('name')) {
			alert('Please provide a name for reference');
			return false;
		}
		src = $('bannerlogoimg').src.split('/');
		el = src[src.size() - 1];
		pars = {
			cid : ($('couponMediaId').value.strip() != '') ? $('couponMediaId').value : 0,
			orgId : $('orgId').value,
			type : this.selected.value,
			sponsor : $('sponsor').value,
			offer : $('offer').value,
			valid : $('valid').value,
			code : $('code').value,
			copy : $('copy').value,
			b2b : ($('btb').checked) ? '1' : '0',
			file : el
		}

		vars = $H(pars).toQueryString();
		FM.doAjax('/utilities/ajaxaddcoupon', vars, FM.CouponWidget.createCallback);
	},

	createCallback : function(transport) {
		//alert(transport.responseText)
		var json = transport.responseText.evalJSON();
		if(json.id) {
			$('bannerlogoimg').src = '';
			tr = new Element('tr', {'id' : 'managecoupons_' + json.id, 'class':'new'});
			id = new Element('td', {'align':'center'})
			id.innerHTML = json.id;
			tr.insert({bottom : id});
			name = new Element('td')
			name.innerHTML = json.code;
			tr.insert({bottom : name});
			active = new Element('td',{'align' : 'center'});
			checked = (json.active == 1) ? ' CHECKED ' : '';
			active.innerHTML = '<input type="checkbox" onclick = "FM.CouponWidget.toggleActive(this,' + json.id + ')" name="" '+ checked +'  />';
			tr.insert({bottom : active});

			btb = new Element('td',{'align' : 'center'});
			b = new Element('input', {'type' : 'checkbox', 'checked' : (json.b2b ==1) ? true : false});
			//alert('4')
			btb.insert({bottom : b});
			tr.insert({bottom : btb});

			tview = new Element('td',{'align' : 'center'});
			tview.innerHTML = '<a href="/couponpreview/' + json.id + '" class="manage_coupon cboxElement" title="' + json.copy + '"><img src="/images/icons/preview.png"></a>';
			tr.insert({bottom : tview});


			editTd = new Element('td',{'align' : 'center'});
			editTd.innerHTML = '<a onclick = "FM.CouponWidget.edit(' + json.id + ')"  title="' + json.code + '"><img src="/images/icons/edit.png" /></a>';
			tr.insert({bottom : editTd});

			deleteTd = new Element('td',{'align' : 'center'});
			deleteTd.innerHTML = '<a class="delete_coupon_'+json.id+'" href="#"><img src="/images/icons/remove.png"></a>';
			tr.insert({bottom : deleteTd});
			if($('managecoupons_' + json.id)){
				$('managecoupons_' + json.id).replace(tr);
			}else {
				$('managecouponheaders').insert({after : tr});
			}


			jQuery(".manage_coupon").colorbox({
				transition : "none",
				opacity : .4
			});

			jQuery("a[class^='delete_coupon']").unbind("click").click(function(){
				var id = jQuery(this).attr("class").split("delete_coupon_")[1];
				FM.CouponWidget.clientDelete(id);
				return false;
			})

			FM.CouponWidget.clear();

			FM.ajaxStatus('Your coupon  has been created' , 'admin');
			FM.createCoupons.init();
			FM.createCoupons.uploaded = false;
			jQuery("#createCoupons .upload_preview").html(" ").hide();
		} else {
			FM.ajaxStatus('Coupon Submission failed. Please refresh page and try again' , 'admin');
		}
	},

	clear : function() {
		FM.CouponWidget.selected.checked = false;
		FM.CouponWidget.selected = 0,
		$('sponsor').value = '';
		$('offer').value = '';
		$('valid').value = '';
		$('code').value = '';
		$('copy').value = '';
		$('btb').checked = false;
		$('cdatepicker_input').value = '';
		$('couponMediaId').value = '';
		jQuery("#createCoupons .upload_preview").html(" ").hide();
	},

	clientDelete : function(id) {
		if(confirm('Are you sure you want to delete custom coupon ' + id)) {
			pars = {
				orgId : $('orgId').value,
				couponId : id
			}
			vars = $H(pars).toQueryString();
			FM.doAjax('/utilities/ajaxdeletecoupon', vars, FM.CouponWidget.clientDeleteCallback);
		}
	},

	clientDeleteCallback : function(transport) {
		if(transport.responseText != '0') {
			$('managecoupons_'+transport.responseText).remove();
			FM.ajaxStatus('Coupon has been deleted' , 'admin');
		} else {
			FM.ajaxStatus('Coupon Deletion failed. Please refresh page and try again' , 'admin');
		}
	},



	edit : function(cid){
		var pars = {
			oid : $('orgId').value,
			id : cid
		}
		vars = $H(pars).toQueryString();
		FM.doAjax('/utilities/ajaxgetcouponinfo', vars, FM.CouponWidget.editCallback);


	},


	editCallback : function(transport) {
		//alert(transport.responseText)
		FM.CouponWidget.editMode = true;
		if($('createCoupons').style.display != 'block'){$('createCoupons').style.display = 'block'}
	//	if($('createCoupons').style.display != 'inline'){$('createCoupons').style.display = 'inline';}
		var json = transport.responseText.evalJSON();
		$('code').value = json.code;
		$('btb').checked = json.b2b;
		$('sponsor').value = json.sponsor;
		$('offer').value = json.offer;
		$('cdatepicker_input').value = json.valid;
		$('valid').value = json.valid;
		$('copy').value = json.copy;
		$('ct_' + json.type).checked = true;
		FM.CouponWidget.selected = $('ct_' + json.type);
		$('couponMediaId').value = json.id;

		//if(FM.BannerWidget.setSelected(null, json.type)) {
		if(json.file != '') {
			jQuery("#createCoupons .upload_preview").show().html('<label>Image:<br><b>60x60</b></label><img id="bannerlogoimg" src="/media/image/couponlogos/' + json.file + '" alt="60x60" />');
		}
		//}
		//if(json.template.logo == 1){FM.BannerWidget.uploaded = true;}
		//jQuery("#createBanners .upload_preview").show().html('<label>Image:<br><b>'+json.template.width+"x"+json.template.height+'</b></label><img id="bannerlogoimg" src="'+json.path + '/' + json.medianame + '" alt="'+json.template.width + "x"+ json.template.height +'" />');
		//FM.BannerWidget.setSelected(null, json.type);

	},





	setSelected : function(el) {
		this.selected = el;
	},

	toggleActive : function(el, mid) {
		pars = {
			id : mid,
			active : (el.checked) ? '1' : '0'
		}
		vars = $H(pars).toQueryString();
		FM.doAjax('/utilities/ajaxtogglecoupon', vars, FM.CouponWidget.toggleActiveCallback);
	},

	toggleB2b : function(el, mid) {
		pars = {
			id : mid,
			b2b : (el.checked) ? '1' : '0'
		}
		vars = $H(pars).toQueryString();
		FM.doAjax('/utilities/ajaxtoggleb2bcoupon', vars, FM.CouponWidget.toggleB2bCallback);
	},

	toggleB2bCallback : function(transport) {
		if(transport.responseText == 1) {
			FM.ajaxStatus('Success' , 'admin');
		}
	},

	toggleActiveCallback : function(transport) {
		if(transport.responseText == 1) {
			FM.ajaxStatus('Success' , 'admin');
		}
	},

	createPrint : function() {
		cps = $A($('couponlayout').getElementsByTagName('input'));
		selected = new Array();
		cps.each(
		function(el) {
			if(el.checked) {
				//alert(el.id);
				id = el.id.split('_');
				selected.push(id[1])
			}
		}
		)
		if(selected.size() == 0){alert('Please Choose Coupon(s) To Print');return false;}
		c = selected.join('_')
		pars = {
			coupons : c
		}
		vars = $H(pars).toQueryString();
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open('" +  'http://4monmouth.com/utilities/printcoupons/?' + vars + "', '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=550,height=400,left = 470,top = 300');");
		cps.each(
		function(el) {
			if(el.checked) {
				el.checked = false;
			}
		}
		)
		//location.href = '/utilities/printcoupons/?' + vars;
	},
	
	createPrintB2b : function() {
		cps = $A($('b2bcouponlayout').getElementsByTagName('input'));
		selected = new Array();
		cps.each(
		function(el) {
			if(el.checked) {
				//alert(el.id);
				id = el.id.split('_');
				selected.push(id[1])
			}
		}
		)
		if(selected.size() == 0){alert('Please Choose Coupon(s) To Print');return false;}
		c = selected.join('_')
		pars = {
			coupons : c
		}
		vars = $H(pars).toQueryString();
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open('" +  'http://4monmouth.com/utilities/printcoupons/?' + vars + "', '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=550,height=400,left = 470,top = 300');");
		cps.each(
		function(el) {
			if(el.checked) {
				el.checked = false;
			}
		}
		)
		//location.href = '/utilities/printcoupons/?' + vars;
	},

	/*viewCoupon : function(path) {
	path = '/media/image/coupons/' + path;
	Shadowbox.open({content:path, player:"img"});
	},*/
	bindCouponEvents : function(){
		jQuery(".manage_coupon").colorbox({
			transition : "none",
			opacity : .4
		});
		jQuery("a[class^='delete_coupon']").unbind("click").click(function(){
			var id = jQuery(this).attr("class").split("delete_coupon_")[1];
			FM.CouponWidget.clientDelete(id);
			return false;
		})
	},
	init : function(){
		this.bindCouponEvents();
	}
}