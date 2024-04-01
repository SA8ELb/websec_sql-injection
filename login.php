<?php

session_start();

$errors = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $errors = notCorrectAuth($_POST['username'], $_POST['password']);

  if($errors == '') {
      $_SESSION['logged_in'] = true;
      header('Location: index.php');
      exit;
  } 
}

echo head();
echo login($errors);
echo footer();

function head()
{
    return ' <!DOCTYPE html>
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
                     <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
                   </a>
           
                   <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                     <li><a href="index.php" class="nav-link px-2 text-secondary">Home</a></li>
                   </ul>
           
           
                   <div class="text-end">
                     <a type="button" class="btn btn-outline-light me-2" href="login.php">Login</a>
                   </div>
                 </div>
               </div>
             </header>';
}

function login(string $__errors)
{
    $form = '<main class="form-signin container" style="width: 300px;margin-top: 50px;">
            <form class="text-center" method = "post" action = "login.php">
              <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
          
              <div class="form-floating">
                <input type="username" class="form-control" id="floatingInput" name="username">
                <label for="floatingInput">Username</label>
              </div>
              <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="password">
                <label for="floatingPassword">Password</label>
              </div>
          
              <div class="mb-3"></div>
              <button class="w-100 btn btn-lg btn-primary" name = "enter" value = "1">Sign in</button>';
    if ($__errors !== '') {
        $form .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">'. $__errors .'</div>';
    }
    $form .= '</form>
            </main>';
    
    return $form;
}

function footer()
{
    return '    </body>
            </html>';
}

function notCorrectAuth(string $__login, string $__password)
{
    $db = new SQLite3('./database/news_db.db');

    $query = "SELECT * FROM USERS WHERE username='$__login' AND password='$__password'";
    $result = $db->query($query);
    
        // Проверяем, есть ли содержимое в результате запроса
    if ($row = $result->fetchArray()) {
      $_SESSION["username"] = $row['username'];
      $_SESSION["user_id"] = $row['id'];
      return '';
    } else {
      return'wrong username or password';
    }
}