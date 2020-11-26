<?php
// Set HttpOnly
ini_set('session.cookie_httponly', 1);
// Session start
SESSION_START();

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Frontend CORS With Session</title>
</head>

<body>
    <h1>Frontend CORS With Session</h1>

    <div class="container">
        <div class="row">
            <div class="col-2">
                Frontend session id
            </div>
            <div class="col-4">
                <?= session_id(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                Backend session id
            </div>
            <div class="col-4 backend-session-id">
            </div>
        </div>
    </div>

    <br>

    <h3>Backend Session ID is fixed!</h3>

    <br>

    <button type="button" class="btn btn-primary" onClick="window.location.reload();">Reload Page</button>
    <button type="button" class="btn btn-primary open-backend">Open Backend</button>

    <!-- jQuery and Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- My Javascript -->
    <script type="application/javascript" src="/assets/js/app.js"></script>
</body>

</html>