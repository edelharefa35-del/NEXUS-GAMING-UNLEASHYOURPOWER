<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php"); // Setelah keluar, tendang lagi ke halaman login
exit;