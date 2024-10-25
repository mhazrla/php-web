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

    $checkNISN = query("SELECT * FROM students WHERE nisn='$nisn'");

    if (count($checkNISN) > 0) {
        return -1;
    }

    // upload gambar
    $image = upload();
    if (!$image) {
        return false;
    }

    $query = "INSERT 
    INTO students 
    VALUES('$nisn', '$name', '$alamat', '$umur', '$class', '$major', '$image')";

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
    $oldImage = htmlspecialchars($data['old_image']);

    if ($_FILES['image']['error'] === 4) {
        $image = $oldImage;
    } else {
        $image = upload();
    }

    $query = "UPDATE students SET
				name = '$name',
				alamat = '$alamat',
				umur = '$umur',
				class_id = '$class',
				major_id = '$major',
                image = '$image'
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


function upload()
{
    $originalName = $_FILES['gambar']['name'];
    $filesize = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if (
        $error === 4
    ) {
        echo "<script>
				alert('pilih gambar terlebih dahulu!');
			  </script>";
        return false;
    }

    $validExtension = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $originalName);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $validExtension)) {
        echo "<script>
				alert('File bukan gambar');
			  </script>";
        return false;
    }

    if ($filesize > 1000000) {
        echo "<script>
				alert('Ukuran gambar terlalu besar!');
			  </script>";
        return false;
    }

    $newFilename = uniqid() . '.' . $ekstensiGambar;
    move_uploaded_file($tmpName, 'img/' . $newFilename);

    return $newFilename;
}
