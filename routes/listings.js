const express = require("express");
const router = express.Router();
const { listingschema } = require("../schema.js")
const wrapasync = require("../utils/wrapasync.js")
const Listing = require("../models/listing.js");
const ExpressError = require("../utils/Expresserror.js")
const { isLoggedIn, isOwner } = require("../middleware.js")
const multer = require("multer")
const { storage } = require("../cloudConfig.js")
const upload = multer({ storage })

let validatelisting = (req, res, next) => {
    let result = listingschema.validate(req.body)
    if (result.error) {
        throw new ExpressError(400, result.error)
    }
    else {
        next();
    }
}

router.get("/", wrapasync(async (req, res) => {
    let alllistings = await Listing.find({});
    res.render("listings/index.ejs", { alllistings });
}))

router.get("/new", isLoggedIn, (req, res) => {
    res.render("listings/new.ejs");
})

router.get("/:id", wrapasync(async (req, res) => {
    let { id } = req.params;
    let listing = await Listing.findById(id)
        .populate({ path: "reviews", populate: { path: "author" } })
        .populate("owner");
    if (!listing) {
        req.flash("error", "listing you requested for does not exists")
        res.redirect("/listings")
    }
    res.render("listings/show.ejs", { listing });
}))

router.post("/", isLoggedIn,upload.single("listing[image]"),  validatelisting, 
    wrapasync(async (req, res, next) => {
        let url= req.file.path;
    const newlisting = new Listing(req.body.listing);
    newlisting.owner = req.user._id;
    newlisting.image=url
    await newlisting.save();
    req.flash("success", "New listing created!");
    res.redirect("/listings");
}))

router.get("/:id/edit", isLoggedIn, isOwner, wrapasync(async (req, res) => {
    let { id } = req.params;
    let listing = await Listing.findById(id);
    if (!listing) {
        req.flash("error", "listing you requested for does not exists")
        res.redirect("/listings")
    }
    let ogurl=listing.image;
    ogurl=ogurl.replace("/upload","/upload/w_250")
    res.render("listings/edit.ejs", { listing,ogurl });
}))

router.put("/:id", isLoggedIn, isOwner,upload.single("listing[image]"), validatelisting, wrapasync(async (req, res) => {
    let { id } = req.params;
   let listing= await Listing.findByIdAndUpdate(id, { ...req.body.listing })
   if(typeof req.file !=="undefined"){
   let url=req.file.path;
   listing.image=url;
   await listing.save();
   }
    req.flash("success", "listing updated!");
    res.redirect(`/listings/${id}`)
}))

router.delete("/:id", isLoggedIn, isOwner, wrapasync(async (req, res) => {
    let { id } = req.params;
    await Listing.findByIdAndDelete(id);
    req.flash("success", "listing deleted!");
    res.redirect("/listings");
}))

module.exports = router;