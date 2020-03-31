<?php
session_start();
unset($_SESSION["tenant"]);
unset($_SESSION["username"]);
header("Location: https://www.vultrwins.com/");
?>