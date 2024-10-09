const express = require("express");
const router = express.Router({mergeParams:true});
const {reviewschema}=require("../schema.js")
const Review= require("../models/reviews.js");
const ExpressError=require("../utils/Expresserror.js")
const wrapasync=require("../utils/wrapasync.js")
const Listing= require("../models/listing.js");
const {isLoggedIn,isReviewAuthor}=require("../middleware.js")

let validatereview= (req,res,next)=>{
    let result= reviewschema.validate(req.body)
    if(result.error){
        throw new ExpressError(400,result.error)
    }
    else{
        next();
    }
}

router.post("/",isLoggedIn,validatereview,wrapasync(async(req,res)=>{
    let listing= await Listing.findById(req.params.id);
    let newReview= new Review(req.body.review)
      newReview.author=req.user._id;
    listing.reviews.push(newReview);
    await newReview.save();
    await listing.save();
    // console.log(listing)
    // console.log(newReview)
    req.flash("success","New review created!");
    res.redirect(`/listings/${req.params.id}`)
}))

router.delete("/:reviewId",isReviewAuthor,wrapasync(async (req,res)=>{
    let {id,reviewId}=req.params;
    await Listing.findByIdAndUpdate(id,{$pull:{reviews :reviewId}})
    await Review.findByIdAndDelete(reviewId);
    req.flash("success","review deleted!");
    res.redirect(`/listings/${id}`)
}))

module.exports=router;
