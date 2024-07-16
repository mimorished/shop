<?php
$receiptContent = "Subscription Receipt\n";
$receiptContent .= "Date: " . date('Y-m-d H:i:s') . "\n";
$receiptContent .= "Price: $5.99\n";
$receiptContent .= "Subscription details: Standard Subscription\n";

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="subscription_receipt.txt"');

echo $receiptContent;
?>
