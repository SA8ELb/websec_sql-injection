<?php

session_start();

if (isset($_POST['logout'])){
    $_SESSION['username'] = '';
    $_SESSION['user_id'] = '';
    $_SESSION['logged_in'] = false;
}

if (isset($_POST['delete'])){
    if($_POST['delete'] == 'flag'){
        $flag = 'Websec{W3l1_D0n3_Y0u_D1d_It}';
    }
    else {
        $flag = '';
    }
    echo '
    <script>
        alert("newsletter deleted\n'. $flag .'")
    </script>';
}

$user_id = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : "";

echo head();
echo news($user_id);

function head()
{
    $head =' 
    <!DOCTYPE html>
    <html>
       <head>
           <meta charset="utf-8">
           <meta http-equiv="X-UA-Compatible" content="IE=edge">
           <title>SQL-Injection</title>
           <meta name="viewport" content="width=device-width, initial-scale=1">
           <link rel="stylesheet" href="./css/bootstrap.min.css">
       </head>
       <body >
           <header class="p-3 bg-dark text-white">
               <div class="container">
                 <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                   <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                     <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                        <use xlink:href="#bootstrap">
                        </use>
                     </svg>
                   </a>
           
                   <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                     <li>
                        <a href="index.php" class="nav-link px-2 text-secondary">
                            Home
                        </a>
                     </li>
                   </ul>';
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        $head .=' 
                        <div class="text-end">
                            <form method="post" action = "index.php">
                                Hello, '. $_SESSION['username'] .'&nbsp;
                                <button class="btn btn-outline-light me-2" name="logout">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>';
    }
    else {
        
        $head .='
                        <div class="text-end">
                            <a type="button" class="btn btn-outline-light me-2" href="login.php">Login</a>
                        </div>
                    </div>
                </div>
            </header>';
    }
           
           
                   

    return $head;
}

function news(string $__user_id)
{
    $news = '';

    $db = new SQLite3('./database/news_db.db');

    $query = 'SELECT * FROM NEWS';


    $result = $db->query($query);
    
    while ($row1 = $result->fetchArray()) {
        
        $news .= 
        '<div class="row " style="width: 800px; margin: 50px auto;">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">'. $row1['title'] .'</h5>
                        <p class="card-text">'. $row1['text']. '</p>
                        <form method="post" action = "index.php">
                            <a href="#" class="btn btn-primary">Go somewhere</a>';
        if($__user_id == $row1['author_id']) {
                    $value = ($row1['id'] != 1) ? $row1['id'] : 'flag';
                    $news .= '
                            <button class="btn btn-outline-danger" name=delete value = "'.$value.'">delete newsletter</button>
                        </form>';       
        }
        $news .='
                    </div>
                </div>
            </div>';
        
        if (!$row2 = $result->fetchArray()) {
            break;
        }
        $news .= 
        '   <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">'. $row2['title'] .'</h5>
                    <p class="card-text">'. $row2['text']. '</p>
                    <form method="post" action = "index.php">
                        <a href="#" class="btn btn-primary">Go somewhere</a>';
        if($__user_id == $row2['author_id']) {
            $value2 = ($row2['id'] != 1) ? $row2['id'] : 'flag';
            $news .= '
                        <button class="btn btn-outline-danger" name=delete value="'.$value2.'">delete newsletter</button>
                    </form>';       
        }
        $news .='
                    </div>
                </div>
            </div>
        </div>';
    }


    return $news;
}

function footer()
{
    return '    </body>
            </html>';
}
