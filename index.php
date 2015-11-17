<? if($_SERVER['REQUEST_METHOD'] == 'POST') { header("Location: http://lolcats.dev/"); } ?>
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
    <?php
      $SERVERNAME = 'localhost';
      $USERNAME   = 'root';
      $PASSWORD   = 'fuckoff';
      $DBNAME     = 'database1';

      function db_connect($servername, $username, $password, $dbname) {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
          die("Connection failed " . $conn->$connect_error);
        }
        mysqli_set_charset($conn, 'utf8');
        echo "Connected successfully.";
        return $conn;
      }

      function get_posts($conn) {
        $query = "SELECT * FROM posts ORDER BY id DESC";
        $result = $conn->query($query);
        if (!$result) {
          die("Query failed " . mysql_error());
        } else {
          echo "Posts query OK.";
          return $result;
        }
      }

      function post_comment($conn, $name, $email, $post) {
        if (strlen($name) == 0 || strlen($post) == 0) {
          echo "Invalid input";
          return;
        }
        if ( strlen($email) == 0 ) {
          $email = NULL;
        }
        $query = $conn->prepare("INSERT INTO posts (name, email, post) VALUES (?, ?, ?)");
        $query->bind_param('sss', $name, $email, $post);
        $query->execute();

        if (!$query) {
          die(" Query failed " . mysql_error());
        } else {
          echo " Comment posted.";
          return $result;
        }
      }
    ?>
    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <ul class="list-group">
              <li class="list-group-item">
                <a href="/"><img id="cat" src="cat.jpg" height="476" width="524"></a>
              </li>
            </ul>
          </div>
          <div class="col-md-6 debug">
            <ul class="list-group">
              <li class="list-group-item">
                <h4> <? $conn = db_connect($SERVERNAME, $USERNAME, $PASSWORD, $DBNAME); ?> <h4>
              </li>
              <li class="list-group-item">
                <h4>
                  <?
                    $posts = get_posts($conn);
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                      post_comment($conn, $_POST['name'], $_POST['email'], $_POST['post']);
                    }
                  ?>
                </h4>
              </li>
              <li class="list-group-item">
                <form role="form" class="form-horizontal" action="index.php" method="post">
                  <div class="form-group">
                    <div class="col-md-5">
                      <label for='name'>Name</label>
                      <input name="name"  type="text" class="form-control" id='name' placeholder="Name">
                    </div>
                    <div class="col-md-5">
                      <label for='email'>Email</label>
                      <input name="email" type="text" class="form-control" id='email' placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12" for="post">Post</label>
                    <div class="col-md-12">
                      <textarea name="post" class="form-control" rows="5" id="post"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <button type="submit" class="btn btn-info pull-right">Submit</button>
                    </div>
                  </div>
                </form>
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
                    <span class="author"><?=$row['name']?></span>
                    <? if ($row['email'] != NULL): ?>
                      <a href="mailto:<?=$row['email']?>"><span class="glyphicon glyphicon-envelope"></span></a>
                    <? endif; ?>
                    <span class="date">on <?=$row['date']?></span>
                  </small></h4>
                  <p><?=$row['post']?></p>
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
