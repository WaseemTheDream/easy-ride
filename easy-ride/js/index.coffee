rideSearcher = rideSearcher or {}
require [
    'components/map-route',
    'components/input/date-picker'
    ], (MapRoute, DatePicker) ->

    class RideSearcher
        constructor: ->
            # Google Maps Options
            @mapOptions =
                center: new google.maps.LatLng(51.517099, -0.146084)
                zoom: 12
                mapTypeId: google.maps.MapTypeId.ROADMAP

            # Initialize Google Maps
            @map = new google.maps.Map($('#map_canvas')[0], @mapOptions)

            # Member variables
            @womenOnly = $('#search-women-only')
            @departure = new DatePicker(
                $('#search-departure'),
                $('#search-departure-date'),
                true)

            @route = new MapRoute(
                $('#search-route'),
                @map,
                $('#search-from'),
                $('#search-to'))

            @searchButton = $('#search-button')
            
            @searchButton.click =>
                return null if @searchButton.hasClass('disabled')
                data = @toJson()
                return null if data == null
                console.log(data)

                @setButton('btn btn-primary disabled', 'Searching...')

                $.ajax
                    url: '/index_search.php'
                    type: 'GET'
                    data: 'data': JSON.stringify(data)
                    success: (data) =>
                        console.log(data)
                        error = 'Unknown Error!'
                        json = JSON.parse(data)
                        if json
                            if json['status'] == 'OK'
                                @processResults(json['trips'])
                                @setButton('btn btn-primary', 'Search')
                                return
                            else
                                error = json['msg']
                        @setButton('btn btn-danger', error)
                    error: (data) ->
                        @setButton('btn btn-danger', 'Error!')

            @tripTemplate = _.template($('#trip-template').html())
            @trips = $('#trips')
            
        setButton: (btnClass, msg) =>
            @searchButton.attr('class', btnClass)
            @searchButton.text(msg)

        toJson: =>
            json =
                # departure: @departure.getTime()
                women_only: @womenOnly.prop('checked')
                # route: @route.toJson()
            for key, value of json
                if value == null
                    return null
            return json

        processResults: (trips) =>
            @tripsList = trips
            i = 0
            for trip in trips
                i += 1
                console.log(trip)
                new RouteRenderer(@map, trip)
                tripHTML = @tripTemplate(trip)
                console.log(tripHTML)
                @trips.append(tripHTML)


    class RouteRenderer
        constructor: (map, route) ->
            markerOptions =
                visible: false
            polylineOptions = 
                strokeColor: "#808080"
                strokeOpacity: .6
                strokeWeight: 4
            mapRendererOptions =
                markerOptions: markerOptions
                # polylineOptions: polylineOptions

            directionsDisplay = new google.maps.DirectionsRenderer(mapRendererOptions)
            directionsDisplay.setMap(map)
            console.log(directionsDisplay)

            request =
                origin: route['origin']['address']
                destination: route['destination']['address']
                travelMode: google.maps.TravelMode.DRIVING
                region: 'uk'

            directionsService = new google.maps.DirectionsService()
            directionsService.route request, (result, status) =>
                if status == google.maps.DirectionsStatus.OK
                    console.log('Received directions')
                    directionsDisplay.setDirections(result)
                    console.log(result)

    rideSearcher = new RideSearcher()