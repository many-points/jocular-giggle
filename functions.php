<?php

class LolCats {

  function db_connect($servername, $username, $password, $dbname) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $this->error_check($conn);
    mysqli_set_charset($conn, 'utf8');
    $this->conn = $conn;
    return $conn;
  }

  function get_posts() {
    $query = "SELECT * FROM posts ORDER BY id DESC";
    $result = mysqli_query($this->conn, $query);
    $this->error_check($result);
    return $result;
  }

  function post_comment($name, $email, $post) {
    $name  = trim($name);
    $email = trim($email);
    $post  = trim($post);
    if (strlen($name) == 0 || strlen($post) == 0) {
      die('Invalid input');
    }
    if ( !preg_match("/^(.+@.+)?$/", $email) ) {
      die('Invalid email');
    }
    $name  = mysqli_real_escape_string($this->conn, $name);
    $email = mysqli_real_escape_string($this->conn, $email);
    $post  = mysqli_real_escape_string($this->conn, $post);
    $email = strlen($email) ? "'{$email}'" : "NULL" ;
    $query = "INSERT INTO posts (name, email, post) VALUES ('{$name}', {$email}, '{$post}')";
    $result = mysqli_query($this->conn, $query);
    $this->error_check($result);
    return $result;
  }

  function delete_post($id) {
    $id = mysqli_real_escape_string($this->conn, $id);
    $query = "DELETE FROM posts WHERE id = {$id}";
    $result = mysqli_query($this->conn, $query);
    $this->error_check($result);
    return $result;
  }

  function login($email, $password) {
    $email    = trim($email);
    $password = trim($password);
    if (strlen($email) == 0 || strlen($password) == 0) {
      die('invalid input');
    }
    if (!preg_match("/^.+@.+$/", $email)) {
      die('invalid email');
    }
    $email = mysqli_real_escape_string($this->conn, $email);
    $query = "SELECT * FROM users WHERE email = '{$email}'";
    $result = mysqli_query($this->conn, $query);
    $this->error_check($result);
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      $_SESSION['id'] = $row['id'];
      $_SESSION['name'] = $row['name'];
      $_SESSION['email'] = $row['email'];
    } else {
      die('wrong password');
    }
  }

  function logout() {
    session_destroy();
  }

  function register($email, $name, $password, $repeat_password) {
    $email           = trim($email);
    $name            = trim($name);
    $password        = trim($password);
    $repeat_password = trim($repeat_password);
    if (strlen($email) == 0 || strlen($name) == 0 || strlen($password) == 0 || strlen($repeat_password) == 0) {
      die('invalid input');
    }
    if (!preg_match("/^.+@.+$/", $email)) {
      die('invalid email');
    }
    if ($password != $repeat_password) {
      die('passwords don\'t match');
    }
    $email = mysqli_real_escape_string($this->conn, $email);
    $name = mysqli_real_escape_string($this->conn, $name);

    $query = "SELECT * FROM users WHERE name = '{$name}'";
    $result = mysqli_query($this->conn, $query);
    $this->error_check($result);
    if ($result->num_rows != 0) {
      die("User with that name already exists");
    }

    $query = "SELECT * FROM users WHERE name = '{$email}'";
    $result = mysqli_query($this->conn, $query);
    $this->error_check($result);
    if ($result->num_rows != 0) {
      die("User with that email already exists");
    }

    $password = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO users (name, email, password) VALUES ('{$name}', '{$email}', '{$password}')";
    $result = mysqli_query($this->conn, $query);
    $this->error_check($result);
    return $result;
  }

  function error_check($result) {
    if (!$result) {
      die("Mysql error: " . mysqli_error($this->conn));
    }
  }

} ?>
