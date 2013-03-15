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
            @template = _.template($('#rider-request-template').html())
            @spotsRemaining = $('#modal-ride-requests-spots-remaining')
            @spotsRemainingVal = $('#modal-ride-requests-spots-remaining-value')
            @form = $('#modal-ride-requests-form')

        show: => @el.modal('show')
        hide: => @el.modal('hide')
        reset: =>
            @loader.show()
            @status.show()
            @msg.hide()
            @form.html('').hide()
            @spotsRemaining.hide()

        load: (@tripId) =>
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
            for request in data.requests
                @form.append(@template(request))
            @loader.fadeOut(500, => @form.slideDown(500))


    drivesController = new DrivesController()