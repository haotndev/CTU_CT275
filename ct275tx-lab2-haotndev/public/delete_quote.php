<?php

define('TITLE', 'Xóa một Trích dẫn');
include_once __DIR__ . '/../partials/header.php';

echo '<h2>Xóa một Trích dẫn</h2>';

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
        echo '<form action="delete_quote.php" method="POST">
    <p>Bạn có chắc muốn xóa trích dẫn này?</p>
    <div><blockquote> ' . htmlspecialchars($row['quote']) .
            '</blockquote>- ' . htmlspecialchars($row['source']);
        if ($row['favorite'] == 1) {
            echo ' <strong>Yêu thích</strong>';
        }
        echo '</div><br><input type="hidden" name="id" value="' . $_GET['id'] . '"/>
        <p><input type="submit" name="submit" value="Xóa trích dẫn này!"/></p>
        </form>';
    } else {
        $error_message = 'Không thể lấy được trích dẫn này';
        $reason = $pdo_error ?? 'Không rõ nguyên nhân';
        include __DIR__ . '/../partials/show_error.php';
    }
} elseif (isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0) {
    $query = 'DELETE FROM quotes WHERE id=? LIMIT 1';

    try {
        $statement = $pdo->prepare($query);
        $statement->execute([$_POST['id']]);
    } catch (PDOException $th) {
        $pdo_error = $th->getMessage();
    }

    if ($statement && $statement->rowCount() == 1) {
        echo '<p>Trích dẫn đã bị xóa.</p>';
    } else {
        $error_message = 'Không thể xóa trích dẫn này';
        $reason = $pdo_error ?? 'Không rõ nguyên nhân';
        include __DIR__ . '/../partials/show_error.php';
    }
} else {
    include __DIR__ . '/../partials/show_error.php';
}

include_once __DIR__ . '/../partials/footer.php';
