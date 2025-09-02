(function(){
	window.NFChatUI = {
		els: {},
		init: function(){
			this.els.panel = document.getElementById('nf-chatbot');
			this.els.body = this.els.panel ? this.els.panel.querySelector('.nf-chatbot__body') : null;
			this.els.title = this.els.panel ? this.els.panel.querySelector('.nf-chatbot__title') : null;
			this.els.usersList = this.els.panel ? this.els.panel.querySelector('.nf-chatbot__users-list') : null;
			this.els.input = this.els.panel ? this.els.panel.querySelector('.nf-chatbot__input') : null;
			this.els.send = this.els.panel ? this.els.panel.querySelector('.nf-chatbot__send') : null;
		},
		renderMessages: function(messages){
			if(!this.els.body) return;
			this.els.body.innerHTML = '';
			messages.forEach(function(m){
				var wrap = document.createElement('div');
				wrap.className = 'nf-chatbot__message ' + (m.role==='user' ? 'nf-chatbot__message--user' : 'nf-chatbot__message--bot');
				
				if(m.role==='user'){
					// User message: chỉ có bubble, không có avatar
					var bubble = document.createElement('div'); 
					bubble.className = 'nf-chatbot__bubble'; 
					bubble.textContent = m.text;
					wrap.appendChild(bubble);
				} else {
					// Bot message: có avatar "NF" + bubble
					var avatar = document.createElement('div'); 
					avatar.className = 'nf-chatbot__avatar'; 
					avatar.textContent = 'NF';
					var bubble = document.createElement('div'); 
					bubble.className = 'nf-chatbot__bubble'; 
					bubble.textContent = m.text;
					wrap.appendChild(avatar);
					wrap.appendChild(bubble);
				}
				
				NFChatUI.els.body.appendChild(wrap);
			});
			this.scrollToBottom();
		},
		appendMessage: function(text, role){
			if(!this.els.body) return;
			var wrap = document.createElement('div');
			wrap.className = 'nf-chatbot__message ' + (role==='user' ? 'nf-chatbot__message--user' : 'nf-chatbot__message--bot');
			
			if(role==='user'){
				// User message: chỉ có bubble, không có avatar
				var bubble = document.createElement('div'); 
				bubble.className = 'nf-chatbot__bubble'; 
				bubble.textContent = text;
				wrap.appendChild(bubble);
			} else {
				// Bot message: có avatar "NF" + bubble
				var avatar = document.createElement('div'); 
				avatar.className = 'nf-chatbot__avatar'; 
				avatar.textContent = 'NF';
				var bubble = document.createElement('div'); 
				bubble.className = 'nf-chatbot__bubble'; 
				bubble.textContent = text;
				wrap.appendChild(avatar);
				wrap.appendChild(bubble);
			}
			
			this.els.body.appendChild(wrap);
			this.scrollToBottom();
		},
		showTyping: function(){
			if(!this.els.body) return;
			
			// Xóa typing indicator cũ nếu có
			this.hideTyping();
			
			var wrap = document.createElement('div');
			wrap.className = 'nf-chatbot__message nf-chatbot__message--bot nf-chatbot__typing';
			wrap.id = 'nf-typing-indicator';
			
			var avatar = document.createElement('div'); 
			avatar.className = 'nf-chatbot__avatar'; 
			avatar.textContent = 'NF';
			
			var bubble = document.createElement('div'); 
			bubble.className = 'nf-chatbot__bubble nf-chatbot__typing-bubble';
			
			// Tạo typing dots
			var dots = document.createElement('div');
			dots.className = 'nf-typing-dots';
			dots.innerHTML = '<span></span><span></span><span></span>';
			
			bubble.appendChild(dots);
			wrap.appendChild(avatar);
			wrap.appendChild(bubble);
			this.els.body.appendChild(wrap);
			this.scrollToBottom();
		},
		hideTyping: function(){
			if(!this.els.body) return;
			var typingEl = this.els.body.querySelector('#nf-typing-indicator');
			if(typingEl){
				typingEl.remove();
			}
		},
		setTitle: function(name){ if(this.els.title){ this.els.title.textContent = name; } },
		focusInput: function(){ if(this.els.input){ this.els.input.focus(); } },
		getDraft: function(){ return (this.els.input && this.els.input.value) ? this.els.input.value : ''; },
		setDraft: function(v){ if(this.els.input){ this.els.input.value = v || ''; } },
		clearDraft: function(){ this.setDraft(''); },
		scrollToBottom: function(){ if(this.els.body){ this.els.body.scrollTop = this.els.body.scrollHeight; } }
	};
})();


