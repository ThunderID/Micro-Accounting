// =======================
// Initialization ========
// =======================
var express		= require('express');
var app			= express();
var bodyParser	= require('body-parser');
var morgan		= require('morgan');
var mongoose	= require('mongoose');

var jwt			= require('jsonwebtoken'); // used to create, sign, and verify tokens
var config		= require('./config'); // get our config file
var Transaction	= require('./app/models/Transaction'); // get our mongoose model
var apiRoutes	= express.Router(); 
	
// =======================
// configuration =========
// =======================
var port 		= 2222; // used to create, sign, and verify tokens
mongoose.connect(config.database); // connect to database
app.set('superSecret', config.secret); // secret variable

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
  	Transaction.find({}, function(err, transactions) {
	res.json(transactions);
  });
});   

// apply the routes to our application with the prefix /api
app.use('/api', apiRoutes);

// =======================
// initial transaction ===
// =======================
app.get('/setup', function(req, res) {

	// create a sample user
	var tlab = new Transaction({ 
		date: '2016-01-01', 
		amount: '50000'
	});

	// save the sample Transaction
	tlab.save(function(err) {
		if (err) throw err;

		console.log('Transaction saved successfully');
		res.json({ success: true });
	});
});


// =======================
// start the server ======
// =======================
app.listen(port);
console.log('Thunder Accounting happens at http://localhost:' + port);

