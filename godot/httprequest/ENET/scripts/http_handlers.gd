extends Node

class HTTPHandlers:
	func game_login_handler(data):
		var json = JSON.parse_string(data)
		if json.has('errors'):
			for error in json['errors']:
				print("ENET: Login Error - " + error)
				_enet.processor.get_node("UI/Login/Errors").text = _enet.processor.err_msg[error]
				_enet.processor.get_node("UI/Login/login_button").disabled = false
		elif !_enet.processor.USER:
			_enet.processor.USER = json['username']
			_enet.processor.USER_ID = json['id']
			print("ENET: Login Successful")
			
			_enet.processor._internal.change_page(1)
			_enet.processor.get_node("UI/Login/Errors").text = ""
			_enet.processor.get_node("UI/Login/Username/username_input").text = ""
			_enet.processor.get_node("UI/Login/Password/password_input").text = ""
			_enet.processor.get_node("UI/STATUS/session_info/user").text = "User: " + json['username']
			
			_enet.processor.get_node("UI/Info/player_info/xp/amt").text = json['xp']
			_enet.processor.get_node("UI/Info/player_info/coins/amt").text = json['coins']
			
			if _enet.processor.anonymous_scores.size():
				for score in _enet.processor.anonymous_scores:
					_enet.processor._functions.post_score(score)
					print("ENET: Attemping to post score (" + str(score) + ") with user: " + _enet.processor.USER)
				_enet.processor.anonymous_scores.clear()
			_enet.processor._functions.get_state()

	func ping_handler(data):
		_enet.processor.pinging = false
		var json = JSON.parse_string(data)
		if json.has('errors'):
			print("ENET: Ping Error - ", json['errors'])
			_enet.processor._internal.change_status(-1)
		elif json.has('disconnect'):
			_enet.processor.SESSION_ID = null
			_enet.processor.USER = null
			_enet.processor.USER_ID = null
				
			print("ENET: Disconnected - Server force shutdown")
			_enet.processor._internal.change_status(-1)
		else:
			_enet.processor.SESSION_ID = json['session_id']
			_enet.processor.get_node("UI/Info/players_online").text = "Online: " + str(json['players_online'])
			if json.has('xp'):
				_enet.processor.get_node("UI/Info/player_info/xp/amt").text = json['xp']
				_enet.processor.get_node("UI/Info/player_info/coins/amt").text = json['coins']
	
	func game_init_handler(data):
		_enet.processor.pinging = false
		var json = JSON.parse_string(data)
		if json.has('errors'):
			print("ENET: Ping Error - ", json['errors'])
		else:
			_enet.processor.SESSION_ID = json['session_id']
			print("ENET: Connected")
			
			_enet.processor.game_name = json['game_name']
			_enet.processor.get_node("UI/Info/game").text = _enet.processor.game_name + " (v" + str(_enet._config.game_version) + ")"
			_enet.processor.get_node("UI/Info/players_online").text = "Online: " + str(json['players_online'])
			
			_enet.processor.get_node("UI/STATUS/session_info/version").text = "ENET v" + str(json['version'])
			_enet.processor.get_node("UI/STATUS/session_info/session_id").text = str(_enet.processor.SESSION_ID)
			_enet.processor._internal.change_status(1)
			
			_enet.processor.err_msg = json['error_messages']
			
			if _enet.processor.offline_score_queue.size():
				print("ENET: Attempting to post scores - ", _enet.processor.offline_score_queue)
				for score in _enet.processor.offline_score_queue:
					_enet.processor.post_score(score)
				_enet.processor.offline_score_queue.clear()

	func post_score_handler(data):
		var json = JSON.parse_string(data)
		if json.has("errors"):
			print("ENET: Score Post ERROR - ", json["errors"])
		else:
			print("ENET: Score Post Success - ", json['score'], " #", json['rank'], " by ", json['user'], " (", json['id'], ")", " PR: ", json['pr'])
			
			_enet.processor.alert_queue.append({"title": "Score logged", "score": json['score'], 'pr': json['pr'], 'rank': json['rank']})
			
			if _enet.processor.PAGE == 1:
				_enet.processor._internal.change_page(1)
				
			if json.has('achievements'):
				pass
			
		_enet.processor.posting_score = null

	func pull_data_handler(data):
		var json = JSON.parse_string(data)
		if json.has("errors"):
			print("ENET: Pull Scores ERROR - ", json["errors"])
		else:
			_enet.processor.score_cache = json['scores']
			_enet.processor._internal.load_scores()
	
	func save_state_handler(data):
		#data == [] means success saving
		#data == {"save_data": ...} means get save
		#data == {"save_data": null} means no save
		var json = JSON.parse_string(data)
		if json.has("errors"):
			print("ENET: Save State ERROR - ", json["errors"])
		if json.has("save_data"):
			if json['save_data']:
				var decoded_data = Marshalls.base64_to_variant(json['save_data'])
				
				print("ENET: Get State - ", decoded_data)
			else:
				print("ENET: No Saved State")
	
	func transactions_handler(data):
		var json = JSON.parse_string(data)
		if json.has("errors"):
			print("ENET: Transaction ERROR - ", json["errors"])
		elif json.has('user'):
			if json['amount'] > 0:
				print("ENET: Transaction Success (" + str(json['amount']) + " coins awarded to user id " + str(int(json['user'])) + ")")
			else:
				print("ENET: Transaction Success (" + str(-json['amount']) + " coins taken from user id " + str(int(json['user'])) + ")")
		
		
		
		
		
		
		
