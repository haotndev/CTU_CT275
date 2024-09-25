<?php

define('TITLE', 'Xem tất cả các Trích dẫn');
include_once __DIR__ . '/../partials/header.php';

echo '<h2>Tất cả các Trích dẫn</h2>';

require_once __DIR__ . '/../partials/check_admin.php';

require_once __DIR__ . '/../partials/db_connect.php';

$query = 'SELECT id, quote, source, favorite FROM quotes ORDER BY date_entered DESC';
try {
    $statement = $pdo->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch()) {
        $htmlspecialchars = 'htmlspecialchars';
        echo "<div><blockquote>" . $htmlspecialchars($row['quote']) . "</blockquote>-" . $htmlspecialchars($row['source']) . "<br/>";
        if ($row['favorite'] == 1) {
            echo ' <strong>Yêu thích!</strong>';
        }
        echo "<p><b>Quản trị Trích dẫn: </b> <a href=\"edit_quote.php?id={$row['id']}\"> Sửa</a> <a href=\"delete_quote.php?id={$row['id']}\">Xóa</a></p></div><br>";
    }
} catch (PDOException $th) {
    $errorMessage = "Không thể lấy dữ liệu";
    $reason = $th->getMessage();
    include __DIR__ . '/../partials/show_error.php';
}

include_once __DIR__ . '/../partials/footer.php';
