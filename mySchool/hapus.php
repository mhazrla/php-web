<?php

require 'functions.php';

$nisn = $_GET['nisn'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php

    if (hapus($nisn) > 0) {
        echo "<script>
        Swal.fire({
            title: 'Good job!',
            text: 'Data berhasil dihapus',
            icon: 'success'
            }).then((result) => {
                window.location.href = 'index.php';
            });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Failed!',
            text: 'Data gagal dihapus',
            icon: 'error'
            }).then((result) => {
                window.location.href = 'index.php';
            });
        </script>";
    }
    ?>
</body>

</html>