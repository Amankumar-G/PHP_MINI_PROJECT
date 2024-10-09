const express = require("express");
const wrapasync = require("../utils/wrapasync");
const router = express.Router();
const User=require("../models/user.js")
const passport=require("passport")
const {savedRedirectUrl}= require("../middleware.js")

router.get("/signup",(req,res)=>{
    res.render("users/signup.ejs")
})

router.post("/signup",wrapasync(async(req,res)=>{
try{
let {username,email,password}=req.body;
const newUser= new User({email,username});
const registereduser= await User.register(newUser,password);
req.login(registereduser,(err)=>{
    if(err){
        return next(err);
    }
    req.flash("success","welcome to wonderlust")
res.redirect("/listings")
})
}
catch(e){
    req.flash("error",e.message)
    res.redirect("/signup")
}
}))

router.get("/login",(req,res)=>{
    res.render("users/login.ejs")
})

router.post("/login",
    savedRedirectUrl,
passport.authenticate("local",{
    failureRedirect:"/login",
    failureFlash:true,
})
,async(req,res)=>{
    req.flash("success","you are logged in");
    let redirectUrl=res.locals.redirectUrl || "/listings";
    res.redirect(redirectUrl)
})

router.get("/logout",async(req,res,next)=>{
    req.logout((err)=>{
        if(err){
            return next(err)
        }
        req.flash("success","you are logged out!");
        res.redirect("/listings")
    })
})
module.exports = router;
