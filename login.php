<?php

if (isset($_POST["submit"])) {
    if ($_POST["username"] == "admin" && $_POST["password"] == "123") {
        header("Location: admin.php");
    } else {
        $error = true;
    }
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
    <h1>Login Admin</h1>

    <?php if (isset($error)) : ?>
        <p style="color:red">Username atau password salah</p>
    <?php endif ?>

    <form action="" method="post">
        <input type="text" name="username" placeholder="Masukkan username">
        <input type="password" name="password" placeholder="*******">
        <button type="submit" name="submit">Submit</button>
    </form>
</body>

</html>