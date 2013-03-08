define ['components/input/text-input'], (TextInput) ->
    class NumberInput extends TextInput
        getValue: =>
            inputString = @input.val().trim()
            if @required and not inputString
                @setError('Required field.')
                return null

            min = parseInt(@input.attr('min'))
            max = parseInt(@input.attr('max'))
            val = parseInt(@input.val())
            if not (min <= val and val <= max)
                @setError('Not within valid range.')
                return null

            return val