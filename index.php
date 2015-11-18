<?
  require_once "config.php";
  session_start();
  if($_SERVER['REQUEST_METHOD'] == 'POST' && !$DEBUG) {
    header("Location: http://{$SITENAME}/");
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['submit_post'])) {
      if(isset($_POST['id']) && isset($_SESSION['id']) && $_POST['id'] == $_SESSION['id']) {
        $site->post_comment($_SESSION['name'], isset($_POST['leave_email']) ? $_SESSION['email'] : "", $_POST['post']);
      } else {
        $site->post_comment($_POST['name'], $_POST['email'], $_POST['post']);
      }
    }

    if(isset($_POST['login'])) {
      $site->login($_POST['email'], $_POST['password']);
    }

    if(isset($_POST['logout'])) {
      $site->logout();
    }

    if(isset($_POST['register'])) {
      $site->register($_POST['email'], $_POST['name'], $_POST['password'], $_POST['repeat_password']);
    }

    if(isset($_POST['delete_post'])) {
      if (isset($_SESSION['id']) && $_SESSION['id'] == '1') {
        $site->delete_post($_POST['id']);
      }
    }

  }

  $posts = $site->get_posts();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lolcats Development</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <ul class="list-group">
              <li class="list-group-item">
                <a href="/"><img id="cat" src="cat.png" width="524"></a>
              </li>
              <? if($DEBUG): ?>
              <li class="list-group-item">
                <h4>
                  <?
                    var_dump($_POST);
                    var_dump($_SESSION);
                  ?>
                </h4>
              </li>
              <? endif; ?>
            </ul>
          </div>
          <div class="col-md-6 debug">
            <ul class="list-group">
              <li class="list-group-item">
                <? if($_SESSION && isset($_SESSION['id'])): ?>
                  <p>You are logged in as <?=htmlentities($_SESSION['name'])?></p>
                  <form id="logout-form" role="form" class="form-horizontal" action="index.php" method="post">
                    <div class="form-group">
                      <div class="col-md-5">
                        <button type="submit" name="logout" class="btn btn-warning">Logout</button>
                      </div>
                    </div>
                  </form>
                <? else: ?>
                  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#login-form">Login form</button>
                  <form id="login-form" role="form" class="form-horizontal collapse" action="index.php" method="post">
                    <div class="form-group">
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px; margin-top: 12px;">Email</p>
                        <input name="email" type="text" class="form-control" id='email' placeholder="Email">
                      </div>
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px; margin-top: 12px;">Password</p>
                        <input name="password"  type="text" class="form-control" id='password' placeholder="password">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <button type="submit" name="login" class="btn btn-primary pull-right">Submit</button>
                      </div>
                    </div>
                  </form>
                <? endif; ?>
              </li>
              <? if($_SESSION && isset($_SESSION['id'])): ?>
              <? else: ?>
                <li class="list-group-item">
                  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#registration-form">Reg form</button>
                  <form id="registration-form" role="form" class="form-horizontal collapse" action="index.php" method="post">
                    <div class="form-group">
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px; margin-top: 12px;">Email</p>
                        <input name="email" type="text" class="form-control" id='email' placeholder="Email">
                      </div>
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px; margin-top: 12px;">Name</p>
                        <input name="name"  type="text" class="form-control" id='name' placeholder="Name">
                      </div>
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px; margin-top: 12px;">Password</p>
                        <input name="password"  type="text" class="form-control" id='password' placeholder="password">
                      </div>
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px; margin-top: 12px;">Repeat password</p>
                        <input name="repeat_password" type="text" class="form-control" id='repeat_password' placeholder="password">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <button type="submit" name="register" class="btn btn-primary pull-right">Submit</button>
                      </div>
                    </div>
                  </form>
                </li>
              <? endif; ?>
              <li class="list-group-item">
                <? if($_SESSION && isset($_SESSION['id'])): ?>
                  <form role="form" class="form-horizontal" action="index.php" method="post">
                    <input name="id" type="hidden" value="<?=$_SESSION['id']?>">
                    <div class="form-group">
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px">Post as <?=htmlentities($_SESSION['name'])?></p>
                      </div>
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px"><input name="leave_email" type="checkbox" checked> Leave email</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <textarea name="post" class="form-control" rows="5" id="post"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <button type="submit" name="submit_post" class="btn btn-primary pull-right">Submit</button>
                      </div>
                    </div>
                  </form>
                <? elseif($ANONS_CAN_POST): ?>
                  <form role="form" class="form-horizontal" action="index.php" method="post">
                    <div class="form-group">
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px">Post as</p>
                        <input name="name"  type="text" size="5" class="form-control" id='name' placeholder="Name">
                      </div>
                      <div class="col-md-5">
                        <p style="margin-bottom: 2px">Email (optional)</p>
                        <input name="email" type="text" class="form-control" id='email' placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <textarea name="post" class="form-control" rows="5" id="post" placeholder="Enter text here!"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <button type="submit" name="submit_post" class="btn btn-primary pull-right">Submit</button>
                      </div>
                    </div>
                  </form>
                <? else: ?>
                  <p style="margin-bottom: 2px">Register to post here!</p>
                <? endif; ?>
              </li>
            </ul>
          </div>
        </div>

        <div class="row">
          <div class="span-md-12">
            <ul class="list-group">
              <? while ($row = $posts->fetch_assoc()): ?>
                <li class="list-group-item">
                  <h4><small>
                    <span class="author"><?=htmlentities($row['name'])?></span>
                    <? if ($row['email'] != NULL): ?>
                      <a href="mailto:<?=htmlentities($row['email'])?>"><span class="glyphicon glyphicon-envelope"></span></a>
                    <? endif; ?>
                    <span class="date">wrote on <?=$row['date']?></span>
                  </small></h4>
                  <p><?=nl2br(htmlentities($row['post']))?></p>
                  <? if ($_SESSION['id'] == '1'): ?>
                    <div style="position: absolute; right: 10px; bottom: 10px;">
                      <form action="index.php" method="post">
                        <input name="id" type="hidden" value="<?=$row['id']?>">
                        <button type="submit" name="delete_post"class="btn btn-danger">Delete</button>
                      </form>
                    </div>
                  <? endif; ?>
                </li>
              <? endwhile; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <script src="jquery-2.1.4.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
