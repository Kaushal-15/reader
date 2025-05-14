const express = require('express');
const app = express();
const path = require('path');
const PORT = process.env.PORT || 1516;

//static files
app.use(express.static(path.join(__dirname, 'img')));
// Serve the index page at '/' and '/index'
app.get(['/', '/index','/index.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'index.html'));
});

// Serve the login page at '/login' and '/login.html'
app.get(['/login', '/login.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'login.html'));
});

// Redirect '/signin' and '/signin.html' to '/login'
app.get(['/signin', '/signin.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'signin.html'));
});

app.get(['/books', '/books.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'books.html'));
});

app.get(['/library', '/library.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'library.html'));
});

app.get(['/pdfviewer', '/pdfviewer.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'pdfviewer.html'));
});

app.get(['/upload', '/upload.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'upload.html'));
});

app.get(['/ai.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'ai.html'));
});

app.get(['/dd.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'dd.html'));
});

app.get(['/dict.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'dict.html'));
});

app.get(['/flash.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'flash.html'));
});

app.get(['/mcq.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'mcq.html'));
});

app.get(['/progress.html','/progress'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'progress.html'));
});

app.get(['/view.html'], (req, res) => {
    res.sendFile(path.join(__dirname, 'views', 'view.html'));
});
// Catch-all for 404
app.get('*', (req, res) => {
    res.status(404).sendFile(path.join(__dirname, 'views', '404.html'));      
});

app.listen(PORT, () => console.log(`SERVER RUNNING ON PORT ${PORT}`));
