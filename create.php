<?php
require_once "config.php";

$input = json_decode(file_get_contents("php://input"), true);

$nim = $input['nim'];
$nama = $input['nama'];
$jurusan = $input['jurusan'];
$email = $input['email'];

$sql = "INSERT INTO mahasiswa (nim, nama, jurusan, email)
        VALUES ('$nim', '$nama', '$jurusan', '$email')";

if ($conn->query($sql)) {
    echo json_encode(["message" => "Data berhasil ditambahkan"]);
} else {
    echo json_encode(["error" => $conn->error]);
}
?>