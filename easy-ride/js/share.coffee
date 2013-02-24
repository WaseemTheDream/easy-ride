jQuery ->

    class RideSearcher
        constructor: ->
            # Member variables
            @from = new google.maps.places.SearchBox($('#share-from')[0])
            @to = new google.maps.places.SearchBox($('#share-to')[0])
            @date = $('#share-departure-date').datepicker()
            @departureTime = $('#share-departure-time').timepicker()
            @arrivalTime = $('#share-arrival-time').timepicker()

            # Google Maps Options
            @mapOptions =
                center: new google.maps.LatLng(51.517099, -0.146084)
                zoom: 12
                mapTypeId: google.maps.MapTypeId.ROADMAP

            # Initialize Google Maps
            @map = new google.maps.Map($('#map_canvas')[0], @mapOptions)
            @mapMarkers = []

            # Bind the search box bounds to the map's view
            @from.bindTo('bounds', @map)

            # Add from/to event listeners
            google.maps.event.addListener @from, 'places_changed', =>
                console.log('Places changed!')
                places = @from.getPlaces()

                for marker in @mapMarkers
                    marker.setMap(null)

                @mapMarkers = []
                bounds = new google.maps.LatLngBounds()
                for place in places
                    image =
                        url: place.icon
                        size: new google.maps.Size(71, 71)
                        origin: new google.maps.Point(0, 0)
                        anchor: new google.maps.Point(17, 34)
                        scaledSize: new google.maps.Size(25, 25)

                    marker = new google.maps.Marker 
                        map: @map
                        icon: image
                        title: place.name
                        position: place.geometry.location

                    @mapMarkers.push(marker)

                    bounds.extend(place.geometry.location)

                @map.fitBounds(bounds)

            

        updateFrom: ->
            query = @from.val().trim()
            if not query
                return




    new RideSearcher()