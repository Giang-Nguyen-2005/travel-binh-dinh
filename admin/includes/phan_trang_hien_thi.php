<?php
if (!isset($total_pages, $current_page, $base_url)) return;

if ($total_pages > 1) {
    echo "<div style='margin: 20px 0; text-align: center;'>Trang: ";
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo "<strong style='color: red;'>$i</strong> ";
        } else {
            echo "<a href='{$base_url}&page={$i}' class='ajax-link'>$i</a> ";
        }
    }
    echo "</div>";
}
