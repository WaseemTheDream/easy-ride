drivesController = drivesController or {}
jQuery ->

    class DrivesController
        constructor: ->
            @statusMsg = $('#trips-driving-msg')
            @statusLoader = $('#trips-driving-loader')
            setTimeout(@ajax, 1000)

        ajax: =>
            $.ajax
                url: '/trips_ajax.php'
                type: 'GET'
                data: 'data': JSON.stringify({})
                success: @load
                complete: (xhr, status) =>
                    if status != 'success'
                        @statusMsg.html("<em>#{xhr.statusText}</em>")
            if true
                @statusLoader.fadeOut 500, =>
                    @statusMsg.fadeIn(500)

        load: (json) =>
            data = JSON.parse(json)
            console.log(data)

    class Drive
        constructor: (@data) ->

    drivesController = new DrivesController()