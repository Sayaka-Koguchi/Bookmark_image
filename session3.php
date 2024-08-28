<?php
session_start();
echo session_id();
echo $_SESSION["name"];
echo $_SESSION["age"];