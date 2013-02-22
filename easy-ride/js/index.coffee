jQuery ->

    class RideSearcher
        constructor: ->
            # Member variables
            @from = new google.maps.places.SearchBox($('#search-from')[0])
            @to = new google.maps.places.SearchBox($('#search-to')[0])

            # Google Maps Options
            mapOptions =
                center: new google.maps.LatLng(51.517099, -0.146084)
                zoom: 8
                mapTypeId: google.maps.MapTypeId.ROADMAP

            # Initialize Google Maps
            map = new google.maps.Map($('#map_canvas')[0], mapOptions)
            

        updateFrom: ->
            query = @from.val().trim()
            if not query
                return




    new RideSearcher()