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

            @message = $('#share-message')
            @womenOnly = $('#share-women-only')
            @spots = new NumberInput(
                $('#share-spots').parent().parent(),
                $('#share-spots'),
                true)
            @shareButton = $('#share-button')


            @shareButton.click =>
                if @shareButton.hasClass('disabled')
                    return

                data = @toJson()
                return if data == null
                console.log(data)

                $.ajax
                    url: '/share_post.php'
                    type: 'POST'
                    data: 'data': JSON.stringify(data)
                    success: (data) =>
                        console.log(data)
                        error = 'Unknown Error!'
                        json = JSON.parse(data)
                        if json
                            if json['status'] == 'OK'
                                @setButton('btn btn-success', json['msg'])
                                return
                            else
                                error = json['msg']
                        @setButton('btn btn-danger', error)
                    error: (data) =>
                        @setButton('btn btn-danger', 'Error!')
            
        setButton: (btnClass, msg) =>
            @shareButton.attr('class', btnClass)
            @shareButton.text(msg)


        toJson: =>
            json =
                departure: @departure.getDateTime()
                route: @route.toJson()
                message: @message.val()
                women_only: @womenOnly.prop('checked')
                spots: @spots.getValue()
            for key, value of json
                if value == null
                    return null
            return json

    class UserInterface
        constructor: (@container) ->

        ###
            Set error.
            Args:
                msg {String}: error message string.
        ###
        setError: (msg) =>
            @removeError()
            @container.addClass('error')
            error = $(
                '<div>',
                    class: 'controls help-inline error-msg'
                ).append(msg)
            @container.append(error)

        ###
            Remove error.
        ###
        removeError: =>
            @container.removeClass('error')
            @container.children().filter('.error-msg').remove()

    ###
        A Google Maps based Route module for finding a origin and destination
        information with trip length.
    ###
    class MapRoute extends UserInterface
        constructor: (@container, @from, @to, tripLength) ->
            super(@container)
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

            from =
                address: leg['start_address']
                lat: leg['start_location']['hb']
                lon: leg['start_location']['ib']

            to =
                address: leg['end_address']
                lat: leg['end_location']['hb']
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

    ###
        A DateTime module that uses a Bootstrap DatePicker and TimePicker and
        combines the input.
    ###
    class DateTime extends UserInterface
        constructor: (@container, date, time) ->
            super(@container)
            @date = date.datepicker().data('datepicker')
            @time = time.timepicker()

        ###
            Returns departure time as an integer value, if entered
        ###
        getDateTime: =>
            dateString = @date.element.children().filter('input').val()
            timeString = @time.val()
            if not dateString or not timeString
                @setError('Missing departure information.')
                return null

            time = @parseTime(timeString)
            date = @date.date.valueOf() / 1000
            @removeError()
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

    class TextInput extends UserInterface
        constructor: (@container, @input, @required=false) ->
            super(@container)

        getValue: =>
            inputString = @input.val().trim()
            if @required and not inputString
                @setError('Required field.')
                return null
            @removeError()
            return inputString

        setValue: (val) =>
            @input.val(val)
            @removeError()

    class NumberInput extends TextInput
        getValue: =>
            inputString = @input.val().trim()
            if @required and not inputString
                @setError('Required field.')
                return null

            min = parseInt(@input.attr('min'))
            max = parseInt(@input.attr('max'))
            val = parseInt(@input.val())
            if not (min <= val and val <= max)
                @setError('Not within valid range.')
                return null

            return val

    new RideSharer()