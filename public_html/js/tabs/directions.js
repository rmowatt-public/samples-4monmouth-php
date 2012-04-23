FM.Directions = {
    
        directionDisplay : null,
        directionsService : new google.maps.DirectionsService(),
        options : {
            zoom:13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        },
        map : null,
        marker : null,
        
    init : function(){
        jQuery("#gmap form").unbind("submit").submit(FM.Directions.calcRoute);
        FM.Directions.getLatLng();
    },        
    calcRoute : function(){
        var end = jQuery("#direction_from").val(),
            request = {
                origin: end, 
                destination: FM.Directions.options.center,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            };
            
        FM.Directions.directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {              
            jQuery("#gmap").addClass("split");
            FM.Directions.resize();
            FM.Directions.directionDisplay.setDirections(response);
          }
        });
        
        return false;
    },
    getLatLng : function(){
        var address = jQuery("#gmap #direction_to").val().replace(/\s/g,"+");        
        jQuery.ajax({
            cache : false,
            dataType : "jsonp",
            type : "GET",
            url : "http://maps.google.com/maps/geo?q="+address+"&output=json&oe=utf8\&sensor=true&key=ABQIAAAAGB_uFceOlbhV2itP0e7nARRWVIMC9p-33I1aHqrAK1H05AI80xROZSKw2gG6-JWWdxopGCdG8dtFMw",
            success : FM.Directions.setAddress
        })
    },
    resize : function(){
      google.maps.event.trigger(FM.Directions.map, 'resize');
    },
    reset : function(){
      FM.Directions.directionDisplay.setMap(null);
      FM.Directions.marker.setMap(null);
      jQuery("#directionsPanel").empty();
	  jQuery("#colorbox_gmap").hide();
    },
    setAddress : function(data){
        jQuery("#gmap").removeClass("split");      
        var coord = data.Placemark[0].Point.coordinates;            
        FM.Directions.options.center = new google.maps.LatLng(coord[1] , coord[0]);
		//console.log(FM.Directions.options);
		jQuery("#colorbox_gmap").show();
        FM.Directions.map = new google.maps.Map(document.getElementById("map_canvas"), FM.Directions.options);
        FM.Directions.marker = new google.maps.Marker({
            position: FM.Directions.options.center,
            map: FM.Directions.map
        });
       FM.Directions.directionDisplay = new google.maps.DirectionsRenderer();
       FM.Directions.directionDisplay.setMap(FM.Directions.map);
       FM.Directions.directionDisplay.setPanel(document.getElementById("directionsPanel"));
    }
}