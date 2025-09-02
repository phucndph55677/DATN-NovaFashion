(function(){
	var NFChat = {
		state: {
			view: 'list',
			active: null,
			messagesById: {},
			session_code: null,
			is_logged_in: false
		},
		init: function(){
			if(!window.NFChatUI){ return; }
			NFChatUI.init();
			this.bindCore();
			this.seed();

			// Kiểm tra trạng thái đăng nhập
			this.checkLoginStatus();

			// Load session_code từ localStorage nếu có
			var savedSession = localStorage.getItem('nova_chat_session');
			if (savedSession) {
				this.state.session_code = savedSession;
			}

			this.toList();
		},
		checkLoginStatus: function(){
			// Kiểm tra xem user có đang đăng nhập không
			var tokenMeta = document.querySelector('meta[name="csrf-token"]');
			var wasLoggedIn = this.state.is_logged_in;

			if (tokenMeta) {
				// Nếu có CSRF token, có thể user đã đăng nhập
				this.state.is_logged_in = true;

				// Nếu user vừa đăng nhập lại (từ logout), load lịch sử chat
				if (!wasLoggedIn && this.state.view === 'chat') {
					this.loadUserChatHistory();
				}
			} else {
				// Nếu không có CSRF token, user chưa đăng nhập
				this.state.is_logged_in = false;
				// Xóa session cũ nếu user logout
				this.clearSession();
			}
		},
		clearSession: function(){
			this.state.session_code = null;
			localStorage.removeItem('nova_chat_session');
		},
		bindCore: function(){
			var panel = document.getElementById('nf-chatbot');
			var toggle = document.getElementById('nf-chatbot-toggle');
			var closeBtn = panel ? panel.querySelector('.nf-chatbot__close') : null;
			var backBtn = panel ? panel.querySelector('.nf-chatbot__back') : null;
			var userBtns = panel ? panel.querySelectorAll('.nf-chatbot__user') : [];
			var sendBtn = panel ? panel.querySelector('.nf-chatbot__send') : null;

			if(toggle){ toggle.addEventListener('click', function(){ panel.classList.toggle('nf-chatbot--open'); if(panel.classList.contains('nf-chatbot--open') && NFChat.state.view==='chat'){ NFChatUI.focusInput(); } }); }
			if(closeBtn){ closeBtn.addEventListener('click', function(){ panel.classList.remove('nf-chatbot--open'); }); }
			if(backBtn){ backBtn.addEventListener('click', this.toList.bind(this)); }
			userBtns.forEach(function(btn){
				btn.addEventListener('click', function(){
					var name = btn.getAttribute('data-user') || 'Nova AI';
					NFChat.openConversation(name);
				});
			}.bind(this));

			var input = NFChatUI.els.input;
			if(input){
				input.addEventListener('keydown', function(e){ if(e.key==='Enter'){ e.preventDefault(); NFChat.send(); } });
				input.addEventListener('input', function(){ if(sendBtn){ sendBtn.disabled = !(input.value||'').trim(); } });
			}
			if(sendBtn){ sendBtn.addEventListener('click', this.send.bind(this)); }

			// Thêm event listener để xử lý khi user logout
			this.bindLogoutListener();
		},
		bindLogoutListener: function(){
			// Lắng nghe sự kiện click trên các link logout
			document.addEventListener('click', function(e){
				if (e.target && (e.target.closest('a[href*="logout"]') || e.target.closest('form[action*="logout"]'))) {
					// Khi user logout, clear session
					setTimeout(function(){
						NFChat.clearSession();
						NFChat.state.is_logged_in = false;
					}, 100);
				}
			});

			// Lắng nghe sự kiện submit form logout
			document.addEventListener('submit', function(e){
				if (e.target && e.target.action && e.target.action.includes('logout')) {
					setTimeout(function(){
						NFChat.clearSession();
						NFChat.state.is_logged_in = false;
					}, 100);
				}
			});
		},
		seed: function(){
			this.state.messagesById['Nova AI'] = [ { role:'bot', text:'Xin chào! Mình là Nova AI. Bạn cần tư vấn sản phẩm hay hỗ trợ đơn hàng không?' } ];
			// Tạm thời ẩn Admin chat để tập trung vào AI
			// this.state.messagesById['Admin'] = [ { role:'bot', text:'Xin chào, admin sẽ hỗ trợ bạn khi cần!' } ];
			// this.state.messagesById['CSKH 1'] = [ { role:'bot', text:'Chúng tôi sẵn sàng hỗ trợ đơn hàng của bạn.' } ];
		},
		toList: function(){
			this.state.view = 'list';
			var panel = document.getElementById('nf-chatbot');
			panel.classList.add('nf-chatbot--listing');
			NFChatUI.setTitle('Hỗ trợ');
		},
		openConversation: function(name){
			this.state.view = 'chat';
			this.state.active = name;
			var panel = document.getElementById('nf-chatbot');
			panel.classList.remove('nf-chatbot--listing');
			NFChatUI.setTitle(name);

			// Kiểm tra lại trạng thái đăng nhập
			this.checkLoginStatus();

			// Luôn load lịch sử chat nếu user đã đăng nhập
			if (this.state.is_logged_in) {
				this.loadUserChatHistory();
			} else {
				// Nếu user chưa đăng nhập, hiển thị tin nhắn mặc định
				NFChatUI.renderMessages(this.state.messagesById[name] || []);
			}

			NFChatUI.focusInput();
		},

		loadUserChatHistory: function(){
			if (!this.state.is_logged_in) return;

			var tokenMeta = document.querySelector('meta[name="csrf-token"]');
			var csrf = tokenMeta ? tokenMeta.getAttribute('content') : undefined;

			// Gọi API để lấy lịch sử chat của user
			fetch('/chatbot/messages', {
				method: 'GET',
				headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
				credentials: 'same-origin'
			}).then(function(r){ return r.json(); }).then(function(res){
				// Cập nhật trạng thái đăng nhập
				NFChat.state.is_logged_in = res.is_logged_in || false;

				// Cập nhật session_code nếu có
				if (res.session_code) {
					NFChat.state.session_code = res.session_code;
					// Lưu session_code vào localStorage
					localStorage.setItem('nova_chat_session', res.session_code);
				}

				if (res.messages && res.messages.length > 0) {
					// Hiển thị lịch sử chat
					NFChatUI.renderMessages(res.messages);

					// Lưu vào state
					var name = NFChat.state.active;
					if (name) {
						NFChat.state.messagesById[name] = res.messages;
					}
				} else {
					// Nếu không có lịch sử, hiển thị tin nhắn mặc định
					var name = NFChat.state.active;
					NFChatUI.renderMessages(NFChat.state.messagesById[name] || []);
				}
			}).catch(function(){
				// Nếu có lỗi, hiển thị tin nhắn mặc định
				var name = NFChat.state.active;
				NFChatUI.renderMessages(NFChat.state.messagesById[name] || []);
			});
		},

		loadChatHistory: function(){
			if (!this.state.session_code || !this.state.is_logged_in) return;

			var tokenMeta = document.querySelector('meta[name="csrf-token"]');
			var csrf = tokenMeta ? tokenMeta.getAttribute('content') : undefined;

			fetch('/chatbot/messages?session_code=' + this.state.session_code, {
				method: 'GET',
				headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
				credentials: 'same-origin'
			}).then(function(r){ return r.json(); }).then(function(res){
				// Cập nhật trạng thái đăng nhập
				NFChat.state.is_logged_in = res.is_logged_in || false;

				if (res.messages && res.messages.length > 0) {
					// Cập nhật session_code nếu có
					if (res.session_code) {
						NFChat.state.session_code = res.session_code;
					}

					// Hiển thị tin nhắn từ lịch sử
					NFChatUI.renderMessages(res.messages);

					// Lưu vào state
					var name = NFChat.state.active;
					if (name) {
						NFChat.state.messagesById[name] = res.messages;
					}
				} else {
					// Nếu không có lịch sử hoặc session không hợp lệ, xóa session cũ
					if (res.session_code === null || res.session_invalid) {
						NFChat.clearSession();
					}

					// Hiển thị tin nhắn mặc định
					var name = NFChat.state.active;
					NFChatUI.renderMessages(NFChat.state.messagesById[name] || []);
				}
			}).catch(function(){
				// Nếu có lỗi, xóa session cũ và hiển thị tin nhắn mặc định
				NFChat.clearSession();
				var name = NFChat.state.active;
				NFChatUI.renderMessages(NFChat.state.messagesById[name] || []);
			});
		},
		send: function(){
			var draft = NFChatUI.getDraft().trim();
			if(!draft || !this.state.active){ return; }

			// Kiểm tra lại trạng thái đăng nhập trước khi gửi
			this.checkLoginStatus();

			var list = this.state.messagesById[this.state.active] || (this.state.messagesById[this.state.active] = []);
			list.push({ role:'user', text:draft });
			NFChatUI.appendMessage(draft, 'user');
			NFChatUI.clearDraft();
			var uiSend = NFChatUI.els.send; if(uiSend){ uiSend.disabled = true; }

			// Hiển thị typing indicator
			NFChatUI.showTyping();

			// Call backend
			var tokenMeta = document.querySelector('meta[name="csrf-token"]');
			var csrf = tokenMeta ? tokenMeta.getAttribute('content') : undefined;
			var payload = { message: draft, session_code: this.state.session_code };
			fetch('/chatbot/messages', {
				method: 'POST', 
				headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }, 
				credentials: 'same-origin',
				body: JSON.stringify(payload)
			}).then(function(r){ return r.json(); }).then(function(res){
				// Ẩn typing indicator
				NFChatUI.hideTyping();

				// Cập nhật trạng thái đăng nhập
				NFChat.state.is_logged_in = res.is_logged_in || false;

				// Cập nhật session_code nếu có
				if (res.session_code) {
					NFChat.state.session_code = res.session_code;
					// Lưu session_code vào localStorage nếu user đăng nhập
					if (res.is_logged_in) {
						localStorage.setItem('nova_chat_session', res.session_code);
					}
				} else {
					// Nếu không có session_code, xóa session cũ
					NFChat.clearSession();
				}

				if(res.messages && res.messages[1]){ NFChat.receive(res.messages[1].text || ''); }

				// Bật lại nút send
				if(uiSend){ uiSend.disabled = false; }
			}).catch(function(){
				// Ẩn typing indicator khi có lỗi
				NFChatUI.hideTyping();

				NFChat.receive('Xin lỗi, hiện mình không thể phản hồi. Vui lòng thử lại sau.');

				// Bật lại nút send
				if(uiSend){ uiSend.disabled = false; }
			});
		},
		receive: function(text){
			var name = this.state.active; if(!name) return;
			(this.state.messagesById[name]||[]).push({ role:'bot', text:text });
			NFChatUI.appendMessage(text, 'bot');
		}
	};

	function ready(fn){ if(document.readyState!=='loading'){ fn(); } else { document.addEventListener('DOMContentLoaded', fn); } }
	ready(function(){ if(document.getElementById('nf-chatbot')){ NFChat.init(); } });
})();


