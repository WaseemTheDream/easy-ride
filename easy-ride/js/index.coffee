rideSearcher = rideSearcher or {}
require [
    'components/map-route',
    'components/input/date-picker',
    'components/input/text-input'
    ], (MapRoute, DatePicker, TextInput) ->

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
                false)

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
                    url: '/index_ajax.php'
                    type: 'GET'
                    data: 'data': JSON.stringify(data)
                    success: @searchResults
                    complete: (xhr, status) =>
                        if status != 'success'
                            @setButton('btn btn-danger', "#{xhr.statusText}")

            @trips = $('#trips')

            @requestModal = new RequestRideModal()

        searchResults: (data) =>
            console.log(data)
            error = 'Unknown Error!'
            json = JSON.parse(data)
            if json
                if json['status'] == 'OK'
                    @clearTrips()
                    if json['trips'].length == 0
                        @setButton('btn btn-primary', 'No trips found')
                    else
                        @processResults(json['trips'])
                        @setButton('btn btn-primary', 'Search')
                    return
                else
                    error = json['msg']
            @setButton('btn btn-danger', error)

        clearTrips: =>
            @trips.html('')
            
        setButton: (btnClass, msg) =>
            @searchButton.attr('class', btnClass)
            @searchButton.html("<i class='icon icon-white icon-search'></i> #{msg}")

        toJson: =>
            json =
                departure: @departure.getTime()
                women_only: @womenOnly.prop('checked')
                route: @route.toJson()
            if json['route'] == null or json['women_only'] == null
                return null
            return json

        processResults: (tripsData) =>
            @trips.hide()
            @tripsTable = {}
            for tripData in tripsData
                id = tripData.id
                @tripsTable.id = new Trip(@trips, @map, @requestModal, tripData)
            @trips.slideDown(1000)


    class Trip
        constructor: (@container, @map, @modal, @data) ->
            @tripTemplate = _.template($('#trip-template').html())
            @data.departure_string = (new Date(parseInt(@data.departure_time) * 1000)).toLocaleString()
            @container.append(@tripTemplate(@data))
            @routeRenderer = new RouteRenderer(@map, @data)
            $("#trip-#{@data.id}").hover(@routeRenderer.hoverIn, @routeRenderer.hoverOut)

            @requestButton = $("#request-trip-#{@data.id}")
            @requestButton.click(@requestRide)
            @updateStatus()

        setButton: (btnClass, text) =>
            @requestButton.attr('class', "btn btn-small #{btnClass}")
            @requestButton.html("<i class='icon icon-envelope icon-white'></i> #{text}")

        requestRide: (e) =>
            return if @requestButton.hasClass('disabled')
            if $('#logged-in').length == 0      # If not logged in
                return @setButton('btn-danger', 'Login Required!')

            @modal.reset()
            @modal.load(@)
            @modal.show()

        updateStatus: =>
            if @data.status == null
                @setButton('btn-primary', 'Request Ride')
            else if @data.status == 'PENDING'
                @setButton('btn-info disabled', 'Request Pending')
            else if @data.status == 'DECLINED'
                @setButton('btn-danger disabled', 'Request Declined')
            else if @data.status == 'APPROVED'
                @setButton('btn-success disabled', 'Request Approved')


    class RouteRenderer
        constructor: (@map, @route) ->
            @mapRendererOptions =
                markerOptions:
                    visible: false
                polylineOptions:
                    strokeOpacity: 0.0
                    strokeWeight: 4
            @directionsDisplay = new google.maps.DirectionsRenderer(@mapRendererOptions)
            @directionsDisplay.setMap(@map)

            request =
                origin: @route['origin']['address']
                destination: @route['destination']['address']
                travelMode: google.maps.TravelMode.DRIVING
                region: 'uk'

            @directionsService = new google.maps.DirectionsService()
            @directionsService.route request, (result, status) =>
                if status == google.maps.DirectionsStatus.OK
                    @directionsDisplay.setDirections(result)

        hoverIn: (e) =>
            @mapRendererOptions.polylineOptions.strokeOpacity = 0.8
            @directionsDisplay.setMap(@map)

        hoverOut: (e) =>
            @mapRendererOptions.polylineOptions.strokeOpacity = 0.0
            @directionsDisplay.setMap(@map)

    class RequestRideModal
        constructor: ->
            @el = $('#modal-request-ride')
            @info = $('#modal-trip-info')
            @requestMessage = new TextInput(
                $('#modal-trip-request-message').parent().parent(),
                $('#modal-trip-request-message'))
            @submitButton = $('#modal-request-ride-submit')
            @submitButton.click(@submit)
            @tripTemplate = _.template($('#trip-modal-template').html())
        
        load: (@trip) =>
            @info.append(@tripTemplate(@trip.data))

        show: =>
            @el.modal('show')

        hide: =>
            @el.modal('hide')

        reset: =>
            @info.html('')
            @setButton('btn btn-primary', 'Request')

        toJson: =>
            'message': @requestMessage.getValue()
            'trip_id': @trip.data.id

        submit: (e) =>
            data = @toJson()
            $.ajax
                url: '/index_ajax.php'
                type: 'POST'
                data: 'data': JSON.stringify(data)
                success: (data) =>
                    console.log(data)
                    json = JSON.parse(data)

                    if not json
                        @setButton('btn btn-danger', 'Unknown error!')
                        return

                    if json['status'] == 'OK'
                        @hide()
                        @reset()
                        @trip.data.status = 'PENDING'
                        @trip.updateStatus()
                    else 
                        @setButton('btn btn-danger', json['msg'])

                error: (data) ->
                    @setButton('btn btn-danger', 'Unknown error!')

        setButton: (btnClass, msg) =>
            @submitButton.attr('class', btnClass)
            @submitButton.html("<i class='icon icon-white icon-search'></i> #{msg}")


    rideSearcher = new RideSearcher()