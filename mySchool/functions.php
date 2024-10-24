<?php
$hostname = "127.0.0.1";
$username = "root";
$password = "";
$database = "schools";

$connection = mysqli_connect($hostname, $username, $password, $database);

function query($query)
{
    global $connection;
    $result = mysqli_query($connection, $query);

    $rows = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $rows[] = $row;
        }
    }

    return $rows;
}

function tambah($data)
{
    global $connection;
    $nisn = htmlspecialchars($data["nisn"]);
    $name = htmlspecialchars($data["name"]);
    $umur = htmlspecialchars($data["umur"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $major = htmlspecialchars($data["major"]);
    $class = htmlspecialchars($data["class"]);

    $checkNISN = query("SELECT * FROM students WHERE nisn=$nisn");

    if (count($checkNISN) > 0) {
        return -1;
    }

    $query = "INSERT 
    INTO students 
    VALUES('$nisn', '$name', '$alamat', '$umur', '$class', '$major')";

    $insert = mysqli_query($connection, $query);
    $result = mysqli_affected_rows($connection);

    return $result;
}

function update($data)
{
    global $connection;
    $nisn = htmlspecialchars($data["nisn"]);
    $name = htmlspecialchars($data["name"]);
    $umur = htmlspecialchars($data["umur"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $major = htmlspecialchars($data["major"]);
    $class = htmlspecialchars($data["class"]);

    $query = "UPDATE students SET
				name = '$name',
				alamat = '$alamat',
				umur = '$umur',
				class_id = '$class',
				major_id = '$major'
			  WHERE nisn = $nisn
			";
    mysqli_query($connection, $query);
    return mysqli_affected_rows($connection);
}

function hapus($nisn)
{
    global $connection;
    mysqli_query($connection, "DELETE FROM students_subjects WHERE nisn='$nisn'");
    mysqli_query($connection, "DELETE FROM students WHERE nisn='$nisn'");
    return mysqli_affected_rows($connection);
}

function validateInput($data)
{
    $errors = [];
    $valid = true;

    if (empty($data["name"])) {
        $errors['name'] = "Nama harus diisi";
        $valid = false;
    } else {
        $name = htmlspecialchars($data["name"]);
    }

    if (empty($data["nisn"])) {
        $errors['nisn'] = "NISN harus diisi";
        $valid = false;
    } else {
        $nisn = htmlspecialchars($data["nisn"]);
    }

    if (empty($data["umur"])) {
        $errors['umur'] = "Umur harus diisi";
        $valid = false;
    } else {
        $umur = htmlspecialchars($data["umur"]);
    }

    if (empty($data["alamat"])) {
        $errors['alamat'] = "Alamat harus diisi";
        $valid = false;
    } else {
        $alamat = htmlspecialchars($data["alamat"]);
    }

    if (empty($data["major"])) {
        $errors['major'] = "Jurusan harus dipilih";
        $valid = false;
    } else {
        $major = htmlspecialchars($data["major"]);
    }

    if (empty($data["class"])) {
        $errors['class'] = "Kelas harus dipilih";
        $valid = false;
    } else {
        $class = htmlspecialchars($data["class"]);
    }

    return ['valid' => $valid, 'errors' => $errors, 'data' => $data];
}

function handleFormSubmit($data, $action)
{
    $validationResult = validateInput($data);
    $valid = $validationResult['valid'];
    $errors = $validationResult['errors'];

    if ($valid) {
        $result = ($action === 'update') ? update($data) : tambah($data);

        $successMessage = $action === 'update' ? 'Berhasil mengubah data' : 'Berhasil menambahkan data';
        $duplicateMessage = 'NISN sudah ada';
        $failureMessage = $action === 'update' ? 'Gagal mengubah data' : 'Gagal menambahkan data';

        if ($result > 0) {
            echo "<script>
            Swal.fire({
                title: 'Good job!',
                text: '$successMessage',
                icon: 'success'
            }).then((result) => {
                window.location.href = 'index.php';
            });
            </script>";
        } else if ($result == -1) {
            echo "<script>
            Swal.fire({
                title: 'Failed',
                text: '$duplicateMessage',
                icon: 'error'
            }).then((result) => {
                window.location.href = 'index.php';
            });
            </script>";
        } else {
            echo "<script>
            Swal.fire({
                title: 'Failed',
                text: '$failureMessage',
                icon: 'error'
            }).then((result) => {
                window.location.href = 'index.php';
            });
            </script>";
        }
    } else {
        $errorMessage = implode(", ", $errors);
        echo "<script>
        Swal.fire({
            title: 'Failed',
            text: 'Data belum lengkap: $errorMessage',
            icon: 'error'
        });
        </script>";
    }
}
