# REST-Api

This is a scalable PHP REST-Api using Basic HTTP Authentication. Since Basic HTTP Authentication is encoded but NOT encrypted it is highly recommended to use a secure connection (HTTPS) and a strong password.

Do not use this API for handling sensitive data. Go to Symfony or Laravel in this case.

# Table of Contents
1. [Configuration](#conf)  
    - [Set up a MySQL database](#db)  
    - [Launch PHP build in webserver](#serv)
    - [Authentication](#auth)
    - [Set credentials](#cred)
1. [How to use](#use)
    - [Get a collection](#coll)
    - [Get a single item](#item)
    - [Create a new item](#new)
    - [Update an item](#upd)
    - [Delete an item](#del)
1. [HTTP STATUS Codes](#stat)
1. [Adding Middleware](#mdw)
1. [Author](#atr)
1. [License](#mit)

# Configuration <a name="conf"></a>

## Set up a MySQL database <a name="db"></a>
Set up a MySQL server and execute `example_database.sql`. Enter the related MySQL parameters in `settings.php`.

## Launch PHP build in webserver <a name="serv"></a>
```
$ php -S localhost:2400 -t public
```

## Authentication <a name="auth"></a>
Authentication is enabled by default. You can disable it in `settings.php`.

## Set credentials <a name="cred"></a>
The below credentials are currently hard coded in `lib/Authentication` middleware. It's up to you to implement a proper logic.
```php
$username = "rest";
$password = "test";
```

# How to use <a name="use"></a>

## Get a collection <a name="coll"></a>
Usage: `GET domain.tld/[version]/[service]`
```php
$ch = curl_init("http://localhost:2400/v1/Users/");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
```

## Get a single item <a name="item"></a>
Usage: `GET domain.tld/[version]/[service]/[id]`
```php
$ch = curl_init("http://localhost:2400/v1/Users/3");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HEADER, true);

if (!$output = curl_exec($ch)) {
    trigger_error(curl_error($ch));
}

curl_close($ch);
```

## Create a new item <a name="new"></a>
Usage: `POST domain.tld/[version]/[service]`
```php
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

## Update an item <a name="upd"></a>
Usage: `PUT domain.tld/[version]/[service]/[id]`
```php
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

## Delete an item <a name="del"></a>
Usage: `DELETE domain.tld/[version]/[service]/[id]`
```php
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

# HTTP STATUS Codes <a name="stat"></a>
The API responds with the following Status Codes. You can change this to your liking:  

On Success:

- GET: 200 OK
- POST: 201 Created
- PUT: 204 No Content
- DELETE: 205 Reset Content

Otherwise:

- 400 Bad Request
- 401 Unauthorized
- 404 Not Found
- 500 Internal Server Error

# Adding Middleware <a name="mdw"></a>
Below is an example pattern that you can use to build your own middleware. You can create your own middleware by creating a class in the `lib` folder that implements the *IMiddleware* interface:
```php
class YourMiddleware implements IMiddleware {

    public function handle(Request $request, Response $response): void {}
}
```
To inject middleware into another middleware use the constructor of that middleware:
```php
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

# Author <a name="atr"></a>

Martin Wolf

# License <a name="mit"></a>

MIT