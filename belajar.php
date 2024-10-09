<?php
echo "Belajar aja";
$judul = "Belajar PHP Web";
$body = "Form Data Diri";

$nameError = $emailError = "";
$name = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($name)) {
        $nameError = "Nama harus diisi";
    } else {
        $name = $_POST["name"];
    }

    if (empty($email)) {
        $emailError = "Email harus diisi";
    } else {
        $email = $_POST["email"];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $judul ?></title>
    <style>
        .error {
            color: red
        }
    </style>
</head>

<body>
    <h1>
        <?= $body ?>
    </h1>

    <!-- GET -->
    <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">

        <input type="text" name="nama" placeholder="Masukkan Nama" />
        <span class="error">*<?= $nameError ?></span>

        <input type="email" name="email" placeholder="Masukkan Email" />
        <span class="error">*<?= $emailError ?></span>
        <button type="submit">Submit</button>
    </form>

    <h1><?= $name ?? "" ?></h1>
    <h1><?= $email ?? "" ?></h1>

</body>

</html>