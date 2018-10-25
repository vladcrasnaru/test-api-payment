# Glofox Recruitment Backend Case #

For this test you need to create an API with the programming language and the database of your choice, this API must support the following endpoints:

### Create Payment
----
  Returns json data of the payment created.

* **URL**  /payment

* **Method:**  `POST`
  
* **Data Params**  Payment JSON Object

* **Success Response:**

    * **Code:** 200
    * **Content:** Payment JSON Object
 
* **Error Response:**

    * **Code:** 400 INVALID PARAMETERS
    * **Content:** `{ "message" : "Error message", "code": 400 }`

### Get List of Charges
----
  Returns an array of charges.

* **URL** /charge

* **Method:** `GET`
  
* **Data Params** None

* **Success Response:**

    * **Code:** 200
    * **Content:** `[{"payment_id":1,"amount":12},{"payment_id":2,"amount":55}]`

### Create Charge
----
  Returns json data of the charge created. For this method we should consider that the amount saved in the database will depend on the type of payment that we are going to use. If the payment type is direct debit (dd) we should add 7% to the given amount and if the payment type is credit card (cc) we should add 10% to the given amount. The calculation of the amount for direct debit and credit card must be written in different classes and both classes must implement an interface that will enforce a method called charge.

* **URL** /charge

* **Method:** `POST`
  
* **Data Params** Charge JSON Object

* **Success Response:**

    * **Code:** 200
    * **Content:** Charge JSON Object
 
* **Error Response:**

    * **Code:** 400 INVALID PARAMETERS
    * **Content:** `{ "message" : "Error message", "code": 400 }`

### Get Charge By Id
----
  Returns a charge given the id.

* **URL** /charge/{id}

* **Method:** `GET`
*  **URL Params**  `id=[string]`

* **Data Params** None

* **Success Response:**

    * **Code:** 200
    * **Content:** `{"payment_id":1,"amount":12}`

* **Error Response:**
    * **Code:** 404 NOT FOUND
    * **Content:** `{ error : "Charge doesn't exist" }`

----
### Payment Object
```javascript
	{
		"id":    "string",
		"name:   "string",
		"type":  "string", // cc or dd
		"iban":  "string", // only for dd
		"expiry: "date",   // only for cc
		"cc":    "string", // only for cc
		"ccv":   "string"  // only for cc
	}
```

### Charge Object
```javascript
	{
		"id":         "string",
		"payment_id:  "string",
		"amount":     "float"
	}
```