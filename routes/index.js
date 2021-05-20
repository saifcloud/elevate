var express = require('express');
var router = express.Router();
const jwt = require('jsonwebtoken');
const bcrypt = require('bcryptjs');
const path = require('path');
const fs = require('fs');
const transporter = require('../middleware/emailSend');
const accessToken = require('../middleware/accessToken');

const multer = require('multer');

const saltRounds = 10;
const {sequelize, User,Category,Service,Setting,Privacy} = require('../models');




//user register
router.post('/register',async(req, res)=>{

  const { name,role,email,phone,password,device_token,fcm_token } = req.body;
	
  if(!name) throw res.json({'status':false,"message":"name is required"});
  if(!email) throw res.json({'status':false,"message":"email is required"});
  if(!phone) throw res.json({'status':false,"message":"phone number is required"});
  if(!password) throw res.json({'status':false,"message":"password  is required"});
  if(!role) throw res.json({'status':false,"message":"role  is required"});
  if(!device_token) throw res.json({'status':false,"message":"device_token number is required"});
  if(!fcm_token) throw res.json({'status':false,"message":"fcm_token  is required"});




  
	try{
	    
    const checkEmail=  await User.findOne({where:{email:email,is_deleted:0}}); 

    if(checkEmail) return res.json({status:false,message:'Email already exist'});

    const checkPhone= await User.findOne({where:{phone:phone,is_deleted:0}});
    
    if(checkPhone) return res.json({status:false,message:'Phone already exist'});  
    
    const hash = bcrypt.hashSync(password, 10);
    const user = await User.create({name,email,phone,password:hash,role,fcm_token,device_token});
    const token = jwt.sign({user_id:user.id},'sssshhhhh');
    

		return res.json({'status':true,'data':{token:token},'message':"User registered successfully."})
	}catch(err){
	    console.log(err)
		return res.json({'status':false,"message":"Something is wrong please try again later"}); 
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

router.post('/more-info',accessToken,upload.single('document'),async(req,res) =>{
  // console.log(req.body)
    const {bio,experience,services,document} = req.body;

    if(!bio) return res.json({status:false,message:"bio is required."});

    if(!experiance) return res.json({status:false,message:"experiance is required."});

    // if(!document) return res.json({status:false,message:"document is required."});
    
    // if(!bio) return res.json({status:false,message:"bio is required."});
    try{

        const user = await User.findOne({where:{id:req.user.user_id}});
        user.bio = bio;
        user.experience=experience;
        user.document="/backend/public/images/documents/"+req.file.filename;
        user.save()
       
        // console.log('555',services[0].subcategory)
       services.forEach(async(item) => {
        // console.log('555',item)
            await Service.create({
            coach_id:user.id,
            category_id:item.subcategory,
            amount:item.amount,
              
            });
        });

        return res.json({status:true,message:"information updated successfully."})
        
    }catch(err){
      // console.log(err)
     return res.json({status:false,message:"something is wrong."});
    }
});


//login
router.post('/login',async(req,res) => {

  const {email,password,lat,long,location} = req.body;

  if(!email) return res.json({status:false,message:'email is required.'});
  if(!password) return res.json({status:false,message:'password is required.'});
  if(!lat) return res.json({status:false,message:'lat is required.'});
  if(!long) return res.json({status:false,message:'long is required.'});
  if(!location) return res.json({status:false,message:'location is required.'});


 try{
   
   const user = await User.findOne({where:{email:email,is_deleted:0}});
   
   if(!user) return res.json({status:false,message:'email and password does not matched.'});
   const match = await bcrypt.compare(password, user.password);
 
    if(match) {

      user.lat = lat;
      user.long= long;
      user.location= location;
      user.save();


      var token = jwt.sign({ user_id:user.id }, 'sssshhhhh');
      return res.json({status:true,data:{token:token,role:user.role},message:'login successfully'})
      }
    
  return res.json({status:false,message:'email and passoword does not matched.'});
  
 }catch(err){
   return res.json({status:false,message:'something is wrong.'});
 }
});



//get category
router.get('/privacy',async(req,res) =>{
  try{
    const privacy = await Privacy.findOne();
    return res.json({status:true,data:{privacy:privacy},message:'privacy'})
  }catch(err){
    console.log(err)
    return res.json({status:false,message:'Something is wrong.'});
  }
});


//get category
router.get('/category',async(req,res) =>{
  try{
    const category = await Category.findAll({where:{status:1,is_deleted:0}});
    return res.json({status:true,data:{category:category},message:'categories'})
  }catch(err){
    console.log(err)
    return res.json({status:false,message:'Something is wrong.'});
  }
});








//forget password email sending
 router.post('/forget-password-mail',async(req,res) =>{
   const {email} = req.body;
   try{
    
    //  return res.json(req.headers.host)
      var token           = '';
      var characters       = 'AssBCaaaD588666654EFGHIJ57855KLMNfsdfffWWWsd551441444WWgdgO1232514444414f4kdksf44ds5703PQRSTUVW7XYZabcd87686efghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for ( var i = 0; i < 40; i++ ) {
        token += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      
     const user = await User.findOne({where:{email:email,is_deleted:0}});
    //  return res.json(user)
     if(!user) return res.json({status:false,message:'user not found.'});
     user.forget_password =token;
     user.save();
     
     const mailOption={
       to:email,
       from:'project@cloudwapp.in',
       subject:'Forget Password',
       html:'<b>Reset Password</b></h4>' +
       '<p>To reset your password, by clicking on below link:</p>' +
       '<a href=https://' + req.headers.host + '/Dynamate/#/setpassword/' + token + '">Click here to reset password</a>' +
       '<br><br>' +
       '<p>--Team</p>'
     }
    const sendEmail = await transporter.sendMail(mailOption);
    
    // return res.json(sendEmail)
    return res.json({status:true,message:'Email has been sent to your email account.'})
    console.log(sendEmail)
   }catch(err){
    console.log(err)
     return res.json({status:false,message:'Something is wrong try,again.'});
   }
 })



 router.get('/setpassword/:token',async(req,res) =>{
   const {token } = req.params;
   try{
      const user = await User.findOne({
        where:{forget_password:token,is_deleted:0}
      });
      if(!user) return res.json({status:false,message:'Invalid Token or Token Expired'});

      return res.json({status:true,data:{forget_password_token:user.forget_password},message:'Token is matched'});
   }catch(err){
   console.log(err)
   return res.json({status:false,message:'Something is wrong try,again.'});
   }
 });




 router.post('/setpassword/:token',async(req,res) =>{
  const {token } = req.params;
  const {new_password} =req.body;
  try{
     const user = await User.findOne({
       where:{forget_password:token,is_deleted:0}
     });
    //  if(!user) return res.json({status:false,message:'Invalid Token or Token Expired'});
    const hash = bcrypt.hashSync(new_password, saltRounds);
    user.password = hash;
    // user.forget_password="";
    user.save();

     return res.json({status:true,message:'password has been changed successfully.'});
  }catch(err){
  console.log(err)
  return res.json({status:false,message:'Something is wrong try,again.'});
  }
});


router.get('/contact',accessToken,async(req,res) =>{
    
    
    try{
     var website_email = await  Setting.findOne({where:{field_key:"website_email"}});
     var contact = await  Setting.findOne({where:{field_key:"contact"}});
     var address        = await  Setting.findOne({where:{field_key:"address"}});
   
      return res.json({status:true, data:{
        website_email:website_email.field_value,
        website_contact:contact.field_value,
        address:address.field_value
        },message:'contact data'})

    //  console.log(address+' '+website_email+' '+website_contact)
    }catch(err){ 
      console.log(err)
      return res.json(err)
       return res.json({status:false,message:'Something is wrong try,again.'});
    }
});


module.exports = router;
