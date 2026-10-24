extends Node2D

var processor = null

var DEBUG = false

var refresh_toggle = false
#0 = stopped, 1 = connecting, 2 = getting, 3 = retreived successfully and loaded, 4 = connection failed, 5 = failed indefinitely
var get_status = 0
var requester = null
var request_timeout = 0

const CONFIG_FILE = preload("res://ENET/CONFIG.gd")
var _config = CONFIG_FILE.Config.new()

func _ready():
	$status.text = ""
	if !DEBUG:
		requester = HTTPRequest.new()
		add_child(requester)
		
		get_enet()
	else:
		var enet = load("res://ENET/eversole_network.tscn")
		enet = enet.instantiate()
		processor = enet
		add_child(enet)

func get_enet():
	get_status = 1
	print("ENET: Attempting to load most recent build")
	$status.text = "Loading Enet..."
	
	requester.request_completed.connect(_request_completed)
	
	var url = "http://" + _config.server_ip + "/handle_POST.php"
	var json = JSON.stringify({"request": "get_enet",  "data": {}, "session_id": 0})
	var json_bytes = json.to_utf8_buffer()
	
	var result = requester.request_raw(url,["Content-Type: application/json"],HTTPClient.METHOD_POST, json_bytes)
	if result != OK:
		print("ENET: HTTP Request Fail")
		$status.text = "Critical request error"
		return

func load_enet(enet_file_buffer):
	requester.queue_free()
	requester = null
	
	var file = FileAccess.open("user://enet.pck", FileAccess.WRITE)
	file.store_buffer(enet_file_buffer)
	file.close()
	
	var result = ProjectSettings.load_resource_pack("user://enet.pck")
	
	if result:
		var enet = load("res://ENET/eversole_network.tscn")
		enet = enet.instantiate()
		processor = enet
		add_child(enet)
		
		get_status = 3
		print("ENET: Loaded ENET (v" + str(enet._CLIENT_VERSION) + ")")
		$status.text = "Loaded ENET (v" + str(enet._CLIENT_VERSION) + ") Sucessfully"
		
		DirAccess.remove_absolute(ProjectSettings.globalize_path("user://enet.pck"))
	else:
		print("ENET: Invalid PCK")
		$status.text = "Critical resource error"

func _request_completed(result, _response_code, _headers, body):
	if !(result == 0):
		if result == 4:
			print("ENET: Connection Error (Shift + Tab to retry)")
			$status.text = "Connection error (Shift + Tab to retry)"
			get_status = 4
			request_timeout = 0
			requester.cancel_request()
		else:
			print("ENET: Pull Critical Error")
			$status.text = "Critical pull error"
			get_status = 5
		return
	
	load_enet(body)

func _process(delta: float) -> void:
	if Input.is_key_pressed(KEY_TAB) and Input.is_key_pressed(KEY_SHIFT) and (get_status == 4):
		get_enet()
	
	if request_timeout > 5:
		print("ENET: Connection Error (Shift + Tab to retry)")
		$status.text = "Connection error (Shift + Tab to retry)"
		get_status = 4
		request_timeout = 0
		requester.cancel_request()
	
	if get_status == 1:
		request_timeout += delta
		if requester.get_downloaded_bytes() > 0:
			get_status = 2
			print("ENET: Connected to server, starting download")
			request_timeout = 0
	elif get_status == 2:
		var progress = int(round(float(requester.get_downloaded_bytes()) / requester.get_body_size() * 100))
		$status.text  = "Loading Enet (%d%%)" % progress
	elif get_status == 3:
		if $status.visible:
			request_timeout += delta
			if request_timeout > 3:
				$status.hide()

func _get(property: StringName) -> Variant:
	if (processor and processor._functions.has_method(property)):
		return Callable(processor._functions, property)
	return null
	
