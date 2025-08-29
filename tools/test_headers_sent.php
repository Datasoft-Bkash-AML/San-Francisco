<?php
// This script simulates headers already sent by echoing output before including config.php
echo "X";
include __DIR__ . '/../config.php';
echo "\nconfig included\n";
