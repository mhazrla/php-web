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
