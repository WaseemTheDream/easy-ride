drivesController = drivesController or {}
jQuery ->

    class DrivesController
        constructor: ->
            @status = $('#trips-driving-status')
            @statusMsg = $('#trips-driving-msg')
            @statusLoader = $('#trips-driving-loader')
            @drives = $('#trips-driving')
            setTimeout(@ajax, 1000)

        ajax: =>
            $.ajax
                url: '/trips_ajax.php'
                type: 'GET'
                data: 'data': JSON.stringify({})
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
            else
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


    class Drive
        constructor: (@data) ->
            @data.departure_string = (new Date(parseInt(@data.departure_time) * 1000)).toLocaleString()
            @template = _.template($('#trip-row-template').html())

        render: ->
            return @template(@data)

    drivesController = new DrivesController()