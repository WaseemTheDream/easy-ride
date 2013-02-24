jQuery ->

    class RideSharer
        constructor: ->
            @departure = new DateTime(
                $('#share-departure'),
                $('#share-departure-date'),
                $('#share-departure-time'))

            @route = new MapRoute(
                $('#share-route')
                $('#share-from'),
                $('#share-to'),
                $('#share-trip-length'))

    ###
        A Google Maps based Route module for finding a origin and destination
        information with trip length.
    ###
    class MapRoute
        constructor: (@container, @from, @to, @tripLength) ->
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
            @tripLength.val(leg['duration']['text'])

        ###
            Set routes error.
            Args:
                msg {String}: error message string.
        ###
        setError: (msg) =>
            console.log(msg)
            @removeError()
            @container.addClass('error')
            error = $(
                '<div>',
                    class: 'controls help-inline error-msg'
                ).append(msg)
            console.log(error)
            @container.append(error)

        ###
            Removes routes error.
        ###
        removeError: =>
            @container.removeClass('error')
            @container.children().filter('.error-msg').remove()

    ###
        A DateTime module that uses a Bootstrap DatePicker and TimePicker and
        combines the input.
    ###
    class DateTime
        constructor: (@container, date, time) ->
            @date = date.datepicker().data('datepicker')
            @time = time.timepicker()

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

        ###
            Parses the time and returns the integer value in seconds.
            Args:
                string {String}: time string e.g. '04:50 PM'
        ###
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