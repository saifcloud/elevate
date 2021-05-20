var express = require('express');
var router = express.Router();

const multer = require('multer');
const path = require('path');
const fs = require('fs');

const  axios = require('axios');
const fetch = require('node-fetch');
const request = require("request");

const moment = require('moment');
// moment().format('YYYY-MM-DD hh:mm:ss');

const multiparty = require("multiparty");


const accessToken = require('../middleware/accessToken');
const {Sequelize, sequelize,User,Category,Service} = require('../models');











router.get('/home',accessToken,async(req,res) =>{
  try{
  const user = await User.findOne({where:{id:req.user.user_id,is_deleted:0}});
  const category = await Category.findAll({where:{status:1,is_deleted:0}});
  return res.json({status:true,data:{lat:user.lat,long:user.long,location:user.location,category:category},message:'categories'});
  }catch(err){
    return res.json({status:false,messge:'Something is wrong.'});
  }

   
  });





router.post('/nearby-coaches',accessToken,async(req,res) =>{
  const {category_id} = req.body;
  if(!category_id) return res.json({status:false,message:"category is required."});
  try{

    
  const user = await User.findOne({where:{id:req.user.user_id,is_deleted:0}});
  const location = sequelize.literal(`ST_GeomFromText('POINT(${user.long} ${user.lat})', 4326)`);
  const coach = await User.findAll({
    where:{role:2,is_deleted:0},
    // attributes: {
    //   include: [[sequelize.fn('ST_Distance_Sphere', sequelize.literal('geolocation'), location),'distance']]
    // },
    // order: 'distance',
    // limit: 10,
    // logging: console.log,
    // attributes:[[sequelize.fn('min', sequelize.col('services.amount')),'max']],
    include:{
            model:Service,
            as:'services',
            where:{'category_id':category_id},
            
    },
    
  });
  
  capsule =[];
  
 
    for(var i=0; i<coach.length; i++ ){

        var ss = await Service.findOne({
          where:{coach_id:coach[i].id},
          attributes:[[sequelize.fn('min', sequelize.col('amount')),'minPrice']],
          raw: true,
        });

        var singlecat = await Category.findOne({
          where:{id:category_id}
        });


        var servicelist = await Service.findAll({
          where:{coach_id:coach[i].id},
          include:{
            model:Category,
            as:'categories'
            
          }
        });
        // console.log(servicelist)
        services =[];
        servicelist.forEach(element => {
          services.push({
            id:element.categories.id,
            category:element.categories.category
          })
        });

      

    capsule.push({
        id:coach[i].id,
        image:coach[i].image,
        name:coach[i].name,
        bio:coach[i].bio,
        rating:5,
        like_status:0,
        far:"3 miles",
        rate:ss.minPrice,
        reviews:10,
        verfied:"YES",
        services:services,
        current_category:singlecat.category
       
        
      })
  };
  
  //  return res.json(coach)
  return res.json({status:true,data:{capsule:capsule},message:'categories'});
  }catch(err){
    console.log(err)
    return res.json({status:false,messge:'Something is wrong.'});
  }

   
});
   
   
   





//profile
router.get('/profile',accessToken,async(req,res) =>{
    try{
      const user = await User.findOne({where:{id:req.user.user_id},attributes:{exclude:['password','forget_password']}});
      if(user) return res.json({status:true,data:{userdetails:user},message:'User details'});
    }catch(err){
      console.log(err)
      return res.json({status:false,messge:'Something is wrong.'});
    }
  });



  var storage = multer.diskStorage({
    destination: function (req, file, cb) {
      cb(null,'backend/public/users/')
    // cb(null, path.join(__dirname,'../../../../public_html/dynaadmin/public/users/'))
   
      
      
     
    },
    filename: function (req, file, cb) {
      cb(null, Date.now()+'.png')
    }
  })
   
  var upload = multer({ storage: storage })
   
  //profile update
  router.post('/profile-update',accessToken,upload.single('image'),async(req,res) =>{
    const {fullname,email,phone,address} = req.body;
    
    // return res.json(req.body)
    
    if(!fullname) return res.json({status:false,'message':'Full nane is required'});
    
    if(!email) return res.json({status:false,'message':'email is required'});
     
    if(!phone) return res.json({status:false,'message':'phone is required'});
    
    try{
        
      const checkphone = await User.findOne({
          where:{
            //   id:req.user.user_id,
              id:{[Sequelize.Op.not]:req.user.user_id},
              phone:phone,
              is_deleted:0}
          
      });
      
       if(checkphone)  return res.json({status:false,message:'Phone number already exist.'});
      
        const checkemail = await User.findOne({
          where:{
              id:{[Sequelize.Op.not]:req.user.user_id},
              email:email,
              is_deleted:0}
          
      });
      
       if(checkemail)  return res.json({status:false,message:'Email already exist.'});
    //   return res.json(checkphone)
     
         
       const user = await User.findOne({where:{id:req.user.user_id}});
       
       if(req.file)
       {
          user.image  = "/public/users/"+req.file.filename;  
       }
        
        user.fullname = fullname;
        user.email = email;
        user.phone = phone;
        user.address = address;
        user.save();
        
        //  return res.json(user);
        return res.json({status:true,message:'User details updated successfully.'});
  
     
    }catch(err){
      console.log(err)
      return res.json({status:false,messge:'Something is wrong.'});
    }
  });

  



module.exports = router;
