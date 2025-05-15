const mongoose = require('mongoose');
const connect= mongoose.connect('mongodb://localhost:27017/reader');
connect.then(() => {
    console.log('Connected to the database');
}
).catch(err => {
    console.error('Database connection error:', err);
});


const userSchema = new mongoose.Schema({
    fullName: { type: String, required: true },
    email:    { type: String, required: true, unique: true },
    password: { type: String, required: true }
});

module.exports = mongoose.model('User', userSchema);