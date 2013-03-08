define [], ->
    class UserInterface
        constructor: (@container) ->

        ###
            Set error.
            Args:
                msg {String}: error message string.
        ###
        setError: (msg) =>
            @removeError()
            @container.addClass('error')
            error = $(
                '<div>',
                    class: 'controls help-inline error-msg'
                ).append(msg)
            @container.append(error)

        ###
            Remove error.
        ###
        removeError: =>
            @container.removeClass('error')
            @container.children().filter('.error-msg').remove()
