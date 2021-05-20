'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Service extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate({Category}) {
      // define association here
       this.hasOne(Category,{foreignKey:'id',sourceKey:'category_id', as:'categories'});
    }
  };
  Service.init({
    coach_id: DataTypes.INTEGER,
    category_id: DataTypes.INTEGER,
    amount: DataTypes.DOUBLE,
    status: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Service',
    tableName:"services"
  });
  return Service;
};