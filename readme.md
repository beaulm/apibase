RESTful CRUD API
================
All calls to the API will start with `/api/v1/`  
All responses will be in JSON  
If any response other than 200 is given, the JSON will be an array with a `code` and `message` element

Login:
------
- URL: `/api/v1/login`
- Method: `ANY`
- Params: `username`, `password` OR `token`
- Response: `token`  
_*All subsequent requests will require that token._  

Example: `/api/v1/login?username=3333334444&password=fake`  
Ex Return: `{"data":{"token":"12d3ff","user":{"id":2,"username":"3333334444","name":"Beau Lynn-Miller","phone":"3333334444","email":"beaulm@gmail.com","created_at":"2014-10-17 10:16:58","updated_at":"2014-10-17 10:17:00","deleted_at":null,"last_offered":null}},"timestamp":"2014-10-17 10:57:05","hashes":{"gpsConfig":"422962d8","vehicles":"86e45d7c","towingCompanies":"011500b9","states":"738ee935"}}`


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


Error:
-------
Making a call without a token will result in an error:

Example: `/api/v1/user`  
Ex Return: `{"code":401,"message":"You do not have access to view this web page"}`