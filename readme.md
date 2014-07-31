RESTful CRUD API
================

All calls to the API will start with `/api/v1/`
All responses will be in JSON
If any response other than 200 is given, the JSON will be an array with a `code` and `message` element

Login:
------
- URL: `/api/v1/login`
- Method: `ANY`
- Params: `email`, `password`
- Response: `token`
_*All subsequent requests will require that token._

Example: `/api/v1/login?email=fake@fake.com&password=fake`
Ex Return: `{"token":"12d3ff"}`


Get All:
--------
- URL: `/api/v1/{object}`
- Method: `GET`
- Params: `token`
- Response: array of object data

Example: `/api/v1/user?token=12d3ff`
Ex Return: `[{"id":1,"name":"Fakey McFakerson","email":"fake@fake.com"},{"id":2,"name":"Foo McBarenson","email":"foo@bar.com"}]`


Get Specific:
-------------
- URL: `/api/v1/{object}/{id}`
- Method: `GET`
- Params: `token`
- Response: object data

Example: `/api/v1/user/2?token=12d3ff`
Ex Return: `{"id":2,"name":"Foo McBarenson","email":"foo@bar.com"}`


Create Object:
--------------
- URL: `/api/v1/{object}`
- Method: `POST`
- Params: `token`, fillabe parameters defined in model
- Response: object data

Example: `/api/v1/user?name=Testey&password=test&email=test@test.com&phone=5551234123&token=12d3ff`
Ex Return: `{"id":3,"name":"Testey","email":"test@test.com","phone":"5551234123"}`


Update Object:
--------------
- URL: `/api/v1/{object}/{id}`
- Method: `PUT`
- Params: `token`, fillabe parameters defined in model
- Response: object data

Example: `/api/v1/user/2?phone=5557777777&token=12d3ff`
Ex Return: `{"id":2,"name":"Foo McBarenson","email":"foo@bar.com","phone":"5557777777"}`


Delete Object:
--------------
- URL: /api/v1/{object}/{id}
- Method: `DELETE`
- Params: `token`
- Response: array of remaining objects

Example: `/api/v1/user/1`
Ex Return: `[{"id":2,"name":"Foo McBarenson","email":"foo@bar.com","phone":"5557777777"},{"id":3,"name":"Testey","email":"test@test.com","phone":"5551234123"}]`


Logout:
-------
- URL: `/api/v1/logout`
- Method: `ANY`
- Params: `token`
- Response: JSON with message

Example: `/api/v1/logout?token=12d3ff`
Ex Return: `{"message":"Logout successful"}`