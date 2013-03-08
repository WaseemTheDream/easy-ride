define ['common/bootstrap-datepicker',
        'components/user-interface'], (datepicker, UserInterface) ->
    ###
        A Date module that uses a Bootstrap DatePicker.
    ###
    class DatePicker extends UserInterface
        constructor: (@container, date, @required=false) ->
            super(@container)
            @date = date.datepicker().data('datepicker')

        ###
            Returns date as time in integer value, if entered
        ###
        getTime: =>
            dateString = @date.element.children().filter('input').val()
            if not dateString and @required
                @setError('Missing information.')
                return null

            @removeError()
            return @date.date.valueOf() / 1000