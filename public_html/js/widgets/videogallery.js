FM.VideoGallery = {
	
	addVideo :function() {
		
		if($('videoVideo').value != '') {
			
			pars = {
				orgId : $('orgId').value,
				video : $('videoVideo').value,
				description : $('videoDescription').value,
				videoAlbum : $('videoAlbumselect').value
			}
			
			vars = $H(pars).toQueryString();
			
			FM.doAjax('/utilities/ajaxaddvideoinfo', vars, function(transport) {FM.VideoGallery.addVideoCallback(transport, $('videoVideo').value)});
		} else {
			FM.ajaxStatus("Unable to add media", 'statusMessage');
		}
	},

	addAlbum :function() {
		if($('videoAlbumname').value != '') {
			pars = {
				orgId : $('orgId').value,
				albumName : $('videoAlbumname').value,
				description : $('videoAlbumdesc').value
			}

			vars = $H(pars).toQueryString();
			alert(vars)
			FM.doAjax('/utilities/ajaxaddvideoalbum', vars, function(transport) {FM.VideoGallery.addVideoAlbumCallback(transport, $('videoAlbumname').value)});
		} else {
			FM.ajaxStatus("Unable to add media", 'statusMessage');
		}
	},

	removeAlbum : function(id, albumName) {
		if(confirm('Would you really like to delete ' + albumName + '? This will delete all associated images!')) {
			pars = {
				orgId : $('orgId').value,
				id : id
			}

			vars = $H(pars).toQueryString();
			FM.doAjax('/utilities/ajaxdeletevideoalbum', vars, function(transport){FM.VideoGallery.deleteVideoAlbumCallback(transport, id)});

		} else {
			alert('This album will not be deleted')
		}
	},
	
	removeVideo : function(id) {
		if(confirm('Would you really like to delete this video?')) {
			pars = {
				orgId : $('orgId').value,
				id : id
			}

			vars = $H(pars).toQueryString();
			FM.doAjax('/utilities/ajaxdeletevideo', vars, function(transport){FM.VideoGallery.deleteVideoCallback(transport)});

		} else {
			alert('This album will not be deleted')
		}
	},

	deleteVideoAlbumCallback : function(transport, id) {
		alert(transport.responseText);
		if(json = transport.responseText.evalJSON()) {
			//alert(transport.responseText)
			$('videoalbumscroll_' + id).descendants().each(function(el){
				el.remove();
			})
			$('videoalbumscroll_' + id).remove();
			$('videotoggle_' + id).remove();
			FM.VideoGallery.toggleOn(0);
		}
	},
	
	deleteVideoCallback : function(transport) {
		alert(transport.responseText);
		if(json = transport.responseText.evalJSON()) {
			//alert(transport.responseText)
			$('videoThumbWrapper_' + json.id).remove();
		}
	},

	addVideoCallback : function(transport, imgname) {
		if(json = transport.responseText.evalJSON()) {
			wrapperDiv = new Element('div', {'style' : 'margin:0px auto;', 'id': 'videoThumbWrapper_' + json.id });
			deleteDiv = new Element('div');
			deleteDiv.innerHTML = '<span style="float:right"><img src="/images/icons/remove.png" onclick="FM.VideoGallery.removeVideo(\'' + json.id + '\')"/></span>';
    		div = new Element('div');
			div.innerHTML = json.description;
			vid = '<img src = "http://img.youtube.com/vi/' + json.video + '/default.jpg" onclick="showVideo(\'' + json.video + '\')" />'
			album = (json.videoAlbum == 0) ? 'default' : json.videoAlbum;
			
			
			wrapperDiv.insert(deleteDiv, {position:'top'});
			wrapperDiv.insert(div, {position:'top'});
			wrapperDiv.insert(vid, {position:'top'});
			$('videoalbum_' + album).insert(wrapperDiv, {position:'top'});
			FM.VideoGallery.toggleOn(json.videoAlbum);
			$('videoVideo').clear();
			$('videoDescription').clear();
		} else {}
	},

	addVideoAlbumCallback : function(transport, imgname) {           		
		alert(transport.responseText);
		if(json = transport.responseText.evalJSON()) {
			$('videoAlbumdesc').clear();
			$('videoAlbumname').clear();
			div ='<div onclick="FM.VideoGallery.toggleOn(' + json.id  + ')" class="videotoggle" id="videotoggle_' + json.id  + '">' + json.name + '</div>'
			$('videoToggleWrapper').insert(div, {position : 'top'});
			
			toggleDiv = new Element('div', {'style' : 'width:150px;display:none', 'id' : 'videoalbumscroll_' + json.id});
			wrapDiv = new Element('div', {'style' : 'width:150px;display:none', 'id' : 'videoalbum_' + json.id});
			descDiv = new Element('div', {'class' : 'albumhead'});
			descDiv.innerHTML = json.description;
			
			wrapDiv.insert(descDiv, {position : 'top'})
			toggleDiv.insert(wrapDiv, {position : 'top'})
			$('videoScrollers').insert(toggleDiv, {position : 'top'})
			
			FM.ajaxStatus("Album created. Please refresh to add photos.", 'statusMessage');
		} else {}
	},



	toggleOn : function(name) {
		$('videoScrollers').childElements().each(
			function(el) {
				if(el.id == 'videoalbumscroll_' + name) {
					el.style.display = 'inline'
				} else {
					el.style.display = 'none'
				}
			})
	}
}