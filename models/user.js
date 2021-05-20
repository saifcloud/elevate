'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class User extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate({Service}) {
      // define association here
      this.hasMany(Service,{foreignKey:'coach_id',sourceKey:'id',as:'services'});
      // this.hasOne(Jobsite,{foreignKey:'assigned_project_manager',sourceKey:'id',as:'manager_jobsite'});

    }
  }
  User.init({
    image: DataTypes.STRING,
    name: DataTypes.STRING,
    email: DataTypes.STRING,
    phone: DataTypes.STRING,
    password: DataTypes.STRING,
    bio:DataTypes.STRING,
    document:DataTypes.STRING,
    experience:DataTypes.STRING,
    lat:DataTypes.STRING,
    long:DataTypes.STRING,
    otp: DataTypes.STRING,
    location: DataTypes.STRING,
    fcm_token:DataTypes.STRING,
    device_token:DataTypes.STRING,
    role:DataTypes.INTEGER,
    status: DataTypes.INTEGER,
    is_deleted: DataTypes.INTEGER
  }, {
    sequelize,
    tableName:'users',
    modelName: 'User',
  });
  return User;
};