require [
    'components/map-route',
    'components/input/date-time',
    'components/input/number-input'
    ], (MapRoute, DateTime, NumberInput) ->

    class RideSharer
        constructor: ->
            # Google Maps Options
            @mapOptions =
                center: new google.maps.LatLng(51.517099, -0.146084)
                zoom: 12
                mapTypeId: google.maps.MapTypeId.ROADMAP

            # Initialize Google Maps
            @map = new google.maps.Map($('#map_canvas')[0], @mapOptions)

            @departure = new DateTime(
                $('#share-departure'),
                $('#share-departure-date'),
                $('#share-departure-time'))

            @route = new MapRoute(
                $('#share-route'),
                @map,
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
                                @setButton('disabled btn btn-success', json['msg'])
                                return
                            else
                                error = json['msg']
                        @setButton('disabled btn btn-danger', error)
                    error: (data) =>
                        @setButton('disabled btn btn-danger', 'Error!')
            
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

    new RideSharer()