var Sequelize 	= require("sequelize");

var sequelize 	= new Sequelize('postgres://postgres:thunder@localhost:5432/microaccount');

// set up a mongoose model and pass it using module.exports
module.exports 	= sequelize.define('Transaction', {
					date: {
						type: Sequelize.STRING,
					},
					amount: {
						type: Sequelize.STRING,
					},
				  }, { freezeTableName: true }); // Model tableName will be the same as the model name