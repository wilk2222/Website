// scripts.js
document.addEventListener("DOMContentLoaded", function () {
    const chatbotInputForm = document.getElementById("chatbot-input-form");
    const userInput = document.getElementById("user-input");
    const chatbotMessages = document.getElementById("chatbot-messages");
    const botIcon = document.getElementById("bot-icon");
    const chatbot = document.getElementById("chatbot");
    //const initialMessage = document.getElementById("initial-message");

    function addMessage(message, sender) {
        const newMessage = document.createElement("div");
        newMessage.classList.add("message");
        newMessage.classList.add(sender);

        const messageText = document.createElement("span");
        messageText.classList.add("message-text");
        messageText.textContent = message;

        newMessage.appendChild(messageText);
        chatbotMessages.appendChild(newMessage);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    async function sendToServer(message) {
        const requestOptions = {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: message })
        };

        try {
            const response = await fetch("chatbot/chatbot.php", requestOptions);
            const data = await response.json();
            return data.reply;
        } catch (error) {
            console.error("Error:", error);
            return "Sorry, there was an error processing your request.";
        }
    }

    function playNotificationSound() {
        const audio = new Audio("chatbot/bot_sound.mp3"); // Replace with the actual path to your sound file
        audio.play().catch(error => console.log(error));
    }

    let playedSound = false;
    botIcon.addEventListener("click", function () {
        if (chatbot.style.display === "none" || chatbot.style.display === "") {
          chatbot.style.display = "flex";
        } else {
          chatbot.style.display = "none";
        }

        if (!playedSound) {
            playNotificationSound();
            addMessage("Hello, how can I help you?", "bot");
            playedSound = true;
        }
        //initialMessage.style.opacity = "0";
    });

    setTimeout(function () {
        //initialMessage.style.opacity = "1";
    }, 3000);

    chatbotInputForm.addEventListener("submit", async function (event) {
        event.preventDefault();
        const message = userInput.value;
        if (message.trim() === "") return;

        addMessage(message, "user");
        userInput.value = "";

        const reply = await sendToServer(message);
        addMessage(reply, "bot");
        playNotificationSound();
    });
});
