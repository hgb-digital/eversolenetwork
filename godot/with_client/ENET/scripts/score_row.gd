extends TextureRect

func _ready() -> void:
	var f = load("res://ENET/scripts/enet_functions.gd")
	var _internal = f.InternalFunctions.new()
	_internal.load_images(self)

func start(count, user, score):
	if count < 4:
		get_node("top_ranking").play(str(count))
		get_node('rank').hide()
	else:
		get_node("top_ranking").hide()
		get_node('rank').text = "#" + str(count)
	get_node('name').text = user
	get_node('score').text = str(score)
