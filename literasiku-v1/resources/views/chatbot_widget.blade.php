<!-- Chatbot Widget -->
<div id="chatbot-widget" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <!-- Chat Button -->
    <button id="chat-toggle" style="width: 60px; height: 60px; border-radius: 50%; background: #003366; color: white; border: none; cursor: pointer; box-shadow: 0 10px 25px rgba(0, 51, 102, 0.3); display: flex; align-items: center; justify-content: center; transition: 0.3s; position: relative;">
        <i class="fas fa-robot" id="chat-icon" style="font-size: 24px;"></i>
        <div id="chat-notif" style="position: absolute; top: 0; right: 0; width: 15px; height: 15px; background: #ff4757; border-radius: 50%; border: 2px solid white; display: none;"></div>
    </button>

    <!-- Chat Window -->
    <div id="chat-window" style="position: absolute; bottom: 80px; right: 0; width: 350px; height: 450px; background: white; border-radius: 20px; box-shadow: 0 15px 50px rgba(0,0,0,0.15); display: none; flex-direction: column; overflow: hidden; border: 1px solid rgba(0, 51, 102, 0.1); animation: chatFadeIn 0.3s ease-out;">
        <!-- Header -->
        <div style="background: #003366; color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 35px; height: 35px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 14px;">Librarian AI</div>
                    <div style="font-size: 10px; opacity: 0.8; display: flex; align-items: center; gap: 4px;">
                        <span style="width: 6px; height: 6px; background: #2ecc71; border-radius: 50%;"></span> Online
                    </div>
                </div>
            </div>
            <button id="close-chat" style="background: transparent; border: none; color: white; cursor: pointer; font-size: 18px; opacity: 0.7;">&times;</button>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" style="flex: 1; padding: 20px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 12px;">
            <!-- Welcome Message -->
            <div style="align-self: flex-start; background: white; padding: 12px 16px; border-radius: 15px 15px 15px 2px; font-size: 13px; color: #334155; box-shadow: 0 2px 5px rgba(0,0,0,0.02); max-width: 85%; line-height: 1.5; border: 1px solid #edf2f7;">
                Halo! Saya <strong>Librarian AI</strong>. 👋<br><br>
                Ada buku yang ingin Anda cari? Tanyakan saja pada saya, misalnya: <em>"Cari buku pemrograman PHP"</em>
            </div>
        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="typing-indicator" style="padding: 10px 20px; font-size: 11px; color: #64748b; background: #f8fafc; display: none; align-items: center; gap: 5px;">
            <i class="fas fa-circle-notch fa-spin"></i> Librarian sedang mengetik...
        </div>

        <!-- Input Area -->
        <div style="padding: 15px; background: white; border-top: 1px solid #edf2f7;">
            <div style="display: flex; gap: 10px; background: #f1f5f9; padding: 8px 15px; border-radius: 12px;">
                <input type="text" id="chat-input" placeholder="Tulis pesan..." style="flex: 1; background: transparent; border: none; outline: none; font-size: 13px; color: #334155;">
                <button id="send-chat" style="background: transparent; border: none; color: #003366; cursor: pointer; display: flex; align-items: center;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes chatFadeIn {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    #chat-toggle:hover { transform: scale(1.1) rotate(5deg); }
    .user-msg {
        align-self: flex-end;
        background: #003366;
        color: white;
        padding: 10px 14px;
        border-radius: 15px 15px 2px 15px;
        font-size: 13px;
        max-width: 85%;
        box-shadow: 0 4px 10px rgba(0, 51, 102, 0.1);
    }
    .bot-msg {
        align-self: flex-start;
        background: white;
        padding: 10px 14px;
        border-radius: 15px 15px 15px 2px;
        font-size: 13px;
        color: #334155;
        max-width: 85%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        border: 1px solid #edf2f7;
        line-height: 1.5;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatToggle = document.getElementById('chat-toggle');
        const chatWindow = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-chat');
        const chatMessages = document.getElementById('chat-messages');
        const typingIndicator = document.getElementById('typing-indicator');

        // Toggle chat window
        chatToggle.onclick = () => {
            const isVisible = chatWindow.style.display === 'flex';
            chatWindow.style.display = isVisible ? 'none' : 'flex';
            if (!isVisible) chatInput.focus();
        };

        closeChat.onclick = () => chatWindow.style.display = 'none';

        // Send Message
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            // Add user message
            addMessage(message, 'user');
            chatInput.value = '';

            // Show typing
            typingIndicator.style.display = 'flex';
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                const response = await fetch('{{ route("chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                
                // Hide typing
                typingIndicator.style.display = 'none';
                
                // Add bot response
                addMessage(data.response, 'bot');

            } catch (error) {
                console.error('Chat Error:', error);
                typingIndicator.style.display = 'none';
                addMessage('Maaf, saya sedang ada gangguan teknis. Coba lagi nanti ya!', 'bot');
            }
        }

        function addMessage(text, type) {
            const div = document.createElement('div');
            div.className = type === 'user' ? 'user-msg' : 'bot-msg';
            div.innerHTML = text.replace(/\n/g, '<br>');
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        sendBtn.onclick = sendMessage;
        chatInput.onkeypress = (e) => {
            if (e.key === 'Enter') sendMessage();
        };
    });
</script>
