define ['components/user-interface',
         'components/input/text-input'], (UserInterface, TextInput) ->
    ###
        A Google Maps based Route module for finding a origin and destination
        information with trip length.

        Requires: Google Maps API V3
    ###
    class MapRoute extends UserInterface
        constructor: (@container, @from, @to, tripLength) ->
            super(@container)

            new google.maps.places.SearchBox(@from[0])
            new google.maps.places.SearchBox(@to[0])

            @tripLength = new TextInput(
                tripLength.parent().parent(),
                tripLength)
            @result

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
            @from.change(@calculateRoute)
            @to.change(@calculateRoute)

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
                    @result = result
                    @removeError()
                    @updateRoute()
                else
                    @result = null
                    @setError('No routes found.')

        ###
            Updates the form on the page to reflect the results of a route.
        ###
        updateRoute: =>
            route = @result['routes'][0]
            leg = route['legs'][0]
            @from.val(leg['start_address'])
            @to.val(leg['end_address'])
            @tripLength.setValue(leg['duration']['text'])

        ###
            Returns the route in JSON form if it exists.
        ###
        toJson: =>
            if not @result
                @setError('No route specified.')
                return null

            route = @result['routes'][0]
            leg = route['legs'][0]
            console.log(leg['start_location'])

            from =
                address: leg['start_address']
                lat: leg['start_location']['jb']
                lon: leg['start_location']['ib']

            to =
                address: leg['end_address']
                lat: leg['end_location']['jb']
                lon: leg['end_location']['ib']

            length = @tripLength.getValue()
            if not length
                return null

            json =
                from: from
                to: to
                trip_length: length

            @removeError()
            return json