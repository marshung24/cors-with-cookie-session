# CORS With Session

## Description
When the web structure is front-end/back-end isolation, we will find that there is a CORS problem between the front-end and the back-end.

Cookie and Session are used for identification solutions. This solution is supported by servers and browsers, and has many security settings. Therefore, solving CORS and supporting Cookie and Session are of great help to the front-end and back-end separation architecture.

## Installation
- Clone with git

```bash
$ cd /var/www/html/
$ git clone git@github.com:marshung24/cors-with-session.git
```

## Environmental

### Back-end
- Project path: /var/www/html/cors-with-session/backend
- Domain name: backend.dev.local

### Nginx
$ sudo vim /etc/nginx/sites-enabled/backend.conf

```bash
# Redirect http => https
server {
    listen 80 default_server;
    listen [::]:80 default_server;

    server_name _;

    return 301 https://$host$request_uri;
}

# backend.dev.local
server {
        # SSL configuration
        listen 443 ssl;
        listen [::]:443 ssl;

        root /var/www/html/cors-with-session/backend;

        # Add index.php to the list if you are using PHP
        index index.php index.html;

        server_name backend.dev.local;

        # SSL相關設定
        include common.d.conf/common-ssl.conf;

        location / {
                index  index.php index.html;
                # Redirect everything that isn't a real file to index.php
                try_files $uri $uri/ /index.php$is_args$args;
        }

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        }
}
```



### Front-end
- Project path: /var/www/html/cors-with-session/frontend
- Domain name: frontend.dev.local

### Nginx
$ sudo vim /etc/nginx/sites-enabled/frontend.conf

```bash
# frontend.dev.local
server {
        # SSL configuration
        listen 443 ssl;
        listen [::]:443 ssl;

        root /var/www/html/cors-with-session/frontend;

        # Add index.php to the list if you are using PHP
        index index.php index.html;

        server_name frontend.dev.local;

        # SSL相關設定
        include common.d.conf/common-ssl.conf;

        location / {
                index  index.php index.html;
                # Redirect everything that isn't a real file to index.php
                try_files $uri $uri/ /index.php$is_args$args;
        }

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        }
}
```

### Restart Nginx
```bash
$ sudo service nginx restart
```

## Goto front-end
- Go to front-end [https://frontend.dev.local](https://frontend.dev.local)
- Go to back-end [https://backend.dev.local](https://backend.dev.local)

> **Note: Change the front-end and back-end domain names to the names you set.**

## Setting on back-end
### Security
```php
// Set HttpOnly
ini_set('session.cookie_httponly', 1);
```

### CORS
```php
// Setting CORS Header - If "*" is not allowed, you must set "X-Requested-With", "Accept", "Content-Type", "Cookie".
header('Access-Control-Allow-Headers: *');
// Setting CORS Origin Domain - Usually not allowed "*"
header('Access-Control-Allow-Origin: https://frontend.dev.local');
// Setting CORS Methods
header('Access-Control-Allow-Methods: *');
// Setting CORS xhr credential - for Cookie support
header('Access-Control-Allow-Credentials: true');
```

### Session on
```php
// Session start
SESSION_START();
```

## Setting on front-end
### Security
```php
// Set HttpOnly
ini_set('session.cookie_httponly', 1);
```

### Session on
```php
// Session start
SESSION_START();
```

### Javascript
jQuery AJAX

```javascript
var backendUrl = 'https://backend.dev.local/index.php';

$.ajax({
    url: backendUrl,
    method: 'GET',
    datatype: 'json',
    // Setting CORS xhr credential - for Cookie support
    xhrFields: {
      withCredentials: true,
    },
  })
```


## Reference
- [跨來源資源共用（CORS）](https://developer.mozilla.org/zh-TW/docs/Web/HTTP/CORS)
- [前後端分離下之使用 session](https://yu-jack.github.io/2019/06/02/ajax-with-session/)