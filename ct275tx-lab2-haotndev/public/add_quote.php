<?php

define('TITLE', 'Thêm một Trích dẫn');
include_once __DIR__ . '/../partials/header.php';

echo '<h2>Thêm một Trích dẫn</h2>';

require_once __DIR__ . '/../partials/check_admin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['quotes']) || !empty($_POST['source'])) {
        require_once __DIR__ . '/../partials/db_connect.php';
        $query = 'INSERT INTO quotes (quote, source, favorite) VALUES (?, ?, ?)';
        try {
            $statement = $pdo->prepare($query);
            $statement->execute([
                $_POST['quote'],
                $_POST['source'],
                intval(isset($_POST['favorite'])) ?? 0
            ]);
        } catch (PDOException $th) {
            $pdo_error = $th->getMessage();
        }

        if ($statement && $statement->rowCount() == 1) {
            echo '<p>Trích dẫn của bạn đã được lưu trữ.</p>';
        } else {
            $error_message = 'Không thể lưu trữ trích dẫn';
            $reason = $pdo_error ?? 'Không rõ nguyên nhân';
            include __DIR__ . '/../partials/show_error.php';
        }
    } else {
        $error_message = 'Vui lòng điền đầy đủ thông tin câu Trích dẫn và Nguồn trích dẫn!';
        include __DIR__ . '/../partials/show_error.php';
    }
}

?>

<form action="add_quote.php" method="post">
    <p><label>Trích dẫn <textarea name="quote" rows="5" cols="30"></textarea></label></p>
    <p><label>Nguồn <input type="text" name="source"></label></p>
    <p><label>Đây là trích dẫn yêu thích? <input type="checkbox" name="favorite" value="yes"></label></p>
    <p><input type="submit" name="submit" value="Thêm Trích dẫn này!"></p>
</form>

<?php include_once __DIR__ . '/../partials/footer.php'; ?>