define ['components/user-interface'], (UserInterface) ->
    class TextInput extends UserInterface
        constructor: (@container, @input, @required=false) ->
            super(@container)

        getValue: =>
            inputString = @input.val().trim()
            if @required and not inputString
                @setError('Required field.')
                return null
            @removeError()
            return inputString

        setValue: (val) =>
            @input.val(val)
            @removeError()