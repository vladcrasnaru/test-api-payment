Implemented in Laravel.  
Developed in localhost, with XAMPP.

POST parameters have to be named "data" and their values - given as JSON strings.
Responses are returned in a JSON format.


**Endpoints**

POST /public/api/payment
GET /public/api/charge
POST /public/api/charge
GET /public/api/charge/{id}


**Important files**

/app/Http/Controllers/ApiPaymentController.php
/app/Models/Interfaces/PaymentInterface.php
/app/Models/CCPayment.php
/app/Models/DDPayment.php
/routes/api.php
/config/database.php (database config)
/.env (not commited here, but needed for database config and others)
/test_api_payment.sql (database creation script)
