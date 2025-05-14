const mongoose = require('mongoose');

const userSchema=new mongoose.Schema({
    name:String,
    age:{
        type:Number,
        min:1,
        max:130,
    },
    email:{
        type:String,
        required:true,
        lowercase:true,
        unique:true,
    },
    password:String,
    createdAt:{
        type:Date,
        immutable:true,
        default:()=>Date.now()
    }
})

userSchema.methods.sayHi=function(){
    console.log(`Hi my name is ${this.name}`);
}

module.exports=mongoose.model("User",userSchema);