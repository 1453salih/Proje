<?php

include '../../db.php';

try {
    // PDO hata modunu ayarlar
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Meslekleri çeker
    $meslekler_sql = "SELECT id, meslek, sektor_id FROM meslekler";
    $meslekler_stmt = $conn->prepare($meslekler_sql);
    $meslekler_stmt->execute();
    $meslekler_result = $meslekler_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sektörleri çeker
    $sektorler_sql = "SELECT id, sektor FROM sektorler";
    $sektorler_stmt = $conn->prepare($sektorler_sql);
    $sektorler_stmt->execute();
    $sektorler_result = $sektorler_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sektörleri bir diziye alır
    $sektorler = [];
    foreach ($sektorler_result as $row) {
        $sektorler[$row['id']] = $row['sektor'];
    }

    // HTML başlangıcı
    echo '<div class="row mb-5">
            <div class="col-lg-12">
                <label for="meslek_secim" class="">Meslek Tercihleri:</label><br>
                <select class="js-example-basic-multiple js-states form-control select2-hidden-accessible" multiple="multiple" tabindex="-1" aria-hidden="true" style="width: 100%" name="meslek_secim[]" data-placeholder="Seçiniz..">'; // name="meslek_secim[]" olarak düzeltildi

    // Meslekleri sektörlerine göre gruplandırarak gösterir
    $current_sektor = '';
    foreach ($meslekler_result as $row) {
        if ($current_sektor != $row['sektor_id']) {
            if ($current_sektor != '') {
                echo '</optgroup>';
            }
            $current_sektor = $row['sektor_id'];
            echo '<optgroup label="' . $sektorler[$current_sektor] . '">'; 
        }
        echo '<option value="' . $row['id'] . '">' . $row['meslek'] . '</option>';
    }

    echo '</optgroup></select></div></div>';

} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
