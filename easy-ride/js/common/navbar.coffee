jQuery ->
	# Setup drop down menu
	$('.dropdown-toggle').dropdown();
	$('.dropdown input, .dropdown label').click (e)->
		e.stopPropagation()
	return