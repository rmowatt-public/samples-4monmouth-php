FM.BannerWidget = {

	selected : 0,

	uploaded : false,

	requireImage : true,

	imageWidth : 0,

	imageHeight : 0,

	create : function() {
		//alert($A($('managebannertable').getElementsByTagName('tr')).size());
		if(($A($('managebannertable').getElementsByTagName('tr')).size() > 15) && $('bannerMediaId').value == '') {
			alert('There is a limit of 15 banners. You must delete at least 1 before you may create a new banner.')
			return false;
		}

		if(this.selected == 0){
			alert('Please select a banner template before submission!');
			return false;
		}
		if($('bannername').value == ''){
			alert('Please complete the NAME field before submission!');
			return false;
		}
		if($('banneralt').value == ''){
			alert('Please complete the ALT field before submission!');
			return false;
		}
		if($('bannerurl').value == ''){
			alert('Please complete the URL field before submission!');
			return false;
		}
		if($('bannercopy').value == ''){
			alert('Please complete the COPY field before submission!');
			return false;
		}

		var pars = {
			oid : $('orgId').value,
			type : this.selected.value,
			name : $('bannername').value,
			alt : $('banneralt').value,
			url : $('bannerurl').value,
			copy : $('bannercopy').value,
			id : $('bannerMediaId').value
		}

		if(!FM.BannerWidget.uploaded && FM.BannerWidget.requireImage!=false){
			alert('please designate image');
			return false;
		}

		if(!FM.BannerWidget.requireImage || this.selected.value == 20){
			pars.headline = $('bannerheadline').value;
		}
		if(FM.BannerWidget.requireImage){
			img = $A($('bannerlogoimg').src.split('/'));
			pars.medianame = img[img.size() -1];
		}

		var vars = $H(pars).toQueryString();


		FM.doAjax('/utilities/ajaxaddbanner', vars, FM.BannerWidget.createCallback);
	},

	createCallback : function(transport) {

		//alert(transport.responseText)
		var json = transport.responseText.evalJSON();

		if(json.id) {
			this.selected = 0,
			tr = new Element('tr', {'id' : 'managebanners_' + json.id, 'class':'new'});
			id = new Element('td')
			id.innerHTML = json.id;
			tr.insert({bottom : id});

			mname = new Element('td')
			mname.innerHTML = $('bannername').value;
			tr.insert({bottom : mname});

			active = new Element('td',{'align' : 'center'});
			checked = (json.active == 1) ? ' CHECKED ' : '';
			active.innerHTML = '<input type="checkbox" onclick = "FM.BannerWidget.toggleActive(this,' + json.id + ')" name="" '+ checked +'  />';
			tr.insert({bottom : active});

			view = new Element('td',{'align' : 'center'});
			view.innerHTML = '<a href="/bannerpreview/' + json.id + '" class="manager_banner" title="' + json.name + '"><img src="/images/icons/preview.png"></a>';
			tr.insert({bottom : view});


			editTd = new Element('td',{'align' : 'center'});
			editTd.innerHTML = '<a onclick = "FM.BannerWidget.edit(' + json.id + ')"  title="' + json.name + '"><img src="/images/icons/edit.png" /></a>';
			tr.insert({bottom : editTd});

			deleteTd = new Element('td',{'align' : 'center'});
			deleteTd.innerHTML = '<a class="delete_banner_'+json.id+'" href="#"><img src="/images/icons/remove.png"></a>';
			tr.insert({bottom : deleteTd});
			if(!$('managebanners_' + json.id)) {
				$('managebannerheaders').insert({after : tr});
			} else {
				$('managebanners_' + json.id).replace(tr);
			}

			FM.BannerWidget.clear();
			FM.ajaxStatus('Your Banner has been created and you can view it under the "manage Banners" tab.' , 'admin');
			return false;
		} else {
			FM.ajaxStatus('Banner Submission failed. Please refresh page and try again' , 'admin');
			return false;
		}
	},

	clear : function() {
		$('bannername').value = '';
		$('banneralt').value = '';
		$('bannerurl').value = '';
		$('bannercopy').value = '';
		$('bannerheadline').value = '';
		$('bannerMediaId').value = '';
		$('bannerTemplates').descendants().each(
		function(e) {
			if(e.value){
				e.checked = false;
				FM.BannerWidget.selected = 0;
			}
		}
		)

		FM.BannerWidget.uploaded = false;
		FM.BannerWidget.init();
		FM.BannerWidget.uploaded = false;
		FM.BannerWidget.imageWidth = 0;
		FM.BannerWidget.imageHeight = 0;
		jQuery("#createBanners .upload_preview").hide().html('');
		jQuery("#createBanners ").addClass("noimage");
	},

	clientDelete : function(id) {
		if(confirm('Are you sure you want to delete custom banner ' + id)) {
			pars = {
				orgId : $('orgId').value,
				bannerId : id
			}
			vars = $H(pars).toQueryString();
			FM.doAjax('/utilities/ajaxdeletebanner', vars, FM.BannerWidget.clientDeleteCallback);
		}
		else{
			return false;
		}
	},

	clientDeleteCallback : function(transport) {
		if(transport.responseText != '0') {
			$('managebanners_'+transport.responseText).remove();
			FM.ajaxStatus('Banner has been deleted' , 'admin');
		} else {
			FM.ajaxStatus('Banner Deletion failed. Please refresh page and try again' , 'admin');
		}
	},


	edit : function(bid){
		var pars = {
			oid : $('orgId').value,
			id : bid
		}
		vars = $H(pars).toQueryString();
		FM.doAjax('/utilities/ajaxgetbannerinfo', vars, FM.BannerWidget.editCallback);


	},


	editCallback : function(transport) {
		if($('createBanners').style.display != 'block'){$('createBanners').style.display = 'block'}
		var json = transport.responseText.evalJSON();
		$('bannername').value = json.name;
		$('banneralt').value = json.alt;
		$('bannerurl').value = json.url;
		$('bannercopy').value = json.copy;
		$('bannerheadline').value = json.headline;
		$('bannerMediaId').value = json.id;
		if(FM.BannerWidget.setSelected(null, json.type)) {
			if(json.medianame != '') {
				jQuery("#createBanners .upload_preview").show().html('<label>Image:<br><b>'+json.template.width+"x"+json.template.height+'</b></label><img id="bannerlogoimg" style="width:'+json.template.width +'px;height:'+json.template.height +'px" src="'+json.path + '/' + json.medianame + '" height="'+json.template.height +'" width ="'+json.template.width +'" alt="'+json.template.width + "x"+ json.template.height +'" />');
			}
		}
		if(json.template.logo == 1){FM.BannerWidget.uploaded = true;}
		//jQuery("#createBanners .upload_preview").show().html('<label>Image:<br><b>'+json.template.width+"x"+json.template.height+'</b></label><img id="bannerlogoimg" src="'+json.path + '/' + json.medianame + '" alt="'+json.template.width + "x"+ json.template.height +'" />');
		FM.BannerWidget.setSelected(null, json.type);
	},



	setSelected : function(el, id) {
		val = (id) ? id : el.value;
		pars = {
			bannerId : val
		}
		if(el && el.value != "19") {

		}
		vars = $H(pars).toQueryString();
		FM.doAjax('/ajaxgetbannerconfig', vars, FM.BannerWidget.configCallback);
		if(el){this.selected = el}
		else {
			$('bannerTemplates').descendants().each(
			function(e) {
				if(e.value && e.value == id){
					FM.BannerWidget.setSelected(e);
					e.checked = true;
				}
			}
			)}
			return true;
	},

	configCallback : function(transport) {
		var json = transport.responseText.evalJSON();

		//alert("headline : " + json.headline);

		jQuery('#createBanners #bannerHeadlineWrap').toggle(json.headline != '0');
		jQuery("#createBanners ").toggleClass("noimage", (json.logo == '0'));
		FM.BannerWidget.requireImage = (json.logo != '0');

		if(json.logo != '0'){
			jQuery('#createBanners .upload_btn b').html(json.width + " x " +  json.height);
			FM.BannerWidget.imageWidth = json.width;
			FM.BannerWidget.imageHeight = json.height;
			if($('bannerlogoimg')) {
				$('bannerlogoimg').style.width = json.width;
				$('bannerlogoimg').style.height = json.height;
			}
			FM.AdminWidget.updateSwfSettings('banner', json.height, json.width);
			jQuery("#createBanners .upload_preview").show();
		}
		else {
			jQuery("#createBanners .upload_preview").hide().html('');
			//jQuery("#createBanners .upload_preview").hide();
		}

	},

	viewBanner : function(path) {
		path = '/bannerpreview/' + path;
		Shadowbox.open({content:path, player:"iframe", height:300, width:300});
	},

	viewPayBanner : function(path, width, height) {
		path = '/media/image/paybanners/' + path;
		Shadowbox.open({content:path, player:"iframe", height:50, width:514});
	},

	viewFullBanner : function(path, width, height) {
		path = '/media/image/fullbanners/' + path;
		Shadowbox.open({content:path, player:"iframe", height:50, width:514, overlayColor : "#FF0000"});
	},

	toggleActive : function(el, mid) {
		pars = {
			id : mid,
			active : (el.checked) ? '1' : '0'
		}
		vars = $H(pars).toQueryString();
		FM.doAjax('/utilities/ajaxtogglebanner', vars, FM.CouponWidget.toggleActiveCallback);
	},

	toggleActiveCallback : function(transport) {
		if(transport.responseText == 1) {
			FM.ajaxStatus('Success' , 'admin');
		}
	},

	bindBannerEvents : function(){
		var path = {
			banner : {
				source : '/bannerpreview/',
				height : "150px",
				width : "300px"
			},
			paybanner : {
				source : '/media/image/paybanners/',
				height : "75px",
				width : "530px"
			},
			fullbanner : {
				source : '/media/image/fullbanners/',
				height : "150px",
				width : "960px"
			}
		}

		jQuery(".manager_banner").colorbox({
			innerWidth:"300px",
			innerHeight : "150px",
			iframe : true,
			opacity : .4
		});
		jQuery("img[id^='preview_banner']").colorbox({
			href : function(){
				return (path[jQuery(this).attr("class").split(" ")[0]].source + jQuery(this).attr("id").split("preview_banner_")[1]);
			},
			innerWidth: function(){
				return path[jQuery(this).attr("class").split(" ")[0]].width;
			},
			innerHeight : function(){
				return path[jQuery(this).attr("class").split(" ")[0]].height;
			},
			iframe : true,
			opacity : .4
		});
		jQuery("a[class^='delete_banner']").unbind("click").click(function(){
			var id = jQuery(this).attr("class").split("delete_banner_")[1];
			FM.BannerWidget.clientDelete(id);
			return false;
		})
	},
	init : function(){
		this.bindBannerEvents();
	}
}

FM.topBannerWidget = {
	selected : null,

	setSelected : function(s)	{
		FM.topBannerWidget.selected = s;
	},

	getSelected : function() {
		return FM.topBannerWidget.selected;
	},

	resetSelected : function() {
		FM.topBannerWidget.selected = null;
		$('topbannerleft').checked = false;
		$('topbannerright').checked  = false;
		$('icon').checked  = false;
	},

	update : function() {

	},

	updateIcon : function(transport) {
		var json = transport.evalJSON();
		$('currenticon').src = json.src;
		FM.ajaxStatus('icon updated', 'confirmation');
		FM.topBannerWidget.resetSelected();
	}
}