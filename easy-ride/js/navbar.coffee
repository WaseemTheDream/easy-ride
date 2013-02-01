jQuery ->
	# Setup drop down menu
	console.log('Script ran')
	$('.dropdown-toggle').dropdown();
	$('.dropdown input, .dropdown label').click (e)->
		e.stopPropagation()
	return