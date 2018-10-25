Implemented in Laravel.<br />
Developed in localhost, with XAMPP.<br />
<br />
POST parameters have to be named "data" and their values - given as JSON strings.<br />
Responses are returned in a JSON format.<br />
<br />
<br />
**Endpoints**<br />
<br />
POST /public/api/payment<br />
GET /public/api/charge<br />
POST /public/api/charge<br />
GET /public/api/charge/{id}<br />
<br />
<br />
**Important files**<br />
<br />
/app/Http/Controllers/ApiPaymentController.php<br />
/app/Models/Interfaces/PaymentInterface.php<br />
/app/Models/CCPayment.php<br />
/app/Models/DDPayment.php<br />
/routes/api.php<br />
/config/database.php (database config)<br />
/.env (not commited here, but needed for database config and others)<br />
/test_api_payment.sql (database creation script)<br />
