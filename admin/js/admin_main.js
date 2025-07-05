$(document).ready(function() {
    // Tìm kiếm động
    $('#search-input').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Lọc động
    $('.filter').on('change', function() {
        var danh_muc_id = $('#filter-danh-muc').val();
        var trang_thai = $('#filter-trang-thai').val();
        var role = $('#filter-role').val();
        $('.table tbody tr').filter(function() {
            var show = true;
            if (danh_muc_id && $(this).find('.danh-muc').text() !== danh_muc_id) {
                show = false;
            }
            if (trang_thai && $(this).find('.trang-thai').text() !== trang_thai) {
                show = false;
            }
            if (role && $(this).find('.role').text() !== role) {
                show = false;
            }
            $(this).toggle(show);
        });
    });

    // Xử lý hiển thị modal chi tiết bài viết
    $('.view-details').on('click', function(e) {
        e.preventDefault();
        var postId = $(this).data('id');
        $.ajax({
            url: 'admin_posts.php?action=details',
            type: 'GET',
            data: { id: postId },
            success: function(response) {
                var post = JSON.parse(response);
                $('#post-details').html(`
                    <div class="post-detail">
                        <p><strong>Tiêu đề:</strong> ${post.tieu_de}</p>
                        <p><strong>Mô tả:</strong> ${post.mo_ta}</p>
                        <p><strong>Nội dung:</strong> ${post.noi_dung}</p>
                        <p><strong>Danh mục:</strong> ${post.danh_muc}</p>
                        <p><strong>Trạng thái:</strong> ${post.trang_thai}</p>
                        <p><strong>Hình ảnh:</strong></p>
                        <img src="../../assets/img/${post.hinh_anh}" alt="${post.tieu_de}">
                    </div>
                `);
                $('#post-modal').show();
                $('.approve-btn, .reject-btn, .pending-btn').data('id', postId);
            },
            error: function() {
                alert('Lỗi khi tải chi tiết bài viết!');
            }
        });
    });

    // Đóng modal
    $('.close').on('click', function() {
        $('#post-modal').hide();
    });

    // Xử lý duyệt/từ chối/chờ duyệt
    $('.approve-btn, .reject-btn, .pending-btn').on('click', function() {
        var postId = $(this).data('id');
        var action = $(this).data('action');
        $.ajax({
            url: 'admin_posts.php',
            type: 'POST',
            data: { action: action, id: postId },
            success: function(response) {
                alert('Cập nhật trạng thái thành công!');
                location.reload();
            },
            error: function() {
                alert('Lỗi khi cập nhật trạng thái!');
            }
        });
    });

    // Đóng modal khi click bên ngoài
    $(window).on('click', function(event) {
        if (event.target == $('#post-modal')[0]) {
            $('#post-modal').hide();
        }
    });
});