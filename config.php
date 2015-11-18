<?php
  require_once 'functions.php';
  $site = new LolCats();

  $SERVERNAME = 'localhost';
  $USERNAME   = 'root';
  $PASSWORD   = 'fuckoff';
  $DBNAME     = 'database1';

  $DEBUG      = false;

  $SITENAME   = "lolcats.dev";

  $ANONS_CAN_POST = false;

  $site->db_connect($SERVERNAME, $USERNAME, $PASSWORD, $DBNAME);
?>
