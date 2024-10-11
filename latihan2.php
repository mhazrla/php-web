<?php
$nama = $_GET["nama"];
$nisn = $_GET["nisn"];

if (empty($nama) || empty($nisn)) {
    header("Location: latihan.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1><?= $nama ?></h1>
    <h1><?= $nisn ?></h1>
</body>

</html>