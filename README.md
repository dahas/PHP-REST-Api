# REST-Api

This is a scalable PHP REST-Api using Basic HTTP Authentication. Since Basic HTTP Authentication is encoded but NOT encrypted it is highly recommended to use a secure connection (HTTPS) and a strong password.

Do not use this API for handling sensitive data. Go to Symfony or Laravel in this case.

## How to use:

### Set up a MySQL database
Set up a MySQL server and execute `example_database.sql`. Enter the related MySQL parameters in `settings.php`.

### Launch PHP build in webserver
```
$ php -S localhost:2400 -t public
```

### Set credentials
These cresentials are currently hard coded. Implement a proper authentication logic in `lib/Authentication` middleware.
```
$username = "rest";
$password = "test";
```

### Get a collection
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

### Get a single item
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

### Create a new item
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

### Update an item
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

### Delete an item
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

# Author:

Martin Wolf

# License:

MIT