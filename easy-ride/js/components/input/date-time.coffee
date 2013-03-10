define ['lib/bootstrap-datepicker',
        'lib/bootstrap-timepicker',
        'components/user-interface'], (datepicker, timepicker, UserInterface) ->
    ###
        A DateTime module that uses a Bootstrap DatePicker and TimePicker and
        combines the input.
    ###
    class DateTime extends UserInterface
        constructor: (@container, date, time) ->
            super(@container)
            @date = date.datepicker().data('datepicker')
            @time = time.timepicker()

        ###
            Returns time as an integer value, if entered
        ###
        getDateTime: =>
            dateString = @date.element.children().filter('input').val()
            timeString = @time.val()
            if not dateString or not timeString
                @setError('Missing information.')
                return null

            time = @parseTime(timeString)
            date = @date.date.valueOf() / 1000
            @removeError()
            return date + time

        ###
            Parses the time and returns the integer value in seconds.
            Args:
                string {String}: time string e.g. '04:50 PM'
        ###
        parseTime: (string) =>
            strings = string.split(' ')
            time = strings[0].split(':')
            meridiem = strings[1]
            hours = parseInt(time[0])
            minutes = parseInt(time[1])
            if meridiem == 'PM'
                hours += 12
            return hours * 3600 + minutes * 60
