async function getWordDefinition(word) {
    const apiKey = "AIzaSyDq1FXcYc7meASaWWL933BShx4u5f-ScGQ"; // Replace with your actual Gemini API key
    const url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${apiKey}`;

    const requestBody = {
        contents: [{
            parts: [{ text: `Define the word: ${word}` }]
        }]
    };

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestBody)
        });

        const data = await response.json();

        // Extract response text
        if (data && data.candidates && data.candidates[0].content && data.candidates[0].content.parts) {
            return `Definition of "${word}": ${data.candidates[0].content.parts[0].text}`;
        } else {
            return "Error: No definition found.";
        }
    } catch (error) {
        console.error("Error:", error);
        return "Error fetching word meaning.";
    }
}