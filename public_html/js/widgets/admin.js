FM.AdminWidget = {

	swfu : null,
	swfuBanner : null,
	swfuTopBanner : null,
	swfProduct : null,
	swfListing : null,
	swfPhoto : null,
	settings_object : null,
	settings_object2 : null,
	settings_objectTopBanner : null,


	prepUploaders : function() {

		//** COUPON **/
		if($('spanSWFUploadButton')) {
			FM.AdminWidget.settings_object = {
				upload_url : "/ajaxuploadcouponlogo",
				flash_url : "/swfupload.swf",
				file_size_limit : "20 MB",
				button_placeholder_id : "spanSWFUploadButton" ,
				button_width : 62,
				button_height : 62,
				button_text : "",
				debug :false,
				button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor : SWFUpload.CURSOR.HAND,
				swfupload_loaded_handler : FM.Loaders.loaded,
				upload_error_handler : FM.Loaders.error,
				upload_success_handler : FM.Loaders.success,
				upload_complete_handler : FM.Loaders.complete,
				upload_progress_handler : FM.Loaders.progress,
				upload_start_handler : FM.Loaders.start,
				file_dialog_complete_handler : FM.Loaders.dialogComplete
			};
			FM.AdminWidget.swfu = new SWFUpload(FM.AdminWidget.settings_object);
		}

		if($('spanSWFUploadButtonBanner')) {
			FM.AdminWidget.settings_object2 = {
				upload_url : "/ajaxuploadbannerlogo",
				flash_url : "/swfupload.swf",
				file_size_limit : "20 MB",
				button_placeholder_id : "spanSWFUploadButtonBanner" ,
				button_width : 62,
				button_height : 62,
				button_text : "",
				debug :false,
				button_text_style : ".redText { color: #FFFFFF; }",
				button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor : SWFUpload.CURSOR.HAND,
				swfupload_loaded_handler : FM.Loaders.loaded,
				upload_error_handler : FM.Loaders.error,
				upload_success_handler : FM.Loaders.successBanner,
				upload_complete_handler : FM.Loaders.completeBanner,
				upload_progress_handler : FM.Loaders.progress,
				upload_start_handler : FM.Loaders.start,
				post_params : null,
				file_dialog_complete_handler : FM.Loaders.dialogCompleteBanner
			};

			FM.AdminWidget.swfuBanner = new SWFUpload(FM.AdminWidget.settings_object2);
		}

		if($('spanSWFUploadTopBanner')) {
			FM.AdminWidget.settings_objectTopBanner = {
				upload_url : "/ajaxuploadtopbanner",
				flash_url : "/swfupload.swf",
				file_size_limit : "20 MB",
				button_placeholder_id : "spanSWFUploadTopBanner" ,
				button_width : 62,
				button_height : 62,
				button_text : "",
				debug :false,
				button_text_style : ".redText { color: #FFFFFF; }",
				button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor : SWFUpload.CURSOR.HAND,
				swfupload_loaded_handler : FM.Loaders.loaded,
				upload_error_handler : FM.Loaders.error,
				upload_success_handler : FM.Loaders.successTopBanner,
				upload_complete_handler : FM.Loaders.completeTopBanner,
				upload_progress_handler : FM.Loaders.progress,
				upload_start_handler : FM.Loaders.start,
				post_params : null,
				file_dialog_complete_handler : FM.Loaders.dialogCompleteTopBanner
			};

			FM.AdminWidget.swfuTopBanner = new SWFUpload(FM.AdminWidget.settings_objectTopBanner);
		}

		if($('swfUploadProduct')) {
			FM.AdminWidget.settings_objectProduct = {
				upload_url : "/ajaxuploadproductimage",
				flash_url : "/swfupload.swf",
				file_size_limit : "20 MB",
				button_placeholder_id : "swfUploadProduct" ,
				button_width : 62,
				button_height : 62,
				button_text : "",
				debug :false,
				button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor : SWFUpload.CURSOR.HAND,
				swfupload_loaded_handler : FM.Loaders.loaded,
				upload_error_handler : FM.Loaders.error,
				upload_success_handler : FM.Loaders.successProduct,
				upload_complete_handler : FM.Loaders.completeProduct,
				upload_progress_handler : FM.Loaders.progress,
				upload_start_handler : FM.Loaders.start,
				post_params : null,
				file_dialog_complete_handler : FM.Loaders.dialogCompleteProduct
			};


			FM.AdminWidget.swfProduct = new SWFUpload(FM.AdminWidget.settings_objectProduct);
		}

		if($('swfUploadListing')) {
			FM.AdminWidget.settings_objectListing = {
				upload_url : "/ajaxuploadproductimage",
				flash_url : "/swfupload.swf",
				file_size_limit : "20 MB",
				button_placeholder_id : "swfUploadListing" ,
				button_width : 62,
				button_height : 62,
				button_text : "",
				debug :false,
				button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor : SWFUpload.CURSOR.HAND,
				swfupload_loaded_handler : FM.Loaders.loaded,
				upload_error_handler : FM.Loaders.error,
				upload_success_handler : FM.Loaders.successListing,
				upload_complete_handler : FM.Loaders.completeListing,
				upload_progress_handler : FM.Loaders.progress,
				upload_start_handler : FM.Loaders.start,
				post_params : null,
				file_dialog_complete_handler : FM.Loaders.dialogCompleteListing
			};
			FM.AdminWidget.swfListing = new SWFUpload(FM.AdminWidget.settings_objectListing);
		}

		if($('swfUploadPhotoGallery')) {
			FM.AdminWidget.settings_photogallery = {
				upload_url : "/ajaxuploadimagegallery",
				flash_url : "/swfupload.swf",
				file_size_limit : "20 MB",
				button_placeholder_id : "swfUploadPhotoGallery" ,
				button_width : 62,
				button_height : 62,
				debug :false,
				button_text_style : ".redText { color: #FFFFFF; }",
				button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor : SWFUpload.CURSOR.HAND,
				swfupload_loaded_handler : FM.Loaders.loaded,
				upload_error_handler : FM.Loaders.error,
				upload_success_handler : FM.Loaders.successPhoto,
				upload_complete_handler : FM.Loaders.completePhoto,
				upload_progress_handler : FM.Loaders.progress,
				upload_start_handler : FM.Loaders.start,
				post_params : null,
				file_dialog_complete_handler : FM.Loaders.dialogCompletePhoto
			};
			FM.AdminWidget.swfPhoto = new SWFUpload(FM.AdminWidget.settings_photogallery);
		}

	},

	updateSwfSettings : function(obj, height, width) {

		if(obj == 'coupon') {

		} else if (obj == 'product') {
			FM.AdminWidget.swfProduct.addPostParam('orgId', $('orgId').value);
			FM.AdminWidget.swfProduct.addPostParam('height', height);
			FM.AdminWidget.swfProduct.addPostParam('width', width);
		}
		else if (obj == 'listing') {
			FM.AdminWidget.swfListing.addPostParam('orgId', $('orgId').value);
			FM.AdminWidget.swfListing.addPostParam('height', height);
			FM.AdminWidget.swfListing.addPostParam('width', width);
		}
		else if (obj == 'gallery') {
			FM.AdminWidget.swfPhoto.addPostParam('orgId', $('orgId').value);
			FM.AdminWidget.swfPhoto.addPostParam('height', height);
			FM.AdminWidget.swfPhoto.addPostParam('width', width);
		}
		else {
			FM.AdminWidget.swfuBanner.addPostParam('height', height);
			FM.AdminWidget.swfuBanner.addPostParam('width', width);
		}
	},

	CommonFeatures : {
		doEdit : function(el) {
			if($(el.name + '_wr')) {
				$(el.name + '_wr').style.display = (el.checked) ? 'inline' : 'none';
			}
			if($(el.name + '_wr2')) {
				$(el.name + '_wr2').style.display = (el.checked) ? 'inline' : 'none';
			}
			pars = {
				orgId : $('orgId').value,
				type : 	el.id,
				column : el.name,
				status : (el.checked) ? 1 : 0
			}
			FM.doAjax('/b2b/ajaxcommonfeatures', $H(pars).toQueryString(),
			FM.AdminWidget.CommonFeatures.doEditCallback );
		},

		doEditCallback : function(transport) {
			response = (transport.responseText == '1') ?
			'Operation Successful. Changes will show on next page load.' :
			'Operation failed. Please refresh page and try again.';
			FM.ajaxStatus(response , 'admin');
		}
	},

	Testimonials : {

		edit : 0,

		add : function () {
			var pars = {
				orgId : $('orgId').value,
				name : $('reviewername').value,
				from : $('from').value,
				testimonial : $('testimonialInput').value,
				edit : FM.AdminWidget.Testimonials.edit
			}
			FM.doAjax('/utilities/ajaxaddtestimonial', $H(pars).toQueryString(),
			function(transport){FM.AdminWidget.Testimonials.addCallback(transport, FM.AdminWidget.Testimonials.edit) });
		},

		addCallback : function (transport, id) {
			//alert(transport.responseText);
			if(transport.responseText == '1') {
				FM.ajaxStatus('Testimonial has been added' , 'admin');
				$('reviewername').value = '';
				$('from').value = '';
				$('testimonialInput').value = '';
				if(FM.AdminWidget.Testimonials.edit) {

				}
			}
		},

		deleteTestimonial : function(testiId) {
			var pars = {
				orgId : $('orgId').value,
				id : testiId
			}
			FM.doAjax('/utilities/ajaxdeletetestimonial', $H(pars).toQueryString(),
			FM.AdminWidget.Testimonials.deleteTestimonialCallback );
		},

		deleteTestimonialCallback : function(transport) {
			//alert(transport.responseText);
			$('del_' + transport.responseText).remove();
		},

		populateedit : function(el) {
			var ids = el.id.split('_');
			id = ids[1];
			pars = {
				id : ids[1],
				action : 'popedit'
			}
			//alert(id)
			FM.doAjax('/utilities/ajaxedittestimonial', $H(pars).toQueryString(),
			FM.AdminWidget.Testimonials.populateeditCallback );
		},

		populateeditCallback : function(transport) {
			//alert(transport.responseText)
			faq = transport.responseText.evalJSON();
			$('reviewername').value = faq.name;
			$('from').value = faq.from;
			$('testimonialInput').value = faq.testimonial;
			FM.AdminWidget.Testimonials.edit = faq.id;
		},

		toggleActive : function(el, mid) {
			pars = {
				active : (el.checked) ? '1' : '0',
				id : mid
			}
			vars = $H(pars).toQueryString();
			//alert(vars);
			FM.doAjax('/utilities/ajaxtogglereview', vars, FM.AdminWidget.Testimonials.toggleActiveCallback);
		},

		toggleActiveCallback : function (transport) {
			if(transport.responseText == '1') {
				FM.ajaxStatus('Action was a success!', 'confirmation')
			} else {
				alert('Action failed, Please refresh page and try again')
			}
		}


	},

	TestimonialsTab : {

		edit : 0,

		add : function () {
			if($('feedback').value == '' || $('reviewname').value == '' || !FM.checkEmail($('email').value)) {
				alert("please make sure all fields are complete and email address is valid!");
				return false;
			}
			var pars = {
				orgId : $('orgId').value,
				name : $('reviewname').value,
				from : $('email').value,
				testimonial : $('feedback').value,
				edit : FM.AdminWidget.TestimonialsTab.edit
			}
			FM.doAjax('/utilities/ajaxaddtestimonial', $H(pars).toQueryString(),
			FM.AdminWidget.TestimonialsTab.addCallback );
		},

		addCallback : function (transport) {
			if(transport.responseText == '1') {
				FM.ajaxStatus('Thank you. Your review is being processed', 'confirmation')
				$('reviewname').value = '';
				$('email').value = '';
				$('feedback').value = '';
			}
		}
	}
}

FM.ProfileEdit = {
	update : function(copy, orgType) {
		pars = {
			description : copy,
			orgId : $('orgId').value,
			ot : orgType
		}
		//alert($H(pars).toQueryString());
		FM.doAjax('/ajaxupdateprofile', $H(pars).toQueryString(),
		FM.ProfileEdit.updateCallback );
	},

	updateCallback : function(transport) {
		//alert(transport.responseText)
		response = (transport.responseText == '1') ?
		'Profile Updated Successfully. Refresh page and select the \'Profile\' tab to view' :
		'Operation failed. Please refresh page and try again.';
		//FM.ajaxStatus(response , 'admin');
		FM.ajaxStatus(response, 'confirmation');
	}
}

FM.OrgDataEdit = {
	update : function() {
		pars = {
			orgId : $('orgId').value,
			name : $('updateName').value,
			address1 : $('updateAddress1').value,
			address2 : $('updateAddress2').value,
			city : $('updateCity').value,
			state : $('updateState').value,
			zip : $('updateZip').value,
			phone : $('updatePhone').value,
			email : $('updateEmail').value,
			slug : $('updateSlug').value,
			website : $('updateWebsite').value
		}
		//alert($H(pars).toQueryString());
		FM.doAjax('/ajaxupdateorgdata', $H(pars).toQueryString(),
		FM.OrgDataEdit.updateCallback );
	},

	updateCallback : function(transport) {
		response = (transport.responseText == '1') ?
		'Profile Updated Successfully. Refresh page and select the \'Profile\' tab to view' :
		'Operation failed. Please refresh page and try again.';
		//FM.ajaxStatus(response , 'admin');
		FM.ajaxStatus(response, 'confirmation');
	},

	checkSlug : function(e, original) {
		if(e.value == ''){return false;}
		if(e.value.strip().indexOf(' ') >= 0) {
			alert ('link can not contain spaces')
			return false;
		}
		var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";
		for (var i = 0; i < e.value.length; i++) {
			if (iChars.indexOf(e.value.charAt(i)) != -1) {
				alert ("Your string has special characters. \nThese are not allowed.");
				return false;
			}
		}
		pars = {
			slug : e.value.strip(),
			orgId : $('orgId').value
		}
		FM.doAjax('/utilities/ajaxcheckslug', $H(pars).toQueryString(),
		function(transport) {
			if(transport.responseText != 1) {
				alert('this link is already in use, please choose another');
				$('updateSlug').value = original;
				return false;
			}
		}

		);
	}

}

FM.ProductList = {
	createProduct : function () {
		descr = tinyMCE.get('productDesc').getContent();
		pars = {
			orgId : $('orgId').value,
			name : $('productName').value,
			link : $('productLink').value,
			description : descr,
			image : $('productimagename').value
		}
		//alert($H(pars).toQueryString());
		FM.doAjax('/ajaxaddproduct', $H(pars).toQueryString(),
		FM.ProductList.updateCallback );
	},

	updateCallback : function(transport) {
		//if(transport.responseText == '1') {
		$('productName').value = '';
		$('productLink').value = '';
		tinyMCE.get('productDesc').setContent('');
		$('productDesc').value = '';
		$('productimagename').value = '';
		$('productimage').innerHTML = '60 X 60';
		$('bannerlogoimg').src = '';
		//}
		response = (transport.responseText == '1') ?
		'Product added Successfully. Refresh page and select the \'Products\' tab to view' :
		'Operation failed. Please refresh page and try again.';
		FM.ajaxStatus(response , 'admin');
	},

	deleteNode : function(pid) {
		if(confirm('Are You Sure That you Want To Delete This Product ?')) {
			pars = {
				orgId : $('orgId').value,
				id : pid
			}
			//alert($H(pars).toQueryString());
			FM.doAjax('/ajaxdeleteproduct', $H(pars).toQueryString(),
			function(transport){FM.ProductList.deleteCallback(transport, pid)})
		}
	},

	deleteCallback : function(transport, pid) {
		if(transport.responseText == '1') {$('product_' + pid).remove();}
		response = (transport.responseText == '1') ?
		'Product deleted Successfully. Refresh page and select the \'Products\' tab to view' :
		'Operation failed. Please refresh page and try again.';
		FM.ajaxStatus(response , 'admin');
	}
}

FM.RealEstateList = {
	createListing : function () {
		//if($('realestateimagename').value == ''){
		if($('realestateimagename').value == ''){
			alert('please select an image');
			return false;
		}
		if($('realestateName').value == ''){
			alert('please provide an identifier');
			return false;
		}
		pars = {
			orgId : $('orgId').value,
			name : $('realestateName').value,
			link : $('realestateLink').value,
			address : $('realestateAddress').value,
			city : $('realestateCity').value,
			state : $('realestateState').value,
			zip : $('realestateZip').value,
			description : $('realestateDesc').value,
			image : $('realestateimagename').value
		}
		//alert($H(pars).toQueryString());
		FM.doAjax('/ajaxaddrelisting', $H(pars).toQueryString(),
		FM.RealEstateList.updateCallback );
	},

	updateCallback : function(transport) {
		//alert(transport.responseText);
		//return false;
		if(transport.responseText == '1') {
			$('realestateName').value = '';
			$('realestateLink').value = '';
			$('realestateDesc').value = '';
			$('realestateAddress').value = '';
			$('realestateCity').value = '';
			$('realestateState').value = '';
			$('realestateZip').value = '';
			jQuery("#realestatelistAdmin .upload_preview").show().html('');

			$('realestateimage').src = '';
			$('realestateimage').value = '';
		}
		response = (transport.responseText == '1') ?
		'Listing added Successfully. Refresh page and select the \'RealEstate\' tab to view' :
		'Operation failed. Please refresh page and try again.';
		FM.ajaxStatus(response , 'admin');
	},

	deleteNode : function(pid) {
		if(confirm('Are You Sure That you Want To Delete This Listing ?')) {
			pars = {
				orgId : $('orgId').value,
				id : pid
			}
			//alert($H(pars).toQueryString());
			FM.doAjax('/ajaxdeletelisting', $H(pars).toQueryString(),
			function(transport){FM.RealEstateList.deleteCallback(transport, pid)})
		}
	},

	deleteCallback : function(transport, pid) {
		//alert(transport.responseText);
		response = (transport.responseText == '1') ?
		'Listing Deleted Successfully. Refresh page and select the \'R/E\' tab to view' :
		'Operation failed. Please refresh page and try again.';
		FM.ajaxStatus(response , 'admin');
		if(transport.responseText == '1') {$('estate_' + pid).remove();}
	}
}

FM.Loaders = {
	loaded : function() {
	},

	progress : function(file, bytesComplete, totalBytes) {
		//alert(bytesComplete + ' of ' + totalBytes)
	},

	complete : function() {
		//alert('complete')
	},

	completeBanner : function() {
		//alert('complete')
	},

	completeTopBanner : function() {
		//alert('complete')
	},

	completeProduct : function() {
		//alert('complete')
	},

	completePhoto : function() {
		//alert('complete')
	},

	completeListing : function() {
		//alert('complete')
	},


	success : function(file, serverData, recievedResponse) {
		jQuery("#createCoupons .upload_preview").show().html('<label>Image:</label><img id="bannerlogoimg" src="'+serverData+'" />');
		FM.CouponWidget.uploaded = true;
	},

	successBanner : function(file, serverData, recievedResponse) {
		if(serverData == '0'){
			alert('Image is too large');
			return false;
		}
		json = serverData.evalJSON();
		jQuery("#createBanners .upload_preview").show().html('<label>Image:<br><b>' + json.width + "x" + json.height + '</b></label><img id="bannerlogoimg" style="width:'+FM.BannerWidget.imageWidth +'px;height:'+FM.BannerWidget.imageHeight +'px;" src="'+json.src+'" alt="'+json.width+"x"+json.height+'" />');
		FM.BannerWidget.uploaded = true;
	},

	successTopBanner : function(file, serverData, recievedResponse) {
		//alert(serverData)
		if(serverData == '0'){
			alert('Image is too large');
			return false;
		}
		if(!FM.stristr(serverData, 'icon')){
			FM.ajaxStatus('Image has been updated. Refresh to view.', 'confirmation')
			FM.topBannerWidget.resetSelected();
		} else {
			FM.topBannerWidget.updateIcon(serverData);
		}
		//json = serverData.evalJSON();
		//jQuery("#updateTopBanners .upload_preview").show().html('<label>Image:<br><b>'+json.width+"x"+json.height+'</b></label><img id="bannertoplogo" src="'+json.src+'" alt="'+json.width+"x"+json.height+'" />');
		//FM.BannerWidget.uploaded = true;
	},

	successProduct : function(file, serverData, recievedResponse) {
		if(serverData == '0'){
			alert('Image is too large');
			return false;
		}
		json = serverData.evalJSON();
		json = serverData.evalJSON();
		jQuery("#productlistAdmin .upload_preview").show().html('<label>Image:<br><b>'+json.width+"x"+json.height+'</b></label><img id="bannerlogoimg" style="max-height:60px;max-width:60px;" src="'+json.src+'" alt="'+json.width+"x"+json.height+'" />');
		$('productimagename').value = json.name

	},

	successListing : function(file, serverData, recievedResponse) {
		//alert(serverData);
		if(serverData == '0'){
			alert('Image is too large');
			return false;
		}
		json = serverData.evalJSON();
		jQuery("#realestatelistAdmin .upload_preview").show().html('<label>Image:<br><b>'+json.width+"x"+json.height+'</b></label><img id="realestateimagename" style="max-height:100px;max-width:150px;" src="'+json.src+'" alt="'+json.width+"x"+json.height+'" />');
		$('realestateimagename').value = json.name

	},

	successPhoto : function(file, serverData, recievedResponse) {
		if(serverData == '0'){
			alert('Image is too large');
			return false;
		}
		json = serverData.evalJSON();
		img = new Element('img', {'src' : json.src, 'id' : 'photoimage_uploaded'});
		jQuery('.photoimage').append(img).show();
		$('photoname').value = json.name;
		$('photowidth').value = json.width;
		$('photoheight').value = json.height;

		//$('realestateimagename').value = json.name

	},

	error : function(obj, code,message) {
		alert(message)
	},
	start : function(obj) {
	},

	dialogComplete : function() {
		FM.AdminWidget.swfu.startUpload();
	},

	dialogCompleteBanner : function() {
		FM.AdminWidget.updateSwfSettings('banner', 60 , 60);
		FM.AdminWidget.swfuBanner.startUpload();
	},

	dialogCompleteTopBanner : function() {
		if(!FM.topBannerWidget.getSelected()) {
			alert('Please select a Banner Type')
			return false;
		}else {
			FM.AdminWidget.swfuTopBanner.addPostParam('orgId', $('orgId').value);
			FM.AdminWidget.swfuTopBanner.addPostParam('type', FM.topBannerWidget.getSelected());
			FM.AdminWidget.swfuTopBanner.startUpload();
		}
	},

	dialogCompleteProduct : function() {
		FM.AdminWidget.updateSwfSettings('product', 600 , 600);
		FM.AdminWidget.swfProduct.startUpload();
	},

	dialogCompleteListing: function() {
		FM.AdminWidget.updateSwfSettings('listing', 1500 , 1000);
		FM.AdminWidget.swfListing.startUpload();
	},

	dialogCompletePhoto: function() {
		FM.AdminWidget.updateSwfSettings('gallery', 600 , 600);
		FM.AdminWidget.swfPhoto.startUpload();
	}
}

//FM.Loaders.loaded();
FM.onDomReady(FM.AdminWidget.prepUploaders)


FM.ServicesEdit = {
	update : function(content, id) {
		pars = {
			orgId : id,
			content : content
		}
		vars = $H(pars).toQueryString();
		FM.doAjax('/admin/ajaxeditservices', vars, FM.ServicesEdit.updateCallback);
	},

	updateCallback : function(transport) {
		if(transport.responseText == '1'){
			FM.ajaxStatus('Services updated successfully! Reload page to view.', 'confirmation');
		}
	}
}

FM.MenuEdit = {
	update : function(content, id) {
		pars = {
			orgId : id,
			content : content
		}
		vars = $H(pars).toQueryString();
		//alert(vars);
		FM.doAjax('/admin/ajaxeditmenu', vars, FM.MenuEdit.updateCallback);
	},

	updateCallback : function(transport) {
		if(transport.responseText == '1') {
			FM.ajaxStatus('Menu updated successfully! Reload page to view.', 'confirmation');
		}
	}
}

FM.SportsScheduleEdit = {
	update : function(content, id) {
		pars = {
			orgId : id,
			schedule : content
		}
		vars = $H(pars).toQueryString();
		//alert(vars);
		FM.doAjax('/admin/ajaxeditsportsschedule', vars, FM.SportsScheduleEdit.updateCallback);
	},

	updateCallback : function(transport) {
		//alert(transport.responseText);
		if(transport.responseText == '1') {
			FM.ajaxStatus('Schedule updated successfully! Reload page to view.', 'confirmation');
		}
	}
}

FM.TextAd = {
	insert : function(content, oid) {
		pars = {
			orgId : oid,
			content : content,
			id : ($('textAdId').value != '') ? $('textAdId').value : 0
		}
		vars = $H(pars).toQueryString();
		//alert(vars);
		FM.doAjax('/admin/ajaxaddtextad', vars, FM.TextAd.insertCallback);
	},

	edit : function(id) {
		var pars = {
			'id' : id
		},
		vars = $H(pars).toQueryString();
		//alert(vars);
		FM.doAjax('/admin/ajaxedittextad', vars, FM.TextAd.editCallback);
	},

	editCallback : function(transport) {
		json = transport.responseText.evalJSON();
		tinyMCE.get('textad').setContent(json.content)
		$('textAdId').value = json.id;
		if($('textAdAdmin').style.display != 'block'){$('textAdAdmin').style.display = 'block'}
		//if($('textAdAdmin').style.display != 'inline'){$('textAdAdmin').style.display = 'inline';}
	},

	insertCallback : function(transport) {
		//alert(transport.responseText)
		if(transport.responseText != 0) {
			tr = new Element('tr', {'id' : 'manageads_' + transport.responseText, 'class':'new'});
			ct = new Element('td');
			ct.innerHTML = tinyMCE.get('textad').getContent();
			tr.insert({bottom : ct});
			active = new Element('td',{'align' : 'center'});
			active.innerHTML = '<input onclick="FM.TextAd.toggleActive(this, '+  transport.responseText + ')" name="" type="checkbox">';
			tr.insert({bottom : active});
			editTd = new Element('td',{'align' : 'center'});
			editTd.innerHTML = '<a onclick="FM.TextAd.edit('+  transport.responseText + ')" class="delete_ad_'+  transport.responseText + '"><img src="/images/icons/edit.png"></a>';
			tr.insert({bottom : editTd});
			deleteTd = new Element('td',{'align' : 'center'});
			deleteTd.innerHTML = '<a onclick="FM.TextAd.remove('+  transport.responseText + ')" class="delete_ad_'+  transport.responseText + '"><img src="/images/icons/remove.png"></a>';
			tr.insert({bottom : deleteTd});
			if($('manageads_' + transport.responseText)) {
				$('manageads_' + transport.responseText).replace(tr)
			} else {
				$('manageadheaders').insert({after : tr});
			}
			$('textad').value ='';
			tinyMCE.get('textad').setContent('');
			$('textAdId').value = '';

		}
	},

	clear : function() {
		$('textad').value ='';
		tinyMCE.get('textad').setContent('');
		$('textAdId').value = '';
	},

	toggleActive : function(el, mid) {
		pars = {
			active : (el.checked) ? '1' : '0',
			id : mid
		}
		vars = $H(pars).toQueryString();
		//alert(vars);
		FM.doAjax('/admin/ajaxtoggletextad', vars, FM.TextAd.toggleCallback);
	},

	toggleCallback : function(transport) {
		//alert(transport.responseText);
	},

	remove : function(mid) {
		pars = {
			id : mid
		}
		vars = $H(pars).toQueryString();
		FM.doAjax('/admin/ajaxremovetextad', vars, FM.TextAd.removeCallback);
	},

	removeCallback : function(transport) {
		if(transport.responseText != '0') {
			$('manageads_' + transport.responseText).remove();
		} else {
			alert('Action failed. Please refresh and try again')
		}
	}
}