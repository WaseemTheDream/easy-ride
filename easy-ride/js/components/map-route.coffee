define ['components/user-interface',
         'components/input/text-input'], (UserInterface, TextInput) ->
    ###
        A Google Maps based Route module for finding a origin and destination
        information with trip length.

        Requires: Google Maps API V3
    ###
    class MapRoute extends UserInterface
        constructor: (@container, @map, @from, @to, tripLength=null) ->
            super(@container)

            new google.maps.places.SearchBox(@from[0])
            new google.maps.places.SearchBox(@to[0])

            if tripLength
                @tripLength = new TextInput(
                    tripLength.parent().parent(),
                    tripLength)
            @result

            # Google Maps
            polylineOptions = 
                strokeColor: "#808080"
                strokeOpacity: .9
                strokeWeight: 4
            mapRendererOptions =
                polylineOptions: polylineOptions

            @directionsDisplay = new google.maps.DirectionsRenderer()
            @directionsService = new google.maps.DirectionsService()
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
            if @tripLength
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
            console.log(leg)
            origin =
                address: leg['start_address']
                lat: leg['start_location'].lat()
                lon: leg['start_location'].lng()

            destination =
                address: leg['end_address']
                lat: leg['end_location'].lat()
                lon: leg['end_location'].lng()

            json =
                origin: origin
                destination: destination

            if @tripLength
                length = @tripLength.getValue()
                if not length
                    return null
                else
                    json['trip_length'] = length

            @removeError()
            return json