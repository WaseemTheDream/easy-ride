require [
    'components/map-route',
    'components/input/date-picker'
    ], (MapRoute, DatePicker) ->

    class RideSearcher
        constructor: ->
            # Member variables
            @womenOnly = $('#search-women-only')
            @departure = new DatePicker(
                $('#search-departure'),
                $('#search-departure-date'),
                true)

            @route = new MapRoute(
                $('#search-route'),
                $('#search-from'),
                $('#search-to'))

            @searchButton = $('#search-button')
            
            @searchButton.click =>
                data = @toJson()
                return null if data == null
                console.log(data)

                $.ajax
                    url: ''
                    type: 'GET'
                    data: 'data': JSON.stringify(data)
                    success: (data) =>
                        @setButton('btn btn-success', 'Search')
                    error: (data) ->
                        @setButton('btn btn-danger', 'Error!')
            
        setButton: (btnClass, msg) =>
            @searchButton.attr('class', btnClass)
            @searchButton.text(msg)

        toJson: =>
            json =
                departure: @departure.getTime()
                women_only: @womenOnly.prop('checked')
                route: @route.toJson()
            for key, value of json
                if value == null
                    return null
            return json

    new RideSearcher()