FM.PhotoGallery = {

	numberOfImagesToDisplay : 10,
	currentImage : 1,
	totalImages : 0,
	images : $A([]),

	init : function(data) {
		this.images = data;
		this.totalImages = this.images.size();
	},

	focus : function(imgEl) {
		if($('largeGalleryImage')) {
			$('largeGalleryImage').src = imgEl.src;
		}
	},

	fullSize : function(imgEl) {
		Shadowbox.open({content:imgEl.src, player:"img"});
	},

	up : function() {
		if((this.currentImage + (this.numberOfImagesToDisplay - 1 )) < this.totalImages) {
			next = this.images[this.currentImage + (this.numberOfImagesToDisplay - 1)];
			imgs = $A($('slide').getElementsByTagName('span'));
			imgs[0].remove();
			newImg = new Element('img', {src : '/media/image/photogallery/' + next.imageName, height : '50px', width : '50px'});
			newSpan = new Element('span', {'style' : 'padding-left:4px;'});
			newSpan.insert(newImg, {position:'top'});
			imgs[this.numberOfImagesToDisplay - 1].insert(newSpan, {position : 'after'})
			Event.observe(newSpan, 'click', function(event){FM.PhotoGallery.focus(newImg)})
			this.currentImage = this.currentImage + 1;
		}
	},

	down : function() {
		if(this.currentImage > 1) {
			next = this.images[this.currentImage - 2];
			imgs = $A($('slide').getElementsByTagName('span'));
			imgs[this.numberOfImagesToDisplay - 1].remove();
			newImg = new Element('img', {src : '/media/image/photogallery/' + next.imageName, height : '50px', width : '50px'});
			newSpan = new Element('span', {'style' : 'padding-left:4px;'});
			newSpan.insert(newImg, {position:'top'});
			//imgs[0].insert(newSpan, {position : 'before'})
			if($('moveLeft')){$('moveLeft').insert({after:newSpan})}
			else {$('slide').insert({top : newSpan})}
			Event.observe(newSpan, 'click', function(event){FM.PhotoGallery.focus(newImg)})
			//alert(newImg.src)
			this.currentImage = this.currentImage - 1;
		}
	},

	addPhoto :function() {
		if($('photoname').value != '' && $('photowidth').value != '' && $('photoheight').value != '') {
			if($('photowidth').value > 800 ||  $('photoheight').value > 600) {
				FM.ajaxStatus("Media is too large. Please resize to no larger than 800 x 600 px.", 'statusMessage');
				return false;
			}
			pars = {
				orgId : $('orgId').value,
				imageName : $('photoname').value,
				tagline : $('tagline').value,
				height : $('photoheight').value,
				width : $('photowidth').value,
				photoAlbum : $('albumselect').value
			}

			vars = $H(pars).toQueryString();
			FM.doAjax('/utilities/ajaxaddphotoinfo', vars, function(transport) {FM.PhotoGallery.addPhotoCallback(transport, $('photoname').value)});
		} else {
			FM.ajaxStatus("Unable to add media", 'statusMessage');
		}
	},

	addAlbum :function() {
		if($('albumname').value != '') {
			pars = {
				orgId : $('orgId').value,
				albumName : $('albumname').value,
				description : $('albumdesc').value
			}

			vars = $H(pars).toQueryString();
			FM.doAjax('/utilities/ajaxaddphotoalbum', vars, function(transport) {FM.PhotoGallery.addPhotoAlbumCallback(transport, $('albumname').value)});
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
			FM.doAjax('/utilities/ajaxdeletephotoalbum', vars, function(transport){FM.PhotoGallery.deletePhotoAlbumCallback(transport, id)});

		} else {
			alert('This album will not be deleted')
		}
	},

	deletePhotoAlbumCallback : function(transport, id) {
		if(json = transport.responseText.evalJSON()) {
			//alert(transport.responseText)
			$('photoalbum_' + id).remove();
			FM.Media.Images = json;
			FM.Media.PhotoGallery.reset();
			FM.Media.PhotoGallery.init();
		}
	},

	addPhotoCallback : function(transport, imgname) {
		if(json = transport.responseText.evalJSON()) {
			FM.Media.Images = json;
			FM.Media.PhotoGallery.reset(FM.Media.Images);
			FM.Media.PhotoGallery.init();
			$('photoname').value = '';
			$('tagline').value = '';
			$('photoheight').value = '';
			$('photowidth').value = '';
			jQuery('.photoimage').hide().find("img").remove();

		} else {}
	},

	addPhotoAlbumCallback : function(transport, imgname) {
		if(transport.responseText != '0') {
			FM.ajaxStatus("Album created. Please refresh to add photos.", 'statusMessage');
			//ul = new Element('ul', {'id' : 'photogallery_carousel', 'class' : 'photogallery_carousel_' + transport.responseText})
			//$('photogallery_carousel_wrapper').insert({top : ul});
			//FM.Media.PhotoGallery.reset();
			//FM.Media.PhotoGallery.init();
		} else {}
	},

	reset : function(json) {
		if(json){FM.Media.Images = json}
		FM.Media.PhotoGallery.reset();
		FM.Media.PhotoGallery.init();
	},

	toggleAlbumOn : function(el) {
		$('photoalbum_default').style.display = (el.checked) ? 'none' : 'inline';
		pars = {
			orgId : $('orgId').value,
			showPhotoAlbum : (el.checked) ? '0' : '1'
		}

		vars = $H(pars).toQueryString();
		FM.doAjax('/utilities/ajaxusealbum', vars, function(transport){});
	}
}