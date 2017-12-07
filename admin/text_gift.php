<?php
header('Content-disposition: attachment; filename=giftcodelists.txt');
header('Content-type: text/plain');
readfile('../giftcodelists.txt');
?>