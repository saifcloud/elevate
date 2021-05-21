'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class review extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate({User}) {
      // define association here
      this.hasOne(User,{foreignKey:'id',sourceKey:'user_id',as:'user'});
    }
  };
  review.init({
    coach_id: DataTypes.INTEGER,
    user_id: DataTypes.INTEGER,
    rating: DataTypes.STRING,
    comment: DataTypes.TEXT,
    status: DataTypes.INTEGER,
    is_deleted: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Review',
    tableName:'reviews'
  });
  return review;
};