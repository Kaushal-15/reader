document.getElementById('send-btn').addEventListener('click', sendMessage);
document.getElementById('user-input').addEventListener('keypress', function(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Prevent form submission or line break
        sendMessage();
    }
});

async function sendMessage() {
    const userInput = document.getElementById('user-input').value;

    if (userInput) {
        // Display user input in chat box
        const userMessageDiv = document.createElement("div");
        userMessageDiv.textContent = `YOU: ${userInput}`;
        document.getElementById('chat-box').appendChild(userMessageDiv);

        // Get definition from the API
        const definition = await getWordDefinition(userInput);

        // Display definition in chat box
        const botMessageDiv = document.createElement("div");
        botMessageDiv.textContent = `DICBOT: ${definition}`;
        document.getElementById('chat-box').appendChild(botMessageDiv);

        // Clear input field
        document.getElementById('user-input').value = '';

        // Scroll chat box to the bottom
        document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
    }
}