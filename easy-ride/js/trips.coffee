drivesController = drivesController or {}
template = null
e = null
jQuery ->

    class DrivesController
        constructor: ->
            @status = $('#trips-driving-status')
            @statusMsg = $('#trips-driving-msg')
            @statusLoader = $('#trips-driving-loader')
            @drives = $('#trips-driving')
            @rideRequestModal = new RideRequestModal($('#modal-ride-requests'))
            setTimeout(@ajax, 1000)

        ajax: =>
            $.ajax
                url: '/trips_ajax.php'
                type: 'GET'
                data:
                    'method': 'get_upcoming_drives'
                    'data': JSON.stringify({})
                success: @load
                complete: (xhr, status) =>
                    if status != 'success'
                        @statusMsg.html(
                            "<em>#{xhr.status}: #{xhr.statusText}</em>")
                        @statusLoader.fadeOut(500, => @statusMsg.fadeIn(500))

        load: (json) =>
            data = JSON.parse(json)
            console.log(data)
            drives = data.drives
            if drives.length == 0
                @statusLoader.fadeOut(500, => @statusMsg.fadeIn(500))
                return
            @drives.fadeOut 500, => 
                @status.hide()
                @loadDrives(drives)

        loadDrives: (drivesData) =>
            for driveData in drivesData
                console.log(drive)
                drive = new Drive(driveData)
                @drives.append(drive.render())
            @drives.fadeIn(500)
            $('.dropdown-toggle').dropdown()
            $('.button-ride-requests').click (e) =>
                console.log('Clicked!')
                tripId = $(e.target).data('trip-id')
                @rideRequestModal.reset()
                @rideRequestModal.show()
                setTimeout(( => @rideRequestModal.load(tripId)), 1000)
                


    class Drive
        constructor: (@data) ->
            @data.departure_string = (new Date(parseInt(@data.departure_time) * 1000)).toLocaleString()
            @template = _.template($('#trip-row-template').html())

        render: ->
            template = @template(@data)
            return @template(@data)

    class RideRequestModal
        constructor: (@el) ->
            @status = $('#modal-ride-requests-status')
            @loader = $('#modal-ride-requests-loader')
            @msg = $('#modal-ride-requests-msg')
            @spotsRemaining = 0
            @spotsRemainingContainer = $('#modal-ride-requests-spots-remaining')
            @spotsRemainingDisplay = $('#modal-ride-requests-spots-remaining-value')
            @form = $('#modal-ride-requests-form')

        show: => @el.modal('show')
        hide: => @el.modal('hide')
        reset: =>
            @loader.show()
            @status.show()
            @msg.hide()
            @form.html('').hide()
            @spotsRemainingContainer.hide()

        load: (@tripId) =>
            @updateSpotsRemaining()
            $.ajax
                url: '/trips_ajax.php'
                type: 'GET'
                data:
                    'method': 'get_requests_for_trip'
                    'data': JSON.stringify({'trip_id': @tripId})
                success: @success
                complete: (xhr, status) =>
                    if status != 'success'
                        @msg.html(
                            "<em>#{xhr.status}: #{xhr.statusText}</em>")
                        @loader.fadeOut(500, => @msg.fadeIn(500))

        success: (json) =>
            data = JSON.parse(json)
            if data.requests.length == 0
                @msg.text('You have no ride requests for this trip.')
                @loader.fadeOut(500, => @msg.fadeIn(500))
                return
            console.log(data)
            for requestData in data.requests
                rideRequest = new RideRequest(@, requestData)
                @form.append(rideRequest.render())
                rideRequest.initialize()
            @form.slideDown(500)
            @loader.fadeOut(500)

        updateSpotsRemaining: =>
            $.ajax
                url: '/trips_ajax.php'
                type: 'GET'
                data:
                    'method': 'get_spots_remaining_for_trip'
                    'data': JSON.stringify({'trip_id': @tripId})
                success: (json) =>
                    data = JSON.parse(json)
                    console.log(data)
                    @spotsRemainingDisplay.text(data.spots_remaining)
                    @spotsRemaining = data.spots_remaining
                    @spotsRemainingContainer.fadeIn(500)
                    $("tr#trip-#{@tripId} td.riders span").text(data.spots_taken)
                    # TODO: Update riders
                complete: (xhr, status) =>
                    if status != 'success'
                        console.log("<em>#{xhr.status}: #{xhr.statusText}</em>")

    class RideRequest
        constructor: (@modal, @data) ->
            @template = _.template($('#rider-request-template').html())

        render: =>
            @el = @template(@data)
            return @el

        initialize: () =>
            console.log(@data)
            console.log("#rider-#{@data.rider.id}-actions")
            $("#rider-#{@data.rider.id}-actions").children().click(@performAction)

        setActiveAction: (actionName) =>
            for action in ['DECLINED', 'PENDING', 'APPROVED']
                $("#rider-#{@data.rider.id}-actions button[data-action='#{action}']").removeClass('active')
            $("#rider-#{@data.rider.id}-actions button[data-action='#{actionName}']").addClass('active')

        performAction: (e) =>
            button = $(e.target)
            data =
                'status': button.data('action')
                'user_id': @data.rider.id
                'trip_id': @data.trip_id
            $.ajax
                url: '/trips_ajax.php'
                type: 'POST'
                data:
                    'method': 'update_ride_request_status'
                    'data': JSON.stringify(data)
                success: (json) =>
                    console.log(json)
                    response = JSON.parse(json)
                    if response.status != 'OK'
                        return
                    @setActiveAction(button.data('action'))
                    @modal.updateSpotsRemaining()
                complete: (xhr, status) =>
                    if status != 'success'
                        console.log("<em>#{xhr.status}: #{xhr.statusText}</em>")


    class RidesController
        constructor: ->
            @status = $('#trips-riding-status')
            @msg = $('#trips-riding-msg')
            @loader = $('#trips-riding-loader')
            @rides = $('#trips-riding')
            setTimeout(@ajax, 1000)

        ajax: =>
            $.ajax
                url: '/trips_ajax.php'
                type: 'GET'
                data:
                    'method': 'get_rides'
                    'data': JSON.stringify({})
                success: @load
                complete: (xhr, status) =>
                    if status != 'success'
                        @status.html(
                            "<em>#{xhr.status}: #{xhr.statusText}</em>")
                        @loader.fadeOut(500, => @msg.fadeIn(500))

        load: (json) =>
            data = JSON.parse(json)
            console.log(data)
            rides = data.rides
            if rides.length == 0
                @loader.fadeOut(500, => @msg.fadeIn(500))
                return
            @rides.fadeOut 500, =>
                @status.hide()
                @loadRides(rides)

        loadRides: (ridesData) =>
            for rideData in ridesData
                console.log(ride)
                ride = new Ride(rideData)
                @rides.append(ride.render())
            @rides.fadeIn(500)

    class Ride
        constructor: (@data) ->
            @data.departure_string = (new Date(parseInt(@data.departure_time) * 1000)).toLocaleString()
            @template = _.template($('#ride-row-template').html())

        render: ->
            template = @template(@data)
            return @template(@data)

    drivesController = new DrivesController()
    ridesController = new RidesController()