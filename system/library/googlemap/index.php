<!doctype html>
<html>

<head>	
</head>

<body>

	<?php $lan = 'en'; ?>
	<?php $address = '1 Main St. Fort Lee, NJ'; ?>
    <?php $name = 'Won Song';?>
    <?php $info = '<div class=\"wc_info_window\"><strong>Won Song Home</strong>	<p>37 South Piermont Ave. <br/> Piermont, NY 10968 <br/> (201) 739 - 8833 <br/> <a href=\"http://wonsong.com\" target=\"_blank\">www.wonsong.com</a></p></div>'; ?>
    
    <div id="wc_map">
    <form id="wc_directions_form">    
		<label id="wc_directions_from_label" for="wc_directions_from"></label>
		<input id="wc_directions_from" name="wc_directions_from" type="text" val="" />
        <input id="wc_directions_submit" type="submit" value="" />
	</form>
    <div id="wc_toolbar">
    	<label for="wc_traffic" id="wc_traffic_label"></label>
        <input type="checkbox" id="wc_traffic" name="wc_traffic" /> 
    	<a id="wc_print" href="" target="_blank"><img src="http://hosting.gmodules.com/ig/gadgets/file/114281111391296844949/print-icon.png"></a>
        <a id="wc_print_text" href="" target="_blank"></a> 
        <a id="wc_larger" href="" target="_blank"><img src="http://hosting.gmodules.com/ig/gadgets/file/114281111391296844949/map-icon.png"></a>
        <a id="wc_larger_text" href="" target="_blank"></a>     
    </div><!--#wc_toolbar-->
    <div id="wc_map_canvas"></div>
    <div id="wc_type_toolbar">
    	<a class="wc_type" href="" target="_blank"><img src="http://maps.gstatic.com/intl/en_us/mapfiles/transit/iw/3/drive.gif"></a>
        <a class="wc_type" href="" target="_blank"></a>
        <a class="wc_type" href="b" target="_blank"><img src="http://maps.gstatic.com/intl/en_us/mapfiles/transit/iw/3/cycle.gif"></a>
        <a class="wc_type" href="b" target="_blank"></a>
        <a class="wc_type" href="w" target="_blank"><img src="http://maps.gstatic.com/intl/en_us/mapfiles/transit/iw/3/walk.gif"></a>
        <a class="wc_type" href="w" target="_blank"></a>
        <a class="wc_transit" href="" target="_blank"><img src="http://maps.gstatic.com/intl/en_us/mapfiles/transit/iw/3/tram.gif"></a>
        <a class="wc_transit" href="" target="_blank"></a>
    </div>
    <div id="wc_directions_panel"></div>    
    </div>
    
	<style>
		#wc_map {font-family:arial; font-size:13px;}
		#wc_map img {border:0}
		#wc_map #wc_directions_form {text-align:right;}
		#wc_map #wc_directions_from_label {width:10%;}
		#wc_map #wc_directions_from {width:80%;}	
		#wc_map #wc_toolbar {padding:3px; margin:3px 0 0 0; background:#D5DDF3;text-align:right;height:20px; border-top-left-radius:3px; border-top-right-radius:3px;}	
		#wc_map #wc_toolbar a, #wc_map #wc_toolbar label {line-height:20px;}
		#wc_map #wc_toolbar img, #wc_traffic {position:relative; top:3px;}
		#wc_map #wc_map_canvas {width:100%; height:400px;}
		#wc_map #wc_directions_panel {width:100%;}
		#wc_map #wc_type_toolbar {background:#e8ecf9; margin:10px 0 0; border:1px solid #d5ddf3; border-radius:4px;}
		.gray {color:#CCC;}
		
				
	</style>
            
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">				
		var msg = new Object();
		var map;
		var directionsDisplay;
		var directionsService;
		var marker;
		var infoWindow;
		var trafficLayer;
		var startLatLng;
		var latLng;
		var lan = "<?=$lan?>";
		var start = "";
		var address = "<?=$address?>";		
		var info = "<?=$info?>";
		var origPrint = '';
		var origLarger = '';
		
		google.load("jquery","1.6.2");
		google.load("maps","3.x", {other_params: "sensor=false&language="+lan});
		google.setOnLoadCallback (readLocale);
		
		
		// read locale first
		function readLocale() {
			$.ajax({
				type: "GET" , 
				url: "locale/"+lan+".xml" , 
				dataType: "xml",
				success: getMsg,
				cache:false
			});
		}
		
		// set up the locale to msg
		function getMsg(xml) {
			$(xml).find("msg").each(function() {				
				msg[$(this).attr("name")] = $(this).text();
			});
			populateFields();
		}
		
		// populate the fields according to locale
		function populateFields() {
			$("#wc_directions_from_label").text(msg.from);
			$("#wc_directions_submit").val(msg.go);
			$("#wc_traffic_label").text(msg.traffic);
			$("#wc_print_text").text(msg.print);
			$("#wc_larger_text").text(msg.larger);				
			getMap();		
		}
		
		// getmap
		function getMap() {
			// get geocode for lat, lng first
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({
				address : address
			} , 
			// when done
			function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					
					latLng = results[0].geometry.location;
					
					// make a map
					map = new google.maps.Map(
						$("#wc_map_canvas").get(0), {
						center: latLng,
						zoom: 16,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					});
					
					// set up a marker
					marker = new google.maps.Marker({
						position: latLng,
						map: map,
						title: "Open for more Info",
						animation: google.maps.Animation.BOUNCE
						
					});					
					
					// setup info
					infoWindow = new google.maps.InfoWindow({
						content: info
					});				
					google.maps.event.addListener(marker, "click", function(){
						infoWindow.open(map, marker);
					});
					
					// setup traffic
					trafficLayer = new google.maps.TrafficLayer();	
					$("#wc_traffic").change(function(event){
						if ($(this).attr("checked") == "checked" )
							trafficLayer.setMap(map);
						else
							trafficLayer.setMap(null);						
					});
					
					// print anchor					
					origPrint = "http://maps.google.com/maps?f=q&source=s_q&hl="+lan+"&q="+ address.replace(" ", "+") + "@" + latLng.lat() + "," + latLng.lng() + "&ie=UTF8&z=16&pw=2";
					$("#wc_print").attr("href",origPrint);
					$("#wc_print_text").attr("href",origPrint);
					
					// larger anchor
					origLarger = "http://maps.google.com/maps?f=q&source=s_q&hl="+lan+"&q="+ address.replace(" ", "+") + "@" + latLng.lat() + "," + latLng.lng() + "&ie=UTF8&z=16";
					$("#wc_larger").attr("href",origLarger);
					$("#wc_larger_text").attr("href",origLarger);
					
					// when done, setup the direction panel
					initDirections();				
				}				
			});			
		}
		
		function initDirections() {
			// set up the directions input field and form actions
			$("#wc_directions_from").val(msg.example_from_address);
			$("#wc_directions_from").addClass("gray");
			$("#wc_directions_from").focus(function(){				
				if ($.trim($(this).val()) == $.trim(msg.example_from_address))
					$(this).val("").removeClass("gray");
			});
			$("#wc_directions_from").blur(function(){
				if ($.trim($(this).val()) == "")
					$(this).val(msg.example_from_address).addClass("gray");
			});
			
			$("#wc_directions_form").submit(function(){
				$("#wc_directions_from").blur();
				getDirections();
				return false;
			});
			
			directionsService = new google.maps.DirectionsService();
			directionsDisplay = new google.maps.DirectionsRenderer();	
			directionsDisplay.setMap(map);	
			directionsDisplay.setPanel($("#wc_directions_panel").get(0));			
		}
		
		
		// create the direction panel and service
		function getDirections(by){			
			start = $.trim($("#wc_directions_from").val());
			start = (start == msg.example_from_address) ? "" : start;
			if (start == "")
				return false;
			
			// if start is good to go!!
			by = (!by) ? "" : by;
			
			var mode;
			if (by == "") mode = google.maps.TravelMode.DRIVING;
			else if (by == "w") mode = google.maps.TravelMode.WALKING;
			else if (by == "b") mode = google.maps.TravelMode.BICYCLING;			
			else mode = google.maps.TravelMode.DRIVING;
			
			
			directionsService.route({
				origin : start,
				destination: address,
				travelMode : mode				
			}, 
			function(response, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					// display the directions
					$("#wc_directions_panel").text("");
					directionsDisplay.setDirections(response);
					
					// get the toolbar links
					var geocoder = new google.maps.Geocoder();
					geocoder.geocode({
						address : start
					} , 
					// when done
					function (results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							startLatLng = results[0].geometry.location;
							
							// get tool bar links
							var printLink = "http://maps.google.com/maps?hl="+lan+"&saddr=" + start.replace(" ", "+") + "@" + startLatLng.lat() + "," + startLatLng.lng() + "&daddr="+ address.replace(" ", "+") + "@" + latLng.lat() + "," + latLng.lng() +"&dirflag="+by+"&ie=UTF8&layer=c&pw=2&z=16";
							
							var largerLink = "http://maps.google.com/maps?hl="+lan+"&saddr=" + start.replace(" ", "+") + "@" + startLatLng.lat() + "," + startLatLng.lng() + "&daddr="+ address.replace(" ", "+") + "@" + latLng.lat() + "," + latLng.lng() +"&dirflag="+by+"&ie=UTF8&layer=c&z=16"
							
							$("#wc_print").attr("href",printLink);
							$("#wc_print_text").attr("href",printLink);
							$("#wc_larger").attr("href",largerLink);
							$("#wc_larger_text").attr("href",largerLink);
							
							var transLink = "http://maps.google.com/maps?hl="+lan+"&saddr=" + start.replace(" ", "+") + "@" + startLatLng.lat() + "," + startLatLng.lng() + "&daddr="+ address.replace(" ", "+") + "@" + latLng.lat() + "," + latLng.lng() +"&dirflag=r&ie=UTF8&layer=c&z=16"
							// get bottom tool bars	
							$(".wc_transit").attr("href", transLink);
							
							
						}
					});
									
											
				}
				else {
					// if wrong, display erorr msg and put the links back to original map links
					$("#wc_directions_panel").text(msg.error);
					$("#wc_print").attr("href",origPrint);
					$("#wc_print_text").attr("href",origPrint);
					$("#wc_larger").attr("href",origLarger);
					$("#wc_larger_text").attr("href",origLarger);
				}				
			});			
		}
		
		
	</script>
    
    
</body>




</html>




