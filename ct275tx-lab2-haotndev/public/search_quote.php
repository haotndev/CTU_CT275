<?php

define('TITLE', 'Tìm kiếm trích dẫn');
include_once __DIR__ . '/../partials/header.php';

echo '<h2>Tìm kiếm trích dẫn</h2>';

require_once __DIR__ . '/../partials/check_admin.php';
require_once __DIR__ . '/../partials/db_connect.php';


if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $keyword =  $_GET['keyword'] ?? null;
    echo "
    <form action=\"search_quote.php\" method=\"GET\">
        <label for=\"keyword\">Nhập từ khóa</label>
        <input type=\"text\" name=\"keyword\" id=\"keyword\" value=\"$keyword\" />
        <input type=\"submit\" name=\"submit\" value=\"Tìm kiếm\" />
    </form>";
    if ($keyword) {
        $query = 'SELECT id, quote, source, favorite FROM quotes WHERE quote LIKE ? OR source LIKE ?';
        try {
            $statement = $pdo->prepare($query);
            $keyword = '%' . $_GET['keyword'] . '%';
            $statement->execute([$keyword, $keyword]);
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
    } else {
        echo "<p>Vui lòng nhập từ khóa!</p>";
    }
} ?>


<?php include_once __DIR__ . '/../partials/footer.php'; ?>