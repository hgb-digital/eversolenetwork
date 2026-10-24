extends Node2D

#--DO NOT CHANGE--#
var _CLIENT_VERSION = .1

const HTTP_HANDLERS = preload("res://ENET/scripts/http_handlers.gd")
const FUNCTIONS = preload("res://ENET/scripts/enet_functions.gd")
var _handlers = HTTP_HANDLERS.HTTPHandlers.new()
var _functions = FUNCTIONS.EnetFunctions.new()
var _internal = FUNCTIONS.InternalFunctions.new()

#--DO NOT CHANGE--#

#--Base URL of server--#
var server = "http://" + _enet._config.server_ip + "/handle_POST.php"

var USER = null
var USER_ID = null

var SESSION_ID = null

var SAVE_STATE = null

# 0 = Disconnected, 1 = Connected, -1 = Disconnected by server
var STATUS = 0
var PAGE = 0

var game_name = null
var OPEN = -1
@onready var score_row = preload("res://ENET/objects/score_row.tscn")
@onready var score_row_filler = preload("res://ENET/objects/score_row_filler.tscn")
@onready var http_request = $RequestHandler
var score_cache = []
var user_score_cache = []
var score_load_button = null
var time_since_last_ping = 100
var request_queue = []
var current_request = null
var current_request_duration = 0

var err_msg = []

var offline_score_queue = []
var anonymous_scores = []
var posting_score = null

var toggle_temp = false

#title, score, rank, pr?
var alert_queue = []
@onready var alert = preload("res://ENET/objects/alert_box.tscn")
var current_alert = null

var pinging = false

func _ready():
	_internal.load_images(self)
	
	if OPEN == -1:
		_internal.change_status(0)
		http_request.request_completed.connect(_on_request_completed)
		PAGE = 0
		$UI.hide()
		_internal.change_page(-1)
		OPEN = 0

func _process(delta: float) -> void:
	if STATUS > -1:
		if !pinging:
			time_since_last_ping += delta
			if time_since_last_ping > 5:
				_functions.ping()
				time_since_last_ping = 0
	
	if !current_request and request_queue.size():
		_internal.send_data(request_queue[0])
		request_queue.remove_at(0)
	elif current_request:
		current_request_duration += delta
		if current_request_duration > 17:
			current_request = null
			current_request_duration = 0
			print("ENET: Request Timeout")
	
	if alert_queue.size() > 0 and !is_instance_valid(current_alert):
		var alert_object = alert.instantiate()
		current_alert = alert_object
		
		$Alerts.add_child(alert_object)
		alert_object.start(alert_queue[0]['title'], alert_queue[0]['score'], alert_queue[0]['rank'], alert_queue[0]['pr'])
		
		alert_queue.remove_at(0)
	
	if Input.is_key_pressed(KEY_TAB) and Input.is_key_pressed(KEY_SHIFT):
		if !toggle_temp:
			_internal.toggle_enet()
			toggle_temp = true
	else:
		toggle_temp = false
		
	if Input.is_action_just_pressed("ui_accept"):
		_functions.reward(56)
	if Input.is_action_just_pressed("ui_right"):
		_functions.spend(20)



func _on_request_completed(_results, response_code, _headers, body):
	if response_code == 0:
		_internal.disconnect_session()
		print("ENET: Disconnected - FAILED REQUEST")
		return
	var r = current_request
	if ((r != null) and (r != "game_logout")):
		var handler = Callable(_handlers, r + "_handler")
		handler.call(body.get_string_from_utf8())
	current_request = null
	current_request_duration = 0


func _on_button_pressed():
	_functions.login($UI/Login/Username/username_input.text, $UI/Login/Password/password_input.text)
	$UI/Login/login_button.disabled = true

func _on_skip_button_pressed() -> void:
	_internal.change_page(1)

func _on_account_pressed() -> void:
	if !(PAGE == 0):
		_internal.change_page(0)

func _on_logout_button_pressed() -> void:
	if USER:
		_functions.logout()
		_internal.change_page(0)

func _on_back_pressed() -> void:
	_internal.change_page(1)
