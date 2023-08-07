<?php

$amt = $_GET['amt'];

header("location:upi://pay?pn=with%20Upilink.in%20&pa=ronitvirwani1-1@okicici&cu=INR&am=$amt");
exit();