const express = require("express");
const cors = require("cors");
const fs = require("fs");

const app = express();
app.use(cors());
app.use(express.json());

const NOTES_FILE = "notes.json";

// Load existing notes
let notes = [];
if (fs.existsSync(NOTES_FILE)) {
    notes = JSON.parse(fs.readFileSync(NOTES_FILE));
}

// Save a new note
app.post("/save_note", (req, res) => {
    const { pdfUrl, x, y, text } = req.body;
    notes.push({ pdfUrl, x, y, text });
    fs.writeFileSync(NOTES_FILE, JSON.stringify(notes));
    res.send({ success: true });
});

// Get notes for a PDF
app.get("/get_notes", (req, res) => {
    const { pdfUrl } = req.query;
    const filteredNotes = notes.filter(note => note.pdfUrl === pdfUrl);
    res.json(filteredNotes);
});

// Start server
app.listen(3000, () => console.log("Server running on port 3000"));