# REST-Api

This is a scalable PHP REST-Api using Basic HTTP Authentication. Since Basic HTTP Authentication is encoded but NOT encrypted it is highly recommended to use a secure connection (HTTPS) and a strong password.

Do not use this API for handling sensitive data. Go to Symfony or Laravel in this case.

# Configuration

## Set up a MySQL database
Set up a MySQL server and execute `example_database.sql`. Enter the related MySQL parameters in `settings.php`.

## Launch PHP build in webserver
```
$ php -S localhost:2400 -t public
```

## Authentication
Authentication is enabled by default. You can disable it in `settings.php`.

## Set credentials
The below credentials are currently hard coded in `lib/Authentication` middleware. It's up to you to implement a proper logic.
```
$username = "rest";
$password = "test";
```

# How to use

## Get a collection
Usage: `GET domain.tld/[version]/[service]`
```
$ch = curl_init("http://localhost:2400/v1/Users/");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
```

## Get a single item
Usage: `GET domain.tld/[version]/[service]/[id]`
```
$ch = curl_init("http://localhost:2400/v1/Users/3");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
```

## Create a new item
Usage: `POST domain.tld/[version]/[service]`
```
$postData = [
    "name" => "Greta Garbo",
    "age" => "93",
    "city" => "Hollywood",
    "country" => "California"
];
$ch = curl_init("http://localhost:2400/v1/Users");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
```

## Update an item
Usage: `PUT domain.tld/[version]/[service]/[id]`
```
$postData = [
    "name" => "John Rambo",
    "age" => "42",
    "city" => "Seattle",
    "country" => "Washington"
];
$ch = curl_init("http://localhost:2400/v1/Users/4");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
```

## Delete an item
Usage: `DELETE domain.tld/[version]/[service]/[id]`
```
$ch = curl_init("http://localhost:2400/v1/Users/1");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
```

# HTTP STATUS Codes
The API responds with the following Status Codes:  

- GET: 200 OK
- POST: 201 Created
- PUT: 204 No Content
- DELETE: 205 Reset Content  

- 400 Bad Request
- 401 Unauthorized
- 404 Not Found  

- 500 Internal Server Error 

# Adding Middleware
Below is an example pattern that you can use to build your own middleware. You can create your own middleware by creating a class in the `lib` folder that implements the *IMiddleware* interface:
```
class YourMiddleware implements IMiddleware {

    public function handle(Request $request, Response $response): void {}
}
```
To inject middleware into another middleware use the constructor of that middleware:
```
class YourMiddleware implements IMiddleware {

    public function __construct(private IMiddleware $anotherMiddleware) {}

    public function handle(Request $request, Response $response): void
    {
        // 1. Add your logic ...

        // 2. Handle Middleware ...
        $this->anotherMiddleware->handle($request, $response);

        // 3. Do something afterwards ...
    }
}

$anotherMiddleware = new AnotherMiddleware();
$yourMiddleware = new YourMiddleware($anotherMiddleware);
$yourMiddleware->handle($request, $response);
```

# Author:

Martin Wolf

# License:

MIT