const express = require('express');
const path = require('path');
const bcrypt = require('bcrypt');
const cors=require('cors');
const userSchema = require('./user'); 
const app = express();

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
//cors
app.use(cors({ origin: 'https://kaushal-15.github.io/reader/' }));
// static
app.use(express.static(path.join(__dirname, '..', 'img')));

app.set('view engine', 'ejs');

// Routes
app.get(['/login', '/login.html'], (req, res) => {
    res.render('login');
});

app.get(['/signin', '/signin.ejs', '/signin.html'], (req, res) => {
    res.render('signin');
});

app.get(['/', '/index.html', '/index.ejs'], (req, res) => {
    res.render('index');
});
app.get(['/books', '/books.html'], (req, res) => {  
    res.sendFile(path.join(__dirname, '..', 'views', 'books.html'));
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
// Register user
app.post('/signin', async (req, res) => {
    try {
        const { fullname, email, password } = req.body;

        // Validate required fields
        if (!fullname || !email || !password) {
            return res.status(400).send('All fields are required');
        }

        // Check for existing user by fullName
        const existingUser = await userSchema.findOne({ fullName: fullname });
        if (existingUser) {
            return res.status(400).send('User already exists. Please choose another name.');
        }

        // Hashing 
        const saltRounds = 10; 
        const hashedPassword = await bcrypt.hash(password, saltRounds);

        // Create user data 
        const data = {
            fullName: fullname,
            email: email,
            password: hashedPassword 
        };

        // Save user to the database
        const userdata = await userSchema.create(data);
        console.log('User created:', userdata);
        res.redirect('/books.html'); // Redirect to the desired page after successful registration
    } catch (err) {
        console.error('Error in /signin route:', err);
        res.status(500).send('Error saving user');
    }
});

// Login user
app.post('/login', async (req, res) => {
    try {
        const { email, password } = req.body;

        // Validate required fields
        if (!email || !password) {
            return res.status(400).send('Email and password are required');
        }

        // Find user by email
        const user = await userSchema.findOne({ email: email });
        if (!user) {
            return res.status(400).send('User not found');
        }

        // Compare password
        const passwordMatch = await bcrypt.compare(password, user.password);
        if (passwordMatch) {
            res.redirect('books.html'); 
        } else {
            res.status(400).send('Invalid password');
        }
    } catch (err) {
        console.error('Error in /login route:', err);
        res.status(500).send('Error logging in');
    }
});

const PORT = process.env.PORT || 1515
; 
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});