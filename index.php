<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <!-- Brand -->
        <title>One Billion Lights</title>
        <link rel="icon" href="lightbulb.ico" type="image/icon type">
    
        <!-- Bootstrap v5.0.2 CDN CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
              
        <!-- Font Awesome v6.0.0 CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Google APIs -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <!-- Google Fonts : Quicksand -->
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap" rel="stylesheet">

        <!-- Customization -->
        <style>
            #bg-video {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
            }
            body {
                background: #FEF5ED;
                font-family: 'Quicksand', sans-serif;
            }
            .shade {
                box-shadow: 0px 0 10px rgba(0, 0, 0, 0.8);
            }
        </style>

    </head>

    <body>
        
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light py-3 shade">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">One Billion Lights</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#OBL-Navigation" aria-controls="OBL-navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            
                <div class="collapse navbar-collapse" id="OBL-Navigation">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item pe-2"><a class="nav-link active" href="#">Home</a></li>
                        <li class="nav-item pe-2"><a class="nav-link" href="#">About</a></li>
                        <li class="nav-item pe-2"><a class="nav-link" href="#">Contact</a></li>
                        <li class="nav-item pe-2 dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="OBL-User-Drop" data-bs-toggle="dropdown">You</a>
                            <ul class="dropdown-menu" aria-labelledby="OBL-User-Drop">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Progress 25%
                                        <div class="progress mt-2" style="height: 5px">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                    <a href="connect" class="btn btn-primary py-2">Sign Up</a>
                </div>
            </div>
        </nav>

        <div class="fixed-bottom py-3 text-center shade">
            &copy; Copyright : <a href="#" style="text-decoration: none">One Billion Lights</a> ( v1.0.1 )
        </div>

        <!-- Bootstrap v5.0.2 CDN JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

        <!-- jQuery v3.6.0 CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>            

    </body>

</html>