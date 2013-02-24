jQuery ->

    class RideSharer
        constructor: ->
            # Member variables
            @from = $('#share-from')
            @to = $('#share-to')
            @date = $('#share-departure-date').datepicker()
            @departureTime = $('#share-departure-time').timepicker()
            @arrivalTime = $('#share-arrival-time').timepicker()

            # Google Maps
            @directionsDisplay = new google.maps.DirectionsRenderer()
            @directionsService = new google.maps.DirectionsService()

            # Google Maps Options
            @mapOptions =
                center: new google.maps.LatLng(51.517099, -0.146084)
                zoom: 12
                mapTypeId: google.maps.MapTypeId.ROADMAP

            # Initialize Google Maps
            @map = new google.maps.Map($('#map_canvas')[0], @mapOptions)
            @mapMarkers = []

            @directionsDisplay.setMap(@map)

            # Add event binders for calculating route
            $('#share-from, #share-to').change(@calculateRoute)            

        ###
            Uses Google Maps Directions API to calculate the route for the data
            entered by the user.
        ###
        calculateRoute: =>
            from = @from.val().trim()
            to = @to.val().trim()

            if not from or not to
                return

            request = 
                origin: from
                destination: to
                travelMode: google.maps.TravelMode.DRIVING
                region: 'uk'

            @directionsService.route request, (result, status) =>
                if status == google.maps.DirectionsStatus.OK
                    @directionsDisplay.setDirections(result)
                    @updateRoute(result)
                else
                    console.log('No routes found')

        ###
            Updates the form on the page to reflect the results of a route.
        ###
        updateRoute: (result) =>
            route = result['routes'][0]
            console.log(route)
            @from.val(route['legs'][0]['start_address'])
            @to.val(route['legs'][0]['end_address'])

        updateFrom: ->
            query = @from.val().trim()
            if not query
                return




    new RideSharer()