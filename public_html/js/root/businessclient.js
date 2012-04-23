
/**
*
*/
FM.BusinessClient = {

	addOption : function(id, name) {
		option = new Element('option', {'value' : id});
		option.innerHTML = name;
		$('userSelect').insert({top : option});
		$('userSelect').selectedIndex = 0;
		$('userSelect').value = id;
		jQuery.fn.colorbox.close();
	},

	toggleActive : function(el) {
		var ids = el.id.split('_');
		id = ids[1];
		pars = {
			id : ids[1],
			active : (el.checked) ? '1' : '0'
		}
		FM.doAjax('/root/ajaxclientactivate', $H(pars).toQueryString(),
		FM.BusinessClient.toggleActiveHandler);
	},

	toggleActiveHandler : function(transport) {
		var text = (transport.responseText == '1') ? 'Update Successful!'
		: 'Update Failed, Please Try Again';
		FM.ajaxStatus(text);
	},

	Edit : {
		populateEdit : function(e) {
			var id = e.target.id.split("edit_")[1],
			pars = {
				id : id,
				clienttype : 2
			}
			//alert($H(pars).toQueryString());
			FM.doAjax('/root/ajaxgetclient', $H(pars).toQueryString(),
			FM.BusinessClient.Edit.populateForm );
		},

		getSubcatsInit : function(val, el, org) {
			if(val) {
				pars = {
					id : val,
					orgId : org
				}
				FM.doAjax('/root/ajaxgetsubcats', $H(pars).toQueryString(),
				function(transport){FM.BusinessClient.Edit.populateSubcats(transport, el) });
			}
		},

		getSubcats : function(event) {
			//alert('sub')
			var element = Event.element(event);
			val = (element.selected) ? element.value : false;
			if(val) {
				pars = {
					id : val
				}
				FM.doAjax('/root/ajaxgetsubcats', $H(pars).toQueryString(),
				function(transport){FM.BusinessClient.Edit.populateSubcats(transport, element) });
			} else {
				$('category').descendants().each(
				function(el) {
					if(!el.selected && $('subs_' + el.value)) {
						$('subs_' + el.value).remove();
					}
				}
				)
			}
		},

		populateSubcats : function(transport, element) {
			var cats = transport.responseText.evalJSON();

			if(!$('subcategory-element')) {
				var div = new Element('div', {'id' : 'subcategory-wrapper'})
				var dd = new Element('dd', {'id' : 'subcategory-element'})
				var dt = new Element('dt', {
				'id' : 'subcategory-label'
				});
				dt.innerHTML = 'Sub Category';
				div.insert({bottom : dt});
				div.insert({bottom : dd});
				$('category-element').insert({after : div})
			}

			$('category').descendants().each(
			function(el) {
				if(!el.selected && $('subs_' + el.value)) {
					$('subs_' + el.value).remove();
				}
			}
			)

			if(cats.subs.size()) {
				if($('subs_' + element.value)){return false;}
				var div = new Element('div', {'id' : 'subs_' + element.value, 'class' : 'subcats'})
				var header = new Element('h5');
				header.innerHTML = element.innerHTML;
				cats.subs.each(
				function(el) {
					select = new Element('input', {'type' : 'checkbox', 'id' : 'sub_' + el.parent , 'class' : 'cbx', 'value' : el.id, 'label' : el.name, 'name' : 'subcat_' + el.id});
					if(el.selected){select.checked = true;}
					var label = new Element('label');
					label.innerHTML = el.name;
					var wrap = new Element('div', {'class' : 'subcatpair'});
					wrap.insert({bottom : select});
					wrap.insert({bottom : label});
					div.insert({bottom : wrap});
				}
				)
				clear = new Element('div', {'class' : 'clear'});
				clear.innerHTML = '&nbsp';
				div.insert({bottom : clear});
				div.insert({top : header});
				$('subcategory-element').insert({bottom : div})
			}

		},

		populateForm : function(transport) {

			client = transport.responseText.evalJSON();

			if($('bannerInfo')){$('bannerInfo').remove();}
			if($('logoInfo')){$('logoInfo').remove();}
			if($('iconInfo')){$('iconInfo').remove();}

			$('userSelect').value = client.admin;
			$('website').value = (client.website) ? client.website : '';
			$('name').value = FM.stripslashes(client.name) ;
			$('address1').value = client.address1 ;
			$('address2').value = client.address2 ;
			$('city').value = client.city ;
			$('state').value = client.state ;
			$('zip').value = client.zip ;
			$('phone').value = client.phone ;
			$('email').value = client.email ;
			$('maillist').value = client.maillist ;
			$('limeCard').value = client.limeCard ;
			$('slug').value = client.slug ;
			//$('descriptionr').value = client.description ;

			tinyMCE.getInstanceById("descriptionr").setContent(FM.stripslashes(client.description));
			tinyMCE.getInstanceById("keywords").setContent(FM.stripslashes(client.keywords.join(', ')));
			//$('category').value = client.category ;
			//categories = $A(client.categories);

			$('category').descendants().each(
			function(el) {
				Event.observe(el, 'click', FM.BusinessClient.Edit.getSubcats);
				if(client.categories.indexOf(el.value) != -1) {
					el.selected = true;
				}
			}
			)

			$('category').descendants().each(
			function(el) {
				if(el.selected && !$('subs_' + el.value)) {
					FM.BusinessClient.Edit.getSubcatsInit(el.value, el, client.id);
				}
			}
			)

			if(!$('subcategory-element')) {
				var div = new Element('div', {'id' : 'subcategory-wrapper'})
				var dd = new Element('dd', {'id' : 'subcategory-element'})
				var dt = new Element('dt', {
				'id' : 'subcategory-label'
				});
				dt.innerHTML = 'Sub Category';
				div.insert({bottom : dt});
				div.insert({bottom : dd});
				$('category-element').insert({after : div})
			}

			$('subcategory-element').descendants().each(function(el) {
				//if(el.value ){alert(el.value)}
				if(el.value && client.categories.indexOf(el.value) != -1) {
					el.selected = true;
				}
			}
			)


			$('town').descendants().each(
			function(el) {
				if(client.towns.indexOf(el.value) != -1) {
					el.selected = true;
				}
			}
			)
			//$('town').value = client.region ;
			//$('specialty').value = client.specialty ;
			$('keywords').value = client.keywords.join(' , ') ;
			$('orgId').value = client.id;
			el = new Element('div', {'id' : 'logoInfo'});
			if(client.logo.fileName) {
				el.innerHTML = '<a class="preview_orglogo" href="/media/image/logos/' + client.logo.fileName + '">Current Icon (click to view)</a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.logo.id + '\', \'logo\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				el.innerHTML = 'No current logo';
			}
			$('file').insert({after : el});

			icon = new Element('div', {'id' : 'iconInfo'});
			if(client.icon.fileName) {
				icon.innerHTML = '<a class="preview_orgicon" href="/media/image/icons/' + client.icon.fileName + '">Current Icon :  '+client.icon.width+'x'+client.icon.height+' (click to view)</a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.icon.id + '\', \'icon\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				icon.innerHTML = 'No current Icon';
			}$('icon').insert({after : icon});

			bnr = new Element('div', {'id' : 'bannerInfo'});
			if(client.miniwebBanner.fileName) {
				bnr.innerHTML = '<a class="preview_orgbanner" href="/media/image/logos/' + client.miniwebBanner.fileName + '">Current Banner (click to view)</a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.miniwebBanner.id + '\', \'banner\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				bnr.innerHTML = 'No current Banner';
			}$('banner').insert({after : bnr});

			$A(client.regions).each(function(el){
				if($('region_'+ el)) {
					$('region_'+ el).checked = true;
				}
			})

			jQuery("#fieldset-admingroup h4").html("Edit : "+client.name);
			FM.Client.toggleClient("addBizClient");
		}
	}
}

FM.NpClient = {
	Edit : {
		populateEdit : function(e) {
			var id = e.target.id.split("edit_")[1];
			pars = {
				id : id,
				clienttype : 3
			}
			FM.doAjax('/root/ajaxgetclient', $H(pars).toQueryString(),
			FM.NpClient.Edit.populateForm );
		},

		populateForm : function(transport) {
			//alert(transport.responseText);
			client = transport.responseText.evalJSON();

			if($('bannerInfo')){$('bannerInfo').remove();}
			if($('logoInfo')){$('logoInfo').remove();}
			if($('iconInfo')){$('iconInfo').remove();}

			$('userSelect').value = client.admin;
			$('website').value = (client.website) ? client.website : '';
			$('name').value = FM.stripslashes(client.name) ;
			$('address1').value = client.address1 ;
			$('address2').value = client.address2 ;
			$('city').value = client.city ;
			$('state').value = client.state ;
			$('zip').value = client.zip ;
			$('phone').value = client.phone ;
			$('email').value = client.email ;
			$('maillist').value = client.maillist ;
			$('slug').value = client.slug ;
			tinyMCE.getInstanceById("descriptionr").setContent(FM.stripslashes(client.description));
			tinyMCE.getInstanceById("keywords").setContent(FM.stripslashes(client.keywords.join(', ')));
			$('category').descendants().each(
			function(el) {
				if(client.categories.indexOf(el.value) != -1) {
					el.selected = true;
				}
			}
			)
			//$('region').value = client.region ;
			$('town').descendants().each(
			function(el) {
				if(client.towns.indexOf(el.value) != -1) {
					el.selected = true;
				}
			}
			)

			el = new Element('div', {'id' : 'logoInfo'});
			if(client.logo.fileName) {
				el.innerHTML = '<a class="preview_orglogo" href="/media/image/logos/' + client.logo.fileName + '">Current Icon (click to view)</a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.logo.id + '\', \'logo\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				el.innerHTML = 'No current logo';
			}
			$('file').insert({after : el});

			icon = new Element('div', {'id' : 'iconInfo'});
			if(client.icon.fileName) {
				icon.innerHTML = '<a class="preview_orgicon" href="/media/image/icons/' + client.icon.fileName + '">Current Icon :  '+client.icon.width+'x'+client.icon.height+' (click to view)</a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.icon.id + '\', \'icon\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				icon.innerHTML = 'No current Icon';
			}
			$('icon').insert({after : icon});

			bnr = new Element('div', {'id' : 'bannerInfo'});
			if(client.miniwebBanner.fileName) {
				bnr.innerHTML = '<a class="preview_orgbanner" href="/media/image/logos/' + client.miniwebBanner.fileName + '">Current Banner (click to view) </a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.miniwebBanner.id + '\', \'banner\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				bnr.innerHTML = 'No current Banner';
			}
			$('banner').insert({after : bnr});

			$A(client.regions).each(function(el){
				if($('region_'+ el)) {
					$('region_'+ el).checked = true;
				}
			})

			$('orgId').value = client.id;

			jQuery("#fieldset-admingroup h4").html("Edit : "+client.name);
			FM.Client.toggleClient("addNpClient");
		}
	}
}

FM.SportsClient =  {
	Edit : {
		populateEdit : function(e) {
			var id = e.target.id.split("edit_")[1];
			pars = {
				id : id,
				clienttype : 4
			}
			FM.doAjax('/root/ajaxgetclient', $H(pars).toQueryString(),
			FM.SportsClient.Edit.populateForm );
		},

		populateForm : function(transport) {
			//alert(transport.responseText);
			var client = transport.responseText.evalJSON();

			if($('bannerInfo')){$('bannerInfo').remove();}
			if($('logoInfo')){$('logoInfo').remove();}
			if($('iconInfo')){$('iconInfo').remove();}

			$('userSelect').value = client.admin;
			$('name').value = FM.stripslashes(client.name) ;
			$('website').value = (client.website) ? client.website : '';
			$('address1').value = client.address1 ;
			$('address2').value = client.address2 ;
			$('city').value = client.city ;
			$('state').value = client.state ;
			$('zip').value = client.zip ;
			$('phone').value = client.phone ;
			$('email').value = client.email ;
			$('maillist').value = client.maillist ;
			$('slug').value = client.slug ;
			$('protected').checked = (client.protected == 1);
			//$('descriptionr').value = client.description ;
			tinyMCE.getInstanceById("descriptionr").setContent(FM.stripslashes(client.description));
			$('category').value = client.category ;
			//$('region').value = client.region ;
			$('town').descendants().each(
			function(el) {
				if(client.towns.indexOf(el.value) != -1) {
					el.selected = true;
				}
			}
			)

			el = new Element('div', {'id' : 'logoInfo'});
			if(client.logo.fileName) {
				el.innerHTML = '<a class="preview_orglogo" href="/media/image/logos/' + client.logo.fileName + '">Current Icon (click to view)</a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.logo.id + '\', \'logo\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				el.innerHTML = 'No current logo';
			}$('file').insert({after : el});

			icon = new Element('div', {'id' : 'iconInfo'});
			if(client.icon.fileName) {
				icon.innerHTML = '<a class="preview_orgicon" href="/media/image/icons/' + client.icon.fileName + '">Current Icon : '+client.icon.width+'x'+client.icon.height+' (click to view)</a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.icon.id + '\', \'icon\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				icon.innerHTML = 'No current Icon';
			}
			$('icon').insert({after : icon});

			bnr = new Element('div', {'id' : 'bannerInfo'});
			if(client.miniwebBanner.fileName) {

				bnr.innerHTML = '<a class="preview_orgbanner" href="/media/image/logos/' + client.miniwebBanner.fileName + '">Current Banner (click to view)</a>&nbsp;or <span style="cursor:pointer;margin-top:.3em;" onclick="FM.Client.deleteMedia(\'' + client.miniwebBanner.id + '\', \'banner\')"><img src="/images/icons/remove.png">Delete</span>';
			} else {
				bnr.innerHTML = 'No current Banner';
			}
			$('banner').insert({after : bnr});

			$A(client.regions).each(function(el){
				if($('region_'+ el)) {
					$('region_'+ el).checked = true;
				}
			})

			$('orgId').value = client.id;

			jQuery("#fieldset-admingroup h4").html("Edit : "+client.name);
			FM.Client.toggleClient("addSportClient");
		}
	}
}

FM.Client = (function(){

	var bindEvents = function(){
		jQuery(".client_nav li").click(toggleNav);
		jQuery(".client:not('#addSportClients', '#addNpClients') #category option").live("click", FM.BusinessClient.Edit.getSubcats);
		jQuery("#bizClients td.edit img").live("click", FM.BusinessClient.Edit.populateEdit);
		jQuery("#npClients td.edit img").live("click", FM.NpClient.Edit.populateEdit);
		jQuery("#sportClients td.edit img").live("click", FM.SportsClient.Edit.populateEdit);
		jQuery(".clientLists td.delete img").live("click", confirmDelete);
		jQuery(".pages a").live("click",FM.showPage);
		jQuery(".edit_faq").live("click", FM.Faq.Root.populateedit);
		jQuery(".delete_faq").live("click", FM.Faq.Root.deletefaq);
		jQuery('.clear_org').live("click", clearForm);
		jQuery('.clear_faq').live("click", FM.Faq.Root.clearForm);
	},
	bindColorBox = function(){
		jQuery(".create_member").colorbox({
			innerWidth:"350px",
			innerHeight : "500px",
			iframe : true,
			opacity : .4
		});
		jQuery('.preview_orgbanner, .preview_orgicon, .preview_orglogo').live('click', function(){
			jQuery.fn.colorbox({
				href : this.href,
				open : true,
				opacity : .4
			});
			return false;
		});
		jQuery(".preview_util, .preview_coupon, .preview_banner").colorbox({
			href : function(){
				return jQuery(this).attr("title");
			},
			opacity : .4
		});
		jQuery(".preview_util").colorbox({
			href : function(){
				return jQuery(this).attr("title");
			},
			opacity : .4
		});
	},
	
	clearForm = function(e) {
		if(confirm('Are you sure you want to cancel any changes and start a new form? ')){
			$('upload').reset();
			$('orgId').value = 0;
			if($('bannerInfo')){$('bannerInfo').remove();}
			if($('logoInfo')){$('logoInfo').remove();}
			if($('iconInfo')){$('iconInfo').remove();}
			if($('subcategory-label')){$('subcategory-label').remove();}
			if($('subcategory-element')){$('subcategory-element').remove();}
			if($('protected')){$('protected').checked = false;}
			jQuery("#fieldset-admingroup h4").html("");
		};
		return false;
	},
	confirmDelete = function(e){
		var el = e.target;
		if(confirm('Are you sure you want to delete '+el.title+'?')){
			//alert('?delete=' + e.target.id);
			id = e.target.id.split('_');
			window.location = '?delete=' + id[1];
		};
		return false;
	},

	checkSlug = function(e) {
		if(e.value.strip() == ''){return false;}
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
			slug : e.value.strip()
		}
		FM.doAjax('/root/ajaxcheckslug', $H(pars).toQueryString(),
		function(transport) {
			//alert(transport.responseText);
			if(transport.responseText == 1) {
				alert('this link is already in use, please choose another');
				$('slug').clear();
				return false;
			}
		}

		);
	},

	deleteMedia = function(xid, type) {
		//alert(xfilename + ' = ' + type);
		if(confirm('Are you sure you want to delete this ' + type)) {
			pars = {
				id : xid,
				mediatype : type,
				clienttype : 4,
				orgId : $('orgId').value
			}
			//alert($H(pars).toQueryString()); return false;
			FM.doAjax('/root/ajaxdeletemedia', $H(pars).toQueryString(),
			function(transport){FM.Client.deleteMediaCallback(transport, type);} );
		}
	},


	deleteMediaCallback = function(transport, type) {
		if(transport.responseText == '1'){
			$(type + 'Info').innerHTML = 'No current Icon';
		}
	},
	
	toggleNav = function(e){
		var id = (e.target) ? e.target.className : e.className;
		jQuery(".client_nav li").removeClass("active").end().find("."+id).addClass("active");

		switch (id){
			case 'bizClient':
			case 'npClient':
			case 'sportClient':
			FM.Client.clearForm();
			break;
			case 'addBizClient':
			break;
			default :
			break;
		}
		toggleClient(id);
	},
	toggleClient = function(client){
		var client = client.split(" active")[0];
		jQuery(".client").removeClass("active").end().find("#"+client+"s").addClass("active");
		return false;
	}
	viewOrgBanner = function(e) {
		console.log(e.target);
		path = '/media/image/logos/' + img;
		Shadowbox.open({content:path, img:"img"});
	};

	jQuery(function(){
		bindEvents();
		bindColorBox();
	});

	return {
		toggleNav : toggleNav,
		toggleClient : toggleClient ,
		checkSlug : checkSlug,
		clearForm : clearForm,
		confirmDelete : confirmDelete,
		deleteMedia : deleteMedia,
		deleteMediaCallback : deleteMediaCallback
	}
})();
