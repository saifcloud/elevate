var express = require('express');
var router = express.Router();

const multer = require('multer');
const path = require('path');
const fs = require('fs');
const bcrypt = require('bcryptjs');

const  axios = require('axios');
const fetch = require('node-fetch');
const request = require("request");

const moment = require('moment');
// moment().format('YYYY-MM-DD hh:mm:ss');

const multiparty = require("multiparty");


const accessToken = require('../middleware/accessToken');
const {Sequelize, sequelize,User,Category} = require('../models');




router.get('/home',accessToken,async(req,res)=>{
    
    try{
    const user = await User.findOne({where:{id:req.user.user_id}});
    var basicdetails = {
        name:user.name,
        location:user.location
    };

    var my_earning            = {};

    var upcoming_appointments = {};

    var today_appontments     = {};

    var appontments_request   = {};

    return res.json({
        status:true,
        data:{
            basicdetails,
            my_earning,
            upcoming_appointments,
            today_appontments,
            appontments_request
        },
        message:'home page data'});
    }catch(err){
     return res.json({status:false,message:"something is wrong."});
    }
});




router.get('/profile',accessToken,async(req,res) =>{

    try{
     var user = await User.findOne({
         where:{id:req.user.user_id},
         attributes:{exclude:['otp','password','fcm_token','is_deleted','device_token','role','status','createdAt','updatedAt']}
         });
     return res.json({status:true,data:{user},message:"Profile details"});
    }catch(err){
     return res.json({status:false,message:"something is wrong."});
    }
});




var storage = multer.diskStorage({
    destination: function (req, file, cb) {
      // cb(null,'backend/public/documents/')
     cb(null, path.join(__dirname,'../backend/public/images/user/'))
      },
    filename: function (req, file, cb) {
      cb(null, Date.now()+'.png')
    }
  })
  
  
  var upload = multer({ storage: storage })
  
router.post('/profile-update',accessToken,upload.single('image'),async(req,res) =>{
    const {name,phone,bio,experience} = req.body;

    if(!name) return res.json({status:false,message:"name is required."});
    if(!phone) return res.json({status:false,message:"phone is required."});
    if(!bio) return res.json({status:false,message:"bio is required."});
    if(!experience) return res.json({status:false,message:"experience is required."});
    // if(!req.file.filename) return res.json({status:false,message:"image is required."});



    try{
       
     var user = await User.findOne({where:{id:req.user.user_id}});
     user.name       = name;
     user.phone      = phone;
     user.bio        = bio;
     user.experience = experience;
     user.image      = "/backend/public/images/user/"+req.file.filename;
     user.save();

     return res.json({status:true,message:"Profile details updated succesfully."});
    }catch(err){
     return res.json({status:false,message:"something is wrong."});
    }
});



router.post('/change-password',accessToken,async(req,res) => {
    const {old_password,new_password} = req.body;

    if(!old_password) return res.json({status:false,message:'old password is required.'});
    if(!new_password) return res.json({status:false,message:'new password is required.'});

    try{
     var user = await User.findOne({where:{id:req.user.user_id}});

     const match = await bcrypt.compare(old_password, user.password);
     if(match){
        user.password = bcrypt.hashSync(new_password, 10);;
        user.save();

        return res.json({status:true,message:"password has been changed."});
     }
     return res.json({status:false,message:"old password not match."});
    }catch(err){
        console.log(err)
    return res.json({status:false,message:"something is wrong."});
    }
});






var storage = multer.diskStorage({
    destination: function (req, file, cb) {
      // cb(null,'backend/public/documents/')
      cb(null, path.join(__dirname,'../backend/public/images/documents/'))
      },
    filename: function (req, file, cb) {
      cb(null, Date.now()+'.png')
    }
  })
  
  
  var upload = multer({ storage: storage })


router.post('/upload-id',accessToken,upload.single('document'),async(req,res) => {
    // const {} = req.body;
     console.log('okkkkk');
    if(!req.file.filename) return res.json({status:false,message:'image is required.'});
   

    try{
     var user = await User.findOne({where:{id:req.user.user_id}});
     user.document = "/backend/public/images/documents/"+req.file.filename;
     user.save();

      return res.json({status:true,message:"Document uploaded successfully."});
    
   
    }catch(err){
        console.log(err)
    return res.json({status:false,message:"something is wrong."});
    }
});

module.exports = router;