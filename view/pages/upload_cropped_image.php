<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['croppedImage']) && $_FILES['croppedImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['croppedImage']['tmp_name'];
        $fileName = uniqid('img_') . '.png';
        $uploadFileDir = '../../uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            echo json_encode(['url' => $dest_path]);
        } else {
            echo json_encode(['error' => 'Dosya yükleme hatası.']);
        }
    } else {
        echo json_encode(['error' => 'Dosya bulunamadı.']);
    }
} else {
    echo json_encode(['error' => 'Geçersiz istek.']);   
}
?>
