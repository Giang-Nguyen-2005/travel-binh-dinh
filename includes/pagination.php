<?php
define('POSTS_PER_PAGE', 6);

function get_paginated_posts($link, $danh_muc_id, $posts_per_page = POSTS_PER_PAGE) {
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $posts_per_page;

    $sql_count = "SELECT COUNT(*) as total FROM bai_viet WHERE danh_muc_id = ? AND trang_thai = 'da_duyet'";
    $stmt_count = mysqli_prepare($link, $sql_count);
    mysqli_stmt_bind_param($stmt_count, "i", $danh_muc_id);
    mysqli_stmt_execute($stmt_count);
    $result_count = mysqli_stmt_get_result($stmt_count);
    $total_posts = mysqli_fetch_assoc($result_count)['total'];
    mysqli_stmt_close($stmt_count);

    $total_pages = ceil($total_posts / $posts_per_page);

    $sql = "SELECT * FROM bai_viet WHERE danh_muc_id = ? AND trang_thai = 'da_duyet' ORDER BY ngay_tao DESC LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $danh_muc_id, $posts_per_page, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return [
        'posts' => $result,
        'current_page' => $current_page,
        'total_pages' => $total_pages
    ];
}

function display_pagination($current_page, $total_pages, $base_url) {
    echo '<div class="pagination">';
    if ($current_page > 1) {
        echo '<a href="' . $base_url . '?page=' . ($current_page - 1) . '" class="page-link">Previous</a>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<span class="page-link active">' . $i . '</span>';
        } else {
            echo '<a href="' . $base_url . '?page=' . $i . '" class="page-link">' . $i . '</a>';
        }
    }
    if ($current_page < $total_pages) {
        echo '<a href="' . $base_url . '?page=' . ($current_page + 1) . '" class="page-link">Next</a>';
    }
    echo '</div>';
}
?>