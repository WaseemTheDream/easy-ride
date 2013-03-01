jQuery ->
	getId = (id) -> $("##{id}")
	getIds = (list) -> (getId(id) for id in list)
	
	addError = (elem, level, message) ->
		target = elem
		for i in [0...level-1]
			target = target.parent()
		target.parent().addClass 'error'
		target.append("<span class=\"help-inline\">#{message}</span>")
	
	removeError = (elem, level) ->
		target = elem
		for i in [0...level-1]
			target = target.parent()
		target.parent().removeClass 'error'
		target.children().filter('.help-inline').remove()
	
	setError = (elem, level, message) ->
		removeError(elem, level)
		addError(elem, level, message)
	
	isValidEmailAddress = (email) ->
		pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/
		return pattern.test email
		
	trimField = (field) ->
		field.val($.trim field.val())
	
	verifyRequiredField = (field) ->
		if not field.val()
			setError(field, 2, 'Field required.')
			return false
		else
			removeError(field, 2)
			return true
	
	verifyEmailAddressField = (field) ->
		if field.val()
			if not isValidEmailAddress(email.val())
				setError(email, 2, 'Invalid email address.')
			else
				removeError(email, 2)
				return true
		return false
	
	verifyPasswordsMatch = (fieldA, fieldB) ->
		if not fieldA.val() or not fieldB.val()
			return false
		if fieldA.val() != fieldB.val()
			setError(fieldB, 2, 'Passwords don\'t match.')
			return false
		else
			removeError(fieldB, 2)
			return true
	
	verifyRequiredRadio = (radioInputList) ->
		selected = false
		for input in radioInputList
			if input.prop('checked')
				selected = true
		if selected
			removeError(radioInputList[0], 3)
		else
			if radioInputList
				setError(radioInputList[0], 3, 'Field required.')
		return selected
	
	firstName = getId('register-first-name')
	lastName = getId('register-last-name')
	email = getId('register-email')
	password = getId('register-password')
	repeatPassword = getId('register-repeat-password')
	driverLicenseId = getId('register-driver-license-id')
	gender = getIds(['register-male', 'register-female'])
	required = [firstName, lastName, email, password, repeatPassword]
	$.each required, ->
		field = this
		field.bind 'blur', ->
			trimField(field)
			verifyRequiredField(field)
	
	email.bind 'blur', ->
		verifyEmailAddressField(email)
	
	repeatPassword.bind 'blur', ->
		verifyPasswordsMatch(password, repeatPassword)
	
	# Validate form submission
	$('#register').submit ->
		errors = false
		for field in required
			if not verifyRequiredField(field)
				errors = true

		if not verifyEmailAddressField(email)
			errors = true

		if not verifyPasswordsMatch(password, repeatPassword)
			errors = true

		if not verifyRequiredRadio(gender)
			errors = true
		
		return not errors
	