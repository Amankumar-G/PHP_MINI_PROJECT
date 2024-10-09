if(process.env.NODE_ENV != "production"){
    require("dotenv").config();
}

const express=require("express");
const app=express();
const mongoose= require("mongoose");
const port=5000;
const path =require("path");
const method= require("method-override")
const ejsmate=require("ejs-mate")
const ExpressError=require("./utils/Expresserror.js") 
const listingroutes= require("./routes/listings.js")
const reviewroutes=require("./routes/review.js")
const userroutes=require("./routes/users.js")
const session= require("express-session")
const flash= require("connect-flash")
const passport=require("passport")
const LocalStretagy= require("passport-local")
const User=require("./models/user.js")
const multer=require("multer")
const upload=multer({dest:'uploads/'})

app.set("view engine","ejs");
app.set("views",path.join(__dirname,"views"))
app.use(express.urlencoded({extended:true}));
app.use(method("_method"))
app.engine("ejs",ejsmate)
app.use(express.static(path.join(__dirname,"/public")))

const sessionOption={
    secret:"jay@2082005",
    resave:false,
    saveUninitialized:true,
    cookie:{
        expires:Date.now()+ 7*24*60*60*1000,
        maxAge:7*24*60*60*1000,
        httpOnly:true,
    }
}

main().then(()=>{
    console.log("connect");
}).catch((err)=>{
    console.log(err);
})
async function main(){
    await mongoose.connect("mongodb://localhost:27017/wonderlust")
}
app.get("/",(req,res)=>{
    res.send("server is working");
})

app.use(session(sessionOption))
app.use(flash())

app.use(passport.initialize());
app.use(passport.session());
passport.use(new LocalStretagy(User.authenticate()))

passport.serializeUser(User.serializeUser());
passport.deserializeUser(User.deserializeUser());

app.use((req,res,next)=>{
    res.locals.success=req.flash("success")
    res.locals.error=req.flash("error")
    res.locals.currUser=req.user;
    next()
})

app.use("/listings",listingroutes);
app.use("/listings/:id/reviews",reviewroutes)
app.use("/",userroutes);


app.all("*",(req,res,next)=>{
    next(new ExpressError(404,"page not found"))
})
app.use((err,req,res,next)=>{
    let {statusCode=500,messege="something went wrong !"}= err;
    // res.status(statusCode).send(messege)
    res.render("listings/error.ejs",{messege})
})

app.listen(port,()=>{
    console.log("server is listening on 5000")
})