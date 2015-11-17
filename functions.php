<?php

class LolCats {

  function db_connect($servername, $username, $password, $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      $this->handle_error("Connection failed: " . $conn->$connect_error);
      return NULL;
    } else {
      mysqli_set_charset($conn, 'utf8');
      $this->conn = $conn;
      return $conn;
    }
  }

  function get_posts() {
    $query = "SELECT * FROM posts ORDER BY id DESC";
    $result = $this->conn->query($query);
    if (!$result) {
      $this->handle_error("Mysql error: " . mysql_error());
      return NULL;
    } else {
      return $result;
    }
  }

  function handle_error($error) {
    $this->error_message .= $error . "\r";
  }

  function post_comment($name, $email, $post) {
    if (strlen($name) == 0 || strlen($post) == 0) {
      $this->handle_error("Invalid input.");
      return;
    }
    if ( strlen($email) == 0 ) {
      $email = NULL;
    } elseif ( !preg_match("/^.+@.+$/", $email) ) {
      return;
    }
    $query = $this->conn->prepare("INSERT INTO posts (name, email, post) VALUES (?, ?, ?)");
    if (!$query) {
      $this->handle_error("Mysql error: " . mysql_error());
      return NULL;
    }
    $query->bind_param('sss', $name, $email, $post);
    $query->execute();
    return $query->result;
  }

}
?>
