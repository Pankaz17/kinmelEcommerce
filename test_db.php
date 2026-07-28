<?php

require_once "config/database.php";

try {
    $connection = db();
    echo "Database Connected Successfully";
} catch(Exception $e) {
    echo $e->getMessage();
}