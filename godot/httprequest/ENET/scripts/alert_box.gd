extends Control

func _ready():
	var f = load("res://ENET/scripts/enet_functions.gd")
	var _internal = f.InternalFunctions.new()
	_internal.load_images(self)

func start(title, score, rank, pr):
	get_node('box/title').text = title
	get_node('box/score').text = str(score)
	if int(rank) < 4:
		get_node('box/ranking/top_ranking').play(str(rank))
		get_node('box/ranking/number').hide()
	else:
		get_node('box/ranking/top_ranking').hide()
		get_node('box/ranking/number').text = "#" + str(rank)
		
	if pr:
		get_node('box/ranking/pr').show()
	else:
		get_node('box/ranking/pr').hide()

func finished():
	queue_free()
