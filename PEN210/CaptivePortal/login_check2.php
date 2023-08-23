<?php
$data=$_POST['passphrase'];

#echo $data;

$file = '/data/people.txt';
$current = file_get_contents($file);
$current .= $data. "" ."\n";
file_put_contents($file, $current);


header('Location: index.php?success');

?>
