<?php
require_once "config.php";

$input = json_decode(file_get_contents("php://input"), true);
$id = $input['id'];

$sql = "DELETE FROM mahasiswa WHERE id=$id";

if ($conn->query($sql)) {
    echo json_encode(["message" => "Data berhasil dihapus"]);
} else {
    echo json_encode(["error" => $conn->error]);
}
?>