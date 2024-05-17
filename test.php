<?php
setcookie("a", 0, time() + 3600, '/');
print_r($_COOKIE);
?>