extends Node

class EnetFunctions:
	func login(username, password):
		_enet.processor.get_node("UI/Login/Errors").text = ""
		if _enet.processor.USER:
			print("ENET: Already logged in")
		elif _enet.processor.STATUS == 1:
			var data = {'username' : username, 'pwd': password, "session_id": _enet.processor.SESSION_ID}
			_enet.processor.request_queue.append([data, "game_login"])
		else:
			print("ENET: Login Error - Not Connected to Server")

	func logout():
		if _enet.processor.USER:
			_enet.processor.USER = null
			_enet.processor.USER_ID = null
			if _enet.processor.STATUS == 1:
				_enet.processor.request_queue.append([{"session_id": _enet.processor.SESSION_ID}, "game_logout", null, _enet.processor.SESSION_ID])
			_enet.processor.get_node("UI/STATUS/session_info/user").text = "User: Not logged in"
			print("ENET: Logged out")

	func ping():
		_enet.processor.pinging = true
		if _enet.processor.STATUS == 1:
			var data = {'session_id': _enet.processor.SESSION_ID}
			_enet.processor.request_queue.append([data, "ping"])
		else:
			_enet.processor._internal.change_status(0)
			var data = {'session_gameid' : _enet._config.game_id, 'session_gamepwd': _enet._config.game_password, 'session_gameversion': _enet._config.game_version, "_version": _enet.processor._CLIENT_VERSION}
			_enet.processor.request_queue.append([data, "game_init"])

	func post_score(score):
		if _enet.processor.STATUS == 1:
			if _enet.processor.USER:
				var data = {'session_id': _enet.processor.SESSION_ID, 'score': score}
				_enet.processor.request_queue.append([data, "post_score"])
			else:
				_enet.processor.anonymous_scores.append(score)
				print("ENET: Score of (" + str(score)  + ") saved to post upon login")
		else:
			_enet.processor.offline_score_queue.append(score)

	func pull_scores():
		if _enet.processor.STATUS == 1:
			var data = {"request": "pull_scores", "session_id": _enet.processor.SESSION_ID}
			_enet.processor.request_queue.append([data, "pull_data"])
	
	func save_state(data):
		if _enet.processor.STATUS == 1 and _enet.processor.USER != null:
			var base64 = Marshalls.variant_to_base64(data)
			
			var send_data = {"session_id": _enet.processor.SESSION_ID, "data": base64}
			_enet.processor.request_queue.append([send_data, "save_state"])
	
	func get_state():
		if _enet.processor.STATUS == 1 and _enet.processor.USER != null:
			_enet.processor.request_queue.append([{"session_id": _enet.processor.SESSION_ID}, "save_state"])
	
	func reward(amount):
		if _enet.processor.STATUS == 1 and _enet.processor.USER != null:
			_enet.processor.request_queue.append([{"session_id": _enet.processor.SESSION_ID, "amount": amount}, "transactions"])
	
	func spend(amount):
		reward(-amount)

class InternalFunctions:
	func send_data(args):
		var json = null
		if args[1] == "game_init":
			json = JSON.stringify({'data': args[0], 'request': args[1], 'session_id': 0})
		else:
			json = JSON.stringify({'data': args[0], 'request': args[1], 'session_id': _enet.processor.SESSION_ID})
		
		_enet.processor.current_request = args[1]
		if _enet.processor.current_request == "post_score":
			_enet.processor.posting_score = args[0]['score']
		_enet.processor.http_request.request(_enet.processor.server,["Content-Type: application/json"],HTTPClient.METHOD_POST, json)

	func disconnect_session():
		_enet.processor.change_status(_enet, 0)
		_enet.processor.SESSION_ID = 0
		_enet.processor.time_since_last_ping = 0
		_enet.processor._functions.logout()
		if _enet.processor.current_request == "post_score":
			_enet.processor.offline_score_queue.append(_enet.processor.posting_score)
			_enet.processor.posting_score = null
		for request in _enet.processor.request_queue:
			if request[1] == "post_score":
				_enet.processor.offline_score_queue.append(request[0]["score"])
		_enet.processor.current_request = null
		_enet.processor.current_request_duration = 0
		_enet.processor.request_queue = []
		
		
		_enet.processor._functions.ping(_enet)

	func change_status(status):
		_enet.processor.STATUS = status
		
		if status == 1:
			_enet.processor.get_node("UI/STATUS/text").play("connected")
			_enet.processor.get_node("UI/STATUS/icon").play("connected")
			_enet.processor.get_node("UI/STATUS/session_info").show()
			_enet.processor.get_node("UI/Login/login_button").disabled = false
		else:
			if status == 0:
				_enet.processor.get_node("UI/STATUS/text").play("connecting")
				_enet.processor.get_node("UI/STATUS/icon").play("connecting")
			else:
				_enet.processor.get_node("UI/STATUS/text").play("not_connected")
				_enet.processor.get_node("UI/STATUS/icon").play("not_connected")
			
			_enet.processor.get_node("UI/STATUS/session_info").hide()
			_enet.processor.get_node("UI/Login/login_button").disabled = true
			_enet.processor.get_node("UI/Info/game").text = ""
			_enet.processor.get_node("UI/Info/players_online").text = ""

	#0 = login, 1 = scores
	func change_page(page):
		if page == 0:
			if !_enet.processor.USER:
				_enet.processor.get_node("UI/Logout").hide()
				_enet.processor.get_node("UI/Logout/logout_button").disabled = true
				_enet.processor.get_node("UI/Logout/back").disabled = true
				_enet.processor.get_node("UI/Login/login_button").disabled = false
				_enet.processor.get_node("UI/Login/skip_button").disabled = false
				_enet.processor.get_node("UI/Login").show()
			else:
				_enet.processor.get_node("UI/Logout/logout_button").disabled = false
				_enet.processor.get_node("UI/Logout/back").disabled = false
				_enet.processor.get_node("UI/Logout/user").text = "Logged in as: " + _enet.processor.USER
				_enet.processor.get_node("UI/Logout").show()
			_enet.processor.get_node("UI/Info/account").disabled = true
			_enet.processor.get_node("UI/Info/account").hide()
			_enet.processor.get_node("UI/Scores").hide()
			_enet.processor.get_node("UI/Info/player_info").hide()
		elif page == 1:
			_enet.processor._internal.clear_scores()
			_enet.processor._functions.pull_scores()
			_enet.processor.get_node("UI/Logout/logout_button").disabled = true
			_enet.processor.get_node("UI/Logout/back").disabled = true
			_enet.processor.get_node("UI/Login/login_button").disabled = true
			_enet.processor.get_node("UI/Login/skip_button").disabled = true
			_enet.processor.get_node("UI/Info/account").disabled = false
			_enet.processor.get_node("UI/Info/account").show()
			if _enet.processor.USER:
				_enet.processor.get_node("UI/Scores/UserScores").show()
				_enet.processor.get_node("UI/Scores/Headings/User Scores").show()
				_enet.processor.get_node("UI/Info/player_info").show()
			else:
				_enet.processor.get_node("UI/Scores/UserScores").hide()
				_enet.processor.get_node("UI/Scores/Headings/User Scores").hide()
			_enet.processor.get_node("UI/Logout").hide()
			_enet.processor.get_node("UI/Login").hide()
			_enet.processor.get_node("UI/Scores").show()
		elif page == -1:
			_enet.processor.get_node("UI/Logout/logout_button").disabled = true
			_enet.processor.get_node("UI/Logout/back").disabled = true
			_enet.processor.get_node("UI/Login/login_button").disabled = true
			_enet.processor.get_node("UI/Login/skip_button").disabled = true
			_enet.processor.get_node("UI/Info/account").disabled = true
			return
		
		_enet.processor.PAGE = page

	func toggle_enet():
		if _enet.processor.OPEN:
			_enet.processor.get_node("UI").hide()
			change_page(-1)
			_enet.processor.OPEN = 0
		else:
			_enet.processor.OPEN = 1
			change_page(_enet.processor.PAGE)
			_enet.processor.get_node("UI").show()

	func clear_scores():
		for child in _enet.processor.get_node("UI/Scores/AllScores/Container").get_children():
			_enet.processor.get_node("UI/Scores/AllScores/Container").remove_child(child)

	func load_scores():
		var limit = 13
		var children = _enet.processor.get_node("UI/Scores/AllScores/Container").get_child_count()
		var count = 1
		
		#User Scores
		_enet.processor.user_score_cache = []
		for child in _enet.processor.get_node("UI/Scores/UserScores/Container").get_children():
			_enet.processor.get_node("UI/Scores/UserScores/Container").remove_child(child)
		
		
		if !(children == 0):
			limit += children
			_enet.processor.get_node("UI/Scores/AllScores/Container").remove_child(_enet.processor.score_load_button)
		else:
			limit += 1
		
		if _enet.processor.score_cache.size() > 0:
			for score_index in range(_enet.processor.score_cache.size()):
				#Create score_only list
				var scores_only = []
				for score in _enet.processor.score_cache:
					scores_only.append(score['score'])
				
				var score = _enet.processor.score_cache[score_index]
				
				if (((children - 2) < score_index) and (score_index < (limit - 1))) or (!(score['user'] == null) and (_enet.processor.USER == score['user'])):
					var row = _enet.processor.score_row.instantiate()
					
					var edited_count = count
					if (score['score'] == _enet.processor.score_cache[count - 2]['score']):
						edited_count = scores_only.find(score['score']) + 1
						
					if !(score['user'] == null) and (_enet.processor.USER == score['user']):
						var user_row = row.duplicate()
						_enet.processor.get_node("UI/Scores/UserScores/Container").add_child(user_row)
						user_row.start(edited_count, score['user'], score['score'])
						_enet.processor.user_score_cache.append(score)
					if (((children - 2) < score_index) and ( score_index < (limit - 1))):
						_enet.processor.get_node("UI/Scores/AllScores/Container").add_child(row)
					
					row.start(edited_count, score['user'], score['score'])
				
				count += 1
			
			if _enet.processor.get_node("UI/Scores/UserScores/Container").get_child_count() == 0:
				var filler = _enet.processor.score_row_filler.instantiate()
				filler.get_node('no_scores').show()
				filler.get_node('load_more').hide()
				filler.get_node('load_more').disabled = true
				_enet.processor.get_node("UI/Scores/UserScores/Container").add_child(filler)
			
			children = _enet.processor.get_node("UI/Scores/AllScores/Container").get_child_count()
			if _enet.processor.score_cache.size() > children:
				var load_more = _enet.processor.score_row_filler.instantiate()
				load_images(load_more)
				load_more.get_node('no_scores').hide()
				load_more.get_node("load_more").connect("pressed", load_scores)
				_enet.processor.get_node("UI/Scores/AllScores/Container").add_child(load_more)
				_enet.processor.score_load_button = load_more
		else:
			var filler = _enet.processor.score_row_filler.instantiate()
			filler.get_node('no_scores').show()
			filler.get_node('load_more').hide()
			filler.get_node('load_more').disabled = true
			_enet.processor.get_node("UI/Scores/AllScores/Container").add_child(filler)
			_enet.processor.get_node("UI/Scores/UserScores/Container").add_child(filler.duplicate())
	
	func load_images(root):
		for child in root.get_children():
			if child is Sprite2D or child is TextureRect:
				child.texture = load(child.get_meta("texture_path"))
			
			elif child is AnimatedSprite2D:
				var frames = SpriteFrames.new()
				var metadata = child.get_meta("texture_path")
				for animation in metadata.keys():
					frames.add_animation(animation)
					for texture in metadata[animation]:
						frames.add_frame(animation, load(texture))
					frames.set_animation_loop(animation, true)
				child.sprite_frames = frames
			
			elif child is TextureButton:
				var metadata = child.get_meta("texture_path")
				for property in metadata.keys():
					child.set(property, load(metadata[property]))
			
			load_images(child)
