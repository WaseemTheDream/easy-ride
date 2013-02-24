departure = null
picker = null
jQuery ->

    class RideSharer
        constructor: ->
            departure = @departure = new DateTime(
                $('#share-departure-date'),
                $('#share-departure-time'))

            @route = new MapRoute(
                $('#share-from'),
                $('#share-to'),
                $('#share-trip-length'))

    ###
        A Google Maps based Route module for finding a origin and destination
        information with trip length.
    ###
    class MapRoute
        constructor: (fromField, toField, tripLengthField) ->
            @from = fromField
            @to = toField
            @tripLength = tripLengthField
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
                    @updateRoute()
                else
                    @result = null
                    console.log('No routes found')

        ###
            Updates the form on the page to reflect the results of a route.
        ###
        updateRoute: =>
            route = @result['routes'][0]
            leg = route['legs'][0]
            @from.val(leg['start_address'])
            @to.val(leg['end_address'])
            @tripLength.val(leg['duration']['text'])

    ###
        A DateTime module that uses a Bootstrap DatePicker and TimePicker and
        combines the input.
    ###
    class DateTime
        constructor: (dateField, timeField) ->
            picker = @date = dateField.datepicker().data('datepicker')
            @time = timeField.timepicker()

        ###
            Returns departure time as an integer value, if entered
        ###
        getDateTime: =>
            dateString = @date.element.children().filter('input').val()
            timeString = @time.val()
            if not dateString or not timeString
                console.log('Missing departure information')
                return null

            time = @parseTime(timeString)
            date = @date.date.valueOf() / 1000
            console.log(time)
            console.log(date)
            return date + time

        parseTime: (string) =>
            strings = string.split(' ')
            time = strings[0].split(':')
            meridiem = strings[1]
            hours = parseInt(time[0])
            minutes = parseInt(time[1])
            if meridiem == 'PM'
                hours += 12
            return hours * 3600 + minutes * 60


    new RideSharer()