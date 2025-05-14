const mongoose = require('mongoose');
const User=require('./user')
main().catch(err => console.log(err.message));
async function main() {
    await mongoose.connect('mongodb://localhost:27017/reader');
    console.log('Connected to MongoDB');
//   const user=await User({name:"kaushal",email:"kaushalsavitha@gmail.com",password:"123456"})
    const user = await User.findOne({name:"kaushal"});
    user.sayHi();
    console.log(user);
}



