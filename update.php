<?php
require_once "config.php";

$input = json_decode(file_get_contents("php://input"), true);

$id = $input['id'];
$nim = $input['nim'];
$nama = $input['nama'];
$jurusan = $input['jurusan'];
$email = $input['email'];

$sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', jurusan='$jurusan', email='$email' WHERE id=$id";

if ($conn->query($sql)) {
    echo json_encode(["message" => "Data berhasil diupdate"]);
} else {
    echo json_encode(["error" => $conn->error]);
}
?>