<?php
$perem1 = "dawdaw@mail.ru";
$perem2 = "123";
$perem3 = "null";
$ch = curl_init('http://45.55.69.246/mega-website-v2/public/api/compliment/code/generate');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "object_id=64&"
    .   "email=".$perem1."&"
    .   "product_cost=".$perem2."&"
    .   "reward_type=".$perem3."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);
var_dump($result);