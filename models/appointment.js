'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class appointment extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate({User,Category,Service}) {
      // define association here
      this.hasOne(User,{foreignKey:'id',sourceKey:'coach_id',as:'coach'});
      this.hasOne(Category,{foreignKey:'id',sourceKey:'category_id',as:'category'});
      // this.hasOne(Service,{foreignKey:'category_id',sourceKey:'category_id',as:'services'});
    }
  };
  appointment.init({
    user_id: DataTypes.INTEGER,
    coach_id: DataTypes.INTEGER,
    booking_date: DataTypes.DATE,
    duration: DataTypes.STRING,
    category_id: DataTypes.INTEGER,
    amount: DataTypes.DOUBLE,
    transaction_id: DataTypes.STRING,
    payment_status: DataTypes.INTEGER,
    status: DataTypes.INTEGER,
    is_deleted: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Appointment',
    tableName:'appointments'
  });
  return appointment;
};