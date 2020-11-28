CORS With Session
===

<!-- TOC -->

- [CORS With Session](#cors-with-session)
- [1. Description](#1-description)
  - [1.1. CORS](#11-cors)
  - [1.2. With Credentials](#12-with-credentials)
- [2. Download Example](#2-download-example)
- [3. Environmental](#3-environmental)
  - [3.1. Back-end](#31-back-end)
  - [3.2. Back-end Nginx](#32-back-end-nginx)
  - [3.3. Front-end](#33-front-end)
  - [3.4. Front-end Nginx](#34-front-end-nginx)
  - [3.5. Restart Nginx](#35-restart-nginx)
- [4. Goto Front-end](#4-goto-front-end)
- [5. Settings](#5-settings)
  - [5.1. Setting on Back-end](#51-setting-on-back-end)
    - [5.1.1. Security](#511-security)
    - [5.1.2. CORS](#512-cors)
    - [5.1.3. Session On](#513-session-on)
  - [5.2. Setting on Front-end](#52-setting-on-front-end)
    - [5.2.1. Security](#521-security)
    - [5.2.2. Session On](#522-session-on)
    - [5.2.3. Javascript](#523-javascript)
- [6. Reference](#6-reference)

<!-- /TOC -->

# 1. Description
When the web structure is front-end/back-end isolation, we will find that there is a CORS problem between the front-end and the back-end.

Cookie and Session are used for identification solutions. This solution is supported by servers and browsers, and has many security settings. Therefore, solving CORS and supporting Cookie and Session are of great help to the front-end and back-end separation architecture.

## 1.1. CORS
What is CORS?  
Browsre has a limitation, when the request domain and the endpoint domain are different, it will cause CORS problems. For example, when the website URL is https://frontend.dev.idv and the API server URL is https://backend.dev.idv, the request sent to the API server will be blocked.

We need to add some HTTP Header settings to comply with browser security regulations:

```
Access-Control-Allow-Headers: *
Access-Control-Allow-Origin: https://frontend.dev.idv
Access-Control-Allow-Methods: *
```

> If "*" is not allowed on Headers, we must set "X-Requested-With", "Accept", "Content-Type", "Cookie".

## 1.2. With Credentials
After completing the above settings, we found that the cookie could not be sent to the API server when sending a request, and the session id of each request was different. Therefore, we need to set up XHR Credentials support on the front-end and API server (back-end):

> - The request can be accompanied by cookies or HTTP authentication (Authentication) messages (and expect the response to carry cookies).  
> - When we need to support session, we also need cookie support.

Frontend:  
```
// XMLHttpRequest - Javascript
withCredentials = true;
```

API Server:  
```
// HTTP Header
Access-Control-Allow-Credentials: true
```

> When XHR Credentials is supported, wildcards cannot be used on Access-Control-Allow-Origin

# 2. Download Example
- Clone CORS Example with git

```bash
$ cd /var/www/html/
$ git clone git@github.com:marshung24/cors-with-session.git
```

# 3. Environmental

## 3.1. Back-end
- Project path: /var/www/html/cors-with-session/backend
- Domain name: backend.dev.local

## 3.2. Back-end Nginx
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



## 3.3. Front-end
- Project path: /var/www/html/cors-with-session/frontend
- Domain name: frontend.dev.local

## 3.4. Front-end Nginx
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

## 3.5. Restart Nginx
```bash
$ sudo service nginx restart
```

# 4. Goto Front-end
- Go to front-end [https://frontend.dev.local](https://frontend.dev.local)
- Go to back-end [https://backend.dev.local](https://backend.dev.local)

> **Note: Change the front-end and back-end domain names to the names you set.**

# 5. Settings
Summary of example settings

## 5.1. Setting on Back-end
file: backend/index.php

### 5.1.1. Security
```php
// Set HttpOnly
ini_set('session.cookie_httponly', 1);
```

### 5.1.2. CORS
```php
// Setting CORS Header - If "*" is not allowed, you must set "X-Requested-With", "Accept", "Content-Type", "Cookie".
header('Access-Control-Allow-Headers: *');
// Setting CORS Origin Domain - Usually not allowed "*"
header('Access-Control-Allow-Origin: https://frontend.dev.local');
// Setting CORS Methods
header('Access-Control-Allow-Methods: *');
// Setting CORS XHR Credentials - for Cookie support
header('Access-Control-Allow-Credentials: true');
```

> Note: Origin can only set one domain or use *. If there are multiple sets of Origin, please use $_SERVER['HTTP_ORIGIN'] to filter by yourself before processing

### 5.1.3. Session On
```php
// Session start
SESSION_START();
```

## 5.2. Setting on Front-end
file:  
- frontend/assets/js/app.js
- frontend/index.php

### 5.2.1. Security
```php
// Set HttpOnly
ini_set('session.cookie_httponly', 1);
```

### 5.2.2. Session On
```php
// Session start
SESSION_START();
```

### 5.2.3. Javascript
jQuery AJAX

```javascript
var backendUrl = 'https://backend.dev.local/index.php';

$.ajax({
    url: backendUrl,
    method: 'GET',
    datatype: 'json',
    // Setting CORS XHR Credentials - for Cookie support
    xhrFields: {
      withCredentials: true,
    },
  })
```


# 6. Reference
- [Wiki 跨來源資源共享](https://zh.wikipedia.org/wiki/%E8%B7%A8%E4%BE%86%E6%BA%90%E8%B3%87%E6%BA%90%E5%85%B1%E4%BA%AB)
- [伺服器端存取控制（CORS）](https://developer.mozilla.org/zh-TW/docs/Web/HTTP/Server-Side_Access_Control)
- [跨來源資源共用（CORS）](https://developer.mozilla.org/zh-TW/docs/Web/HTTP/CORS)
- [前後端分離下之使用 session](https://yu-jack.github.io/2019/06/02/ajax-with-session/)