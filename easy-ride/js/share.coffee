jQuery ->

    class RideSearcher
        constructor: ->
            # Member variables
            @from = new google.maps.places.SearchBox($('#search-from')[0])
            @to = new google.maps.places.SearchBox($('#search-to')[0])
            @date = $('#search-departure-date').datepicker()
            @departureTime = $('#search-departure-time').timepicker()
            @arrivalTime = $('#search-arrival-time').timepicker()

            # Google Maps Options
            mapOptions =
                center: new google.maps.LatLng(51.517099, -0.146084)
                zoom: 8
                mapTypeId: google.maps.MapTypeId.ROADMAP

            # Initialize Google Maps
            map = new google.maps.Map($('#map_canvas')[0], mapOptions)
            markers = []

            # Add from/to event listeners
            google.maps.event.addListener @from, 'from_changed', =>
                places = @from.getPlaces()

                for marker in markers
                    market.setMap(null)

                # markers = []
                # bounds = new google.maps.LatLngBounds()
                # for place in places
                #     image =
                #         url: place.icon
                #         size: new google.maps.Size(71, 71)
                #         origin: 

            

        updateFrom: ->
            query = @from.val().trim()
            if not query
                return




    new RideSearcher()