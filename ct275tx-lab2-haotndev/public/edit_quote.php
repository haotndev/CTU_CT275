<?php

define('TITLE', 'Sửa một Trích dẫn');
include_once __DIR__ . '/../partials/header.php';

echo '<h2>Sửa một Trích dẫn</h2>';

require_once __DIR__ . '/../partials/check_admin.php';

require_once __DIR__ . '/../partials/db_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {

    $query = 'SELECT quote, source, favorite FROM quotes WHERE id=?';
    try {
        $statement = $pdo->prepare($query);
        $statement->execute([$_GET['id']]);
        $row = $statement->fetch();
    } catch (PDOException $th) {
        $pdo_error = $th->getMessage();
    }
    if (!empty($row)) {
        echo '<form action="edit_quote.php" method="POST">
            <p><label>Trích dẫn <textarea name="quote" rows="5" cols="30">' .
            htmlspecialchars($row['quote'])
            . '</textarea></label></p>
            <p> <label>Nguồn <input type="text" name="source" value="' .
            htmlspecialchars($row['source'])
            . '" /></label></p>
            <p><label>Đây là trích dẫn được yêu thích? <input type="checkbox" name="favorite" value="yes"';
        if ($row['favorite'] == 1) {
            echo ' checked="checked"';
        }
        echo '/></label></p>
            <input type="hidden" name="id" value="' . $_GET['id'] . '" />
            <p><input type="submit" name="submit" value="Cập nhật trích dẫn này!"/></p>
            </form>';
    } else {
        $error_message = 'Không thể lấy được trích dẫn này!';
        $reason = $pdo_error ?? "Không xác định";
        include __DIR__ . '/../partials/show_error.php';
    }
} elseif (isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0) {
    if (!empty($_POST['quote']) && !empty($_POST['source'])) {
        $query = 'UPDATE quotes SET quote=?, source=?, favorite=? WHERE id=?';
        try {
            $statement = $pdo->prepare($query);
            $statement->execute([
                $_POST['quote'],
                $_POST['source'],
                intval(isset($_POST['favorite'])) ?? 0,
                $_POST['id']
            ]);
            echo '<p>Trích dẫn này đã được cập nhật.</p>';
        } catch (PDOException $th) {
            $error_message = 'Không thể cập nhật Trích dẫn này';
            $reason = $th->getMessage();
            include __DIR__ . '/../partials/show_error.php';
        }
    } else {
        include __DIR__ . '/../partials/show_error.php';
    }
}

include_once __DIR__ . '/../partials/footer.php';
