jQuery ->

    class RideSearcher
        constructor: ->
            # Member variables
            @from = $('#search-from')
            @to = $('#search-to')

            # Google Maps Options
            mapOptions =
                center: new google.maps.LatLng(51.517099, -0.146084)
                zoom: 8
                mapTypeId: google.maps.MapTypeId.ROADMAP

            # Initialize Google Maps
            map = new google.maps.Map($('#map_canvas')[0], mapOptions)
            @from = new google.maps.places.SearchBox($('#search-from')[0])
            
        updateFrom: ->
            query = @from.val().trim()
            if not query
                return




    new RideSearcher()