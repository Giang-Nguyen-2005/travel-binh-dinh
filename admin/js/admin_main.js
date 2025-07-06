$(document).ready(function() {
    // Hàm cập nhật URL với các tham số tìm kiếm và lọc
    function updateSearchAndFilter() {
        var search = $('#search-input').val();
        var danh_muc = $('#filter-danh-muc').val();
        var trang_thai = $('#filter-trang-thai').val();
        
        // Xây dựng URL với các tham số
        var url = 'admin_posts.php?';
        var params = [];
        if (search) params.push('search=' + encodeURIComponent(search));
        if (danh_muc) params.push('danh_muc=' + encodeURIComponent(danh_muc));
        if (trang_thai) params.push('trang_thai=' + encodeURIComponent(trang_thai));
        url += params.join('&');
        
        // Tải lại trang với URL mới
        window.location.href = url;
    }

    // Tìm kiếm khi nhấn Enter
    $('#search-input').on('keyup', function(e) {
        if (e.key === 'Enter') {
            updateSearchAndFilter();
        }
    });

    // Lọc khi thay đổi bộ lọc
    $('.filter').on('change', function() {
        updateSearchAndFilter();
    });

    // Giữ giá trị bộ lọc khi tải trang
    var urlParams = new URLSearchParams(window.location.search);
    $('#search-input').val(urlParams.get('search') || '');
    $('#filter-danh-muc').val(urlParams.get('danh_muc') || '');
    $('#filter-trang-thai').val(urlParams.get('trang_thai') || '');

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