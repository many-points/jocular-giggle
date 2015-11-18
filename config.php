<?php
  require_once 'functions.php';
  $site = new LolCats();

  $SERVERNAME = 'localhost';
  $USERNAME   = 'root';
  $PASSWORD   = 'fuckoff';
  $DBNAME     = 'database1';

  $ANONS_CAN_POST = true;

  $site->db_connect($SERVERNAME, $USERNAME, $PASSWORD, $DBNAME);
?>
