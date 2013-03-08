jQuery ->

    class RideSearcher
        constructor: ->
            # Member variables
            @departureDate = $('#search-departure-date').datepicker()
            @womenOnly = $('#search-women-only')

            @departure = new Date(
                $('#search-departure'),
                $('#search-departure-date'))

            @route = new MapRoute(
                $('#search-route')
                $('#search-from'),
                $('#search-to'))
            
            @searchButton.click =>
                if @searchButton.hasClass('disabled')
                    returSn
                return if data == null
                console.log(data)

                $.ajax
                    url: ''
                    type: 'GET'
                    data: 'data': JSON.stringify(data)
                    from: @from.val()
                    to: @to.va()
                    success: (data) =>
                        @setButton('disabled btn btn-success', 'Search conducted!')
                    error: (data) ->
                        @setButton('disabled btn btn-danger', 'Error!')
            
        setButton: (btnClass, msg) =>
            @searchButton.attr('class', btnClass)
            @searchButton.text(msg)

        toJson: =>
            json =
                departure: @departure.getDate()
                women_only: @womenOnly.prop('checked')
                from: @from.val()
                to: @to.va()
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
        constructor: (@container, @from, @to) ->
            super(@container)
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

            @removeError()
            return json

    ###
        A DateTime module that uses a Bootstrap DatePicker 
    ###
    class Date extends UserInterface
        constructor: (@container, date) ->
            super(@container)
            @date = date.datepicker().data('datepicker')

        ###
            Returns departure time as an integer value, if entered
        ###
        getDate: =>
            dateString = @date.element.children().filter('input').val()
            if not dateString
                @setError('Missing departure information.')
                return null

            date = @date.date.valueOf() / 1000
            @removeError()
            return date 

        ###
            Parses the time and returns the integer value in seconds.
            Args:
                string {String}: time string e.g. '04:50 PM'
        ###

    class RequiredInput extends UserInterface
        constructor: (@container, @input) ->
            super(@container)

        getValue: =>
            inputString = @input.val().trim()
            if not inputString
                @setError('Required field.')
                return null
            else
                @removeError()
                return inputString

        setValue: (val) =>
            @input.val(val)
            @removeError()



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