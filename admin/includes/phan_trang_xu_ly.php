<?php
if (!isset($link, $sql_base, $limit, $current_page, $base_url)) {
    die("Thiếu tham số phân trang!");
}

$result_all = mysqli_query($link, $sql_base);
$total_rows = mysqli_num_rows($result_all);
$total_pages = ceil($total_rows / $limit);

$offset = ($current_page - 1) * $limit;
$sql_pagination = $sql_base . " LIMIT $offset, $limit";
$result_pagination = mysqli_query($link, $sql_pagination);

$GLOBALS['result_pagination'] = $result_pagination;
$GLOBALS['total_pages'] = $total_pages;
