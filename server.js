// =======================
// Initialization ========
// =======================
var express		= require('express');
var app			= express();
var bodyParser	= require('body-parser');
var morgan		= require('morgan');

var jwt			= require('jsonwebtoken'); // used to create, sign, and verify tokens
var config		= require('./config'); // get our config file
var amqp 		= require('amqplib/callback_api');
var apiRoutes	= express.Router(); 

// =======================
// configuration =========
// =======================
var port 		= 2220; // used to create, sign, and verify tokens
var Sequelize 	= require("sequelize");
var sequelize 	= new Sequelize('postgres://postgres:thunder@localhost:5432/microaccount');
var Transaction = sequelize.define('transaction', {
					date: {
						type: Sequelize.STRING,
					},
					amount: {
						type: Sequelize.STRING,
					},
				}, { freezeTableName: true }); // Model tableName will be the same as the model name: true // Model tableName will be the same as the model name

app.set('superSecret', config.secret); // secret variable
amqp.connect('amqp://localhost', function(err, conn) {}); //connect message broker

// use body parser so we can get info from POST and/or URL parameters
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

// use morgan to log requests to the console
app.use(morgan('dev'));
	
// =======================
// middleware ============
// =======================
// route middleware to verify a token
apiRoutes.use(function(req, res, next) {

	// check header or url parameters or post parameters for token
	var token = req.body.token || req.query.token || req.headers['x-access-token'];

	// decode token
	if (token) {

		// verifies secret and checks exp
		jwt.verify(token, app.get('superSecret'), function(err, decoded) {      
			if (err) {
				return res.json({ success: false, message: 'Failed to authenticate token.' });    
			} 
			else {
				// if everything is good, save to request for use in other routes
				req.decoded = decoded;
				next();
			}
		});
	} 
	else 
	{
		// if there is no token
		// return an error
		return res.status(403).send({ 
			success: false, 
			message: 'No token provided.' 
		});
	}
});

// =======================
// routes ================
// =======================
app.get('/', function(req, res) { // basic route
	res.send('Hello! The API is at http://localhost:' + port + '/api');
});

// API ROUTES -------------------

// route to show a random message (GET http://localhost:2222/api/)
apiRoutes.get('/', function(req, res) {
  res.json({ message: 'Welcome to the coolest API on earth!' });
});

// route to return all transactions (GET http://localhost:2222/api/transactions)
apiRoutes.get('/transactions', function(req, res) {
	Transaction.findAndCountAll().then(function (transactions) {
	    res.json(transactions.rows);
	});
});   

// apply the routes to our application with the prefix /api
app.use('/api', apiRoutes);

// =======================
// initial transaction ===
// =======================
app.get('/setup', function(req, res) {
	Transaction
		.build({ date: '2016-01-01', amount: '50000'})
		.save()
		.then(function(anotherTransaction) {
		// you can now access the currently saved Transaction with the variable anotherTransaction... nice!
			console.log('Transaction saved successfully');
		}).catch(function(error) {
		// Ooops, do some error-handling
			console.log('Transaction not saved');
		});
});

// =======================
// handling queue ========
// =======================
amqp.connect('amqp://localhost', function(err, conn) {
	conn.createChannel(function(err, ch) {
		var ex 	= 'thunderpayment';

		ch.assertExchange(ex, 'fanout', {durable: false});

		ch.assertQueue('', {exclusive: true}, function(err, q) {
			console.log(" [*] Waiting for messages in %s. To exit press CTRL+C", q.queue);
			ch.bindQueue(q.queue, ex, '');

			ch.consume(q.queue, function(msg) {
				var payment	= JSON.parse(msg.content.toString());

				// var Transaction = sequelize.define('transaction', {
				// 	date: {
				// 		type: Sequelize.STRING,
				// 	},
				// 	amount: {
				// 		type: Sequelize.STRING,
				// 	},
				// }, { freezeTableName: true }); // Model tableName will be the same as the model name: true // Model tableName will be the same as the model name

				Transaction
					.build({ date: payment.date, amount: payment.amount })
					.save()
					.then(function(anotherTransaction) {
					// you can now access the currently saved task with the variable anotherTask... nice!
						console.log('Transaction saved successfully');
					}).catch(function(error) {
					// Ooops, do some error-handling
						console.log(error);
					});

				console.log(" [x] %s", payment.amount);
			}, {noAck: true});
		});
	});
});


// =======================
// start the server ======
// =======================
app.listen(port);
console.log('Thunder Accounting happens at http://localhost:' + port);

