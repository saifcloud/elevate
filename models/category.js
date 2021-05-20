'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Category extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate() {
      // define association here
      // this.hasMany(Category_size,{foreignKey:'category_id',sourceKey:'id',as:'category_size'});
    }
  };
  Category.init({
    image: DataTypes.STRING,
    category: DataTypes.STRING,
    status: DataTypes.INTEGER,
    is_deleted: DataTypes.INTEGER
  }, {
    sequelize,
    tableName:'categories',
    modelName: 'Category',
  });
  return Category;
};