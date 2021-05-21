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
const {Sequelize, sequelize,User,Category,Service,Review,Appointment,Card,Favourite} = require('../models');











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
          where:{coach_id:coach[i].id,category_id:category_id},
          // attributes:[[sequelize.fn('min', sequelize.col('amount')),'minPrice']],
          // raw: true,
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

    var fav = await Favourite.findOne({where:{coach_id:coach[i].id,user_id:req.user.user_id}});

    var rating = await Review.findOne({
      where:{coach_id:coach[i].id},
      attributes: [
         [Sequelize.fn('AVG', Sequelize.col('rating')), 'avgRating'],
         [Sequelize.fn('COUNT', Sequelize.col('rating')), 'review'],
        ],
      raw: true,
    });

    capsule.push({
        id:coach[i].id,
        image:coach[i].image,
        name:coach[i].name,
        bio:(coach[i].bio) ? coach[i].bio:'',
        rating: (rating.avgRating) ? parseFloat(rating.avgRating).toFixed(2):0,
        favourite_status:(fav) ? 1:0,
        far:"3 miles",
        rate:ss.amount,
        reviews:rating.review,
        verfied:"YES",
        services:services,
        current_category:singlecat.category
       
        
      })
  };
  
  //  return res.json(coach)
  return res.json({status:true,data:{capsule:capsule},message:'categories'});
  }catch(err){
    console.log(err)
    return res.json({status:false,message:'Something is wrong.'});
  }

   
});
   
   
   



  // review
  router.post('/add-review',accessToken,async(req,res) =>{
    const { rating,coach_id,comment}  = req.body;
    if(!rating) return res.json({status:false,message:"rating is required."});
    if(!coach_id) return res.json({status:false,message:"coach id is required."});
    // if(!comment) return res.json({status:false,message:"comment is required."});
     try{
      const review = await Review.create({
            coach_id,
            user_id:req.user.user_id,
            rating,
            comment
      });

      return res.json({status:true,message:"Review added successfully."});
     }catch(err){
      console.log(err)
      return res.json({status:false,message:'Something is wrong.'});
     }
  });

  

  // get review
  router.post('/get-reviews',accessToken,async(req,res) =>{
    const { coach_id }  = req.body;
    if(!coach_id) return res.json({status:false,message:"coach id is required."});
     try{
      const review = await Review.findAll({
        where:{coach_id},
        include:{
          model:User,
          as:'user'
        } 
      });
      
      var reviews = [];
      // review.forEach(element => {


      for(var i=0; i < review.length; i++){

      // var fav = await Favourite.findOne({where:{coach_id:coach_id,user_id:req.user.user_id}});



     
      reviews.push({

          id:review[i].user.id,
          image:review[i].user.image,
          name:review[i].user.name,
          rating:review[i].rating,
          comment:review[i].comment,
          // favourite_status:(fav) ? 1:0

        });
      }

      // });

      return res.json({status:true,data:{reviews:reviews},message:"Reviews list"})
     }catch(err){
      console.log(err)
      return res.json({status:false,message:'Something is wrong.'});
     }
  });


  // get appointment
  router.post('/appointment-schedule',accessToken,async(req,res) =>{
    const { coach_id,booking_date,duration,category_id }  = req.body;

    if(!coach_id) return res.json({status:false,message:"coach id is required."});
    if(!booking_date) return res.json({status:false,message:"booking date is required."});
    if(!duration) return res.json({status:false,message:"duration is required."});
    if(!category_id) return res.json({status:false,message:"category_id is required."});


     try{
     
      const service = await Service.findOne({
        where:{coach_id,category_id,is_deleted:0},
        include:[
          {model:Category,as:'categories'},
          {model:User,as:'user'}
        ]
       });
        
       var btime = duration.split(":"); 
       var feeHour  = parseFloat(service.amount) * parseInt(btime[0]);
       var feeMin   = (parseInt(btime[1])==30) ? parseFloat(service.amount/2):0;
       var finalAmount = (parseFloat(feeHour)+parseFloat(feeMin)).toFixed(2);
      //  return res.json(finalAmount)
      
      var rating = await Review.findOne({
        where:{coach_id:service.user.id},
        attributes: [
           [Sequelize.fn('AVG', Sequelize.col('rating')), 'avgRating'],
           [Sequelize.fn('COUNT', Sequelize.col('rating')), 'review'],
          ],
        raw: true,
      });

      data1 = {
        coach_id:service.user.id,
        coach_image:service.user.image,
        coach_name:service.user.name,
        rating:(rating.avgRating) ? parseFloat(rating.avgRating).toFixed(2):0,
        booking_date:booking_date,
        duration:duration,
        amount:finalAmount,
        category_id:service.categories.id,
        category:service.categories.category
      };
      

      return res.json({status:true,data:{appointment:data1},message:"Booking Summary"})
     }catch(err){
      console.log(err)
      return res.json({status:false,message:'Something is wrong.'});
     }
  });




  router.post('/make-appointment',accessToken,async(req,res) =>{
    const {coach_id,booking_date,duration,category_id,amount} = req.body;

    if(!coach_id) return res.json({status:false,message:"coach id is required."});
    if(!booking_date) return res.json({status:false,message:"booking date is required."});
    if(!duration) return res.json({status:false,message:"duration is required."});
    if(!category_id) return res.json({status:false,message:"category_id is required."});
    // if(!coach_id) return res.json({status:false,message:"coach id is required."});


    try{
      const check_appointment = await Appointment.findOne({
        where:{ 
        coach_id,
        user_id:req.user.user_id,
        booking_date,
        duration,  
        category_id,
        status:1
       }
      });

      if(check_appointment) return  res.json({status:false,message:"You have already same appointment."});

      const appointment = await Appointment.create({
        coach_id,
        user_id:req.user.user_id,
        booking_date,
        duration,  
        category_id,
        amount     
      });

      return res.json({status:true,message:"Appointment has been submitted successfully."});
    }catch(err){
      console.log(err)
      return res.json({status:false,message:'Something is wrong.'});
    }
  });



// card save

  router.post('/save-card',accessToken,async(req,res) =>{
    const {name_on_card,card_number,expire_date} = req.body;

    if(!name_on_card) return res.json({status:false,message:"name on card is required."});
    if(!card_number) return res.json({status:false,message:"card number is required."});
    if(!expire_date) return res.json({status:false,message:"expire date is required."});
    // if(!category_id) return res.json({status:false,message:"category_id is required."});
    // if(!coach_id) return res.json({status:false,message:"coach id is required."});


    try{
      const check_card = await Card.findOne({
        where:{ 
          user_id:req.user.user_id,
          name_on_card,
          card_number,
          is_deleted:0
       }
      });

      // return res.json(check_card)

      if(check_card) return res.json({status:false,message:"This card already added."});

      const card = await Card.create({
        user_id:req.user.user_id,
        name_on_card,
        card_number,
        expire_date 
      });

      return res.json({status:true,message:"Card saved successfully."});
    }catch(err){
      console.log(err)
      return res.json({status:false,message:'Something is wrong.'});
    }
  });



// card list
  router.get('/card-list',accessToken,async(req,res) =>{
 
    try{
      const check_card = await Card.findAll({
        where:{user_id:req.user.user_id},
        attributes:{exclude:['createdAt','updatedAt']}
      });
     
      return res.json({status:true,data:{cards:check_card},message:"Card list."});
    }catch(err){
      console.log(err)
      return res.json({status:false,message:'Something is wrong.'});
    }
  });



  // my appointments
  router.get('/appointments',accessToken,async(req,res) =>{
 
    try{
      const appointment = await Appointment.findAll({
        where:{user_id:req.user.user_id},
        include:[
          {model:User,as:'coach'},
          {model:Category,as:'category'},
          // {model:Service,as:'services'}
        ],
        attributes:{exclude:['createdAt','updatedAt']}
      });
      
      data1 =[];
      // appointment.forEach(async(element) => {
       
        for(var i=0; i< appointment.length; i++){

        var serv = await Service.findOne({
          where:{category_id:appointment[i].category.id,coach_id:appointment[i].coach.id}
        });
        
          var bmsg_status ="";
          var show_chatbox='';

          if(appointment[i].status==1){
          bmsg_status ="Booking pending";
          show_chatbox=0;
          }

          if(appointment[i].status==2){
            bmsg_status ="Booking Accepted";
            show_cshow_chatboxhat=1;
          }

          if(appointment[i].status==3){
            bmsg_status ="Session Ended";
            show_chatbox=0;
          }
          
          var rating = await Review.findOne({
            where:{coach_id:appointment[i].coach.id},
            attributes: [
               [Sequelize.fn('AVG', Sequelize.col('rating')), 'avgRating'],
               [Sequelize.fn('COUNT', Sequelize.col('rating')), 'review'],
              ],
            raw: true,
          });

          data1.push({
              appointment_id:appointment[i].id,
              image:appointment[i].coach.image,
              name:appointment[i].coach.name,
              rating:(rating.avgRating) ? parseFloat(rating.avgRating).toFixed(2):0,
              amount:serv.amount,
              booking_date:appointment[i].booking_date,
              duration:appointment[i].duration,
              amount:appointment[i].amount,
              category:appointment[i].category.category,
              booking_status:bmsg_status,
              show_chatbox:show_chatbox

          });
      }
      // });
      //  return res.json(appointment)
     
      return res.json({status:true,data:{appointments:data1},message:"Appoinments list."});
    }catch(err){
      console.log(err)
      return res.json({status:false,message:'Something is wrong.'});
    }
  });


  router.post('/end-session',accessToken,async(req,res) =>{
    const {appointment_id} = req.body;

    if(!appointment_id) return res.json({status:false,message:"appointment id requried."});
    try{
      
      const appointment = await Appointment.findOne({
        where:{id:appointment_id},
      });

      appointment.status = 3;
      appointment.save();
      
      return res.json({status:true,message:'Session has been ended.'});
    }catch(err){
      console.log(err)
      return res.json({status:false,message:'Something is wrong.'});
    }

  });




  router.get('/profile',accessToken,async(req,res) =>{
    
    try{
     var user = await User.findOne({
         where:{id:req.user.user_id},
         attributes:{exclude:['bio','otp','password','fcm_token','is_deleted','device_token','role','status','createdAt','updatedAt']}
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
    const {name,phone} = req.body;

    if(!name) return res.json({status:false,message:"name is required."});
    if(!phone) return res.json({status:false,message:"phone is required."});
    // if(!bio) return res.json({status:false,message:"bio is required."});
    // if(!experience) return res.json({status:false,message:"experience is required."});
    // if(!req.file.filename) return res.json({status:false,message:"image is required."});



    try{
       
     var user = await User.findOne({where:{id:req.user.user_id}});
     user.name       = name;
     user.phone      = phone;
     user.image      = "/backend/public/images/user/"+req.file.filename;
     user.save();

     return res.json({status:true,message:"Profile details updated succesfully."});
    }catch(err){
     return res.json({status:false,message:"something is wrong."});
    }
});



// add-to-favourite
router.post('/add-to-favourite',accessToken,async(req,res) =>{
   const { coach_id,category_id }  = req.body;
   if(!coach_id) return res.json({status:false,message:"coach id is required."});
   if(!category_id) return res.json({status:false,message:"category id is required."});
  try{

    const favcehck = await Favourite.findOne({where:{
      coach_id,
      user_id:req.user.user_id
    }});
    if(favcehck){
      favcehck.destroy();
      return res.json({status:true,message:"removed from favourite."});
    }else{
      const fav = await Favourite.create({
        coach_id,
        user_id:req.user.user_id,
        category_id:category_id
      });
     
      return res.json({status:true,message:"added in favourite."});
    }
   
  }catch(err){
    console.log(err)
    return res.json({status:false,message:'Something is wrong.'});
  }
});



// favourite list
router.get('/favourite-list',accessToken,async(req,res) =>{
  
 try{
   const fav = await Favourite.findAll({
      where:{user_id:req.user.user_id},
      include:{model:User,as:'coach'}
   });
   
  
  

   var favdata = [];
   for(var i =0; i<fav.length; i++){



    var servicelist = await Service.findAll({
      where:{coach_id:fav[i].coach_id},
      include:{
        model:Category,
        as:'categories'
        
      }
    });
  
     services =[];
     servicelist.forEach(element => {
       services.push({
         id:element.categories.id,
         category:element.categories.category
       })
     });
     

     var fav1 = await Favourite.findOne({where:{coach_id:fav[i].coach.id,user_id:req.user.user_id}});

     var rating = await Review.findOne({
      where:{coach_id:fav[i].coach.id},
      attributes: [ [Sequelize.fn('AVG', Sequelize.col('rating')), 'avgRating']],
      raw: true,
    });
    

    var ss = await Service.findOne({
      where:{coach_id:fav[i].coach.id,category_id:fav[i].category_id},
      // attributes:[[sequelize.fn('min', sequelize.col('amount')),'minPrice']],
      // raw: true,
    });

    
    favdata.push({
     coach_id:fav[i].coach.id,
     image:fav[i].coach.image,
     name:fav[i].coach.name,
     bio:fav[i].coach.bio,
     category_id:fav[i].category_id,
     rating:(rating.avgRating) ? parseFloat(rating.avgRating).toFixed(2):0,
     favourite_status:(fav1) ? 1:0,
     far:"3 miles",
     rate:ss.amount,
     reviews:10,
     verfied:"YES",
     services:services,
    });
   }
  
   return res.json({status:true,data:{favourite_list:favdata},message:"favourite list."});
 }catch(err){
   console.log(err)
   return res.json({status:false,message:'Something is wrong.'});
 }
});
module.exports = router;
