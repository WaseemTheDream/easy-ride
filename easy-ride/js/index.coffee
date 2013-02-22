jQuery ->

	console.log('Initializing maps.')

	# Google Maps Options
	mapOptions =
		center: new google.maps.LatLng(51.517099, -0.146084)
		zoom: 8
		mapTypeId: google.maps.MapTypeId.ROADMAP

	# Initialize Google Maps
	map = new google.maps.Map($('#map_canvas')[0], mapOptions)
	return
