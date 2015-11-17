<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
  <?
    header("Location: http://lolcats.dev/");
    function post_comment($conn, $name, $email, $post) {
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
  ?>
  </body>
</html>
