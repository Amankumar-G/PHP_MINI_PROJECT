const mongoose = require("mongoose");
const initData = require("./data.js");
const Listing = require("../models/listing.js");



main().then(()=>{ 
    console.log("connect");
}).catch((err)=>{
    console.log(err);
});
async function main(){
    await mongoose.connect("mongodb://localhost:27017/wonderlust");
}

const initDB = async () => {
  await Listing.deleteMany({});
  initData.data=initData.data.map((obj)=>({
...obj,
owner:"66c0a161a8a27c07337304c5",
  }))
  await Listing.insertMany(initData.data);
  console.log("data was initialized");
};

initDB();