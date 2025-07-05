<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Kết nối CSDL
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
$error = '';
$success = '';

// Xử lý form POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } else {
        $stmt = mysqli_prepare($link, "SELECT * FROM dang_ky_email WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "Email đã được đăng ký.";
        } else {
            // Thêm email vào CSDL
            $stmt_insert = mysqli_prepare($link, "INSERT INTO dang_ky_email (email) VALUES (?)");
            mysqli_stmt_bind_param($stmt_insert, "s", $email);

            if (mysqli_stmt_execute($stmt_insert)) {
                // Gửi email xác nhận bằng PHPMailer
                require_once __DIR__ . '/PHPMailer/PHPMailer.php';
                require_once __DIR__ . '/PHPMailer/SMTP.php';
                require_once __DIR__ . '/PHPMailer/Exception.php';

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'nguyengiang2005e@gmail.com';         // Gmail của bạn
                    $mail->Password = 'juofmuqigznluixm';                   // App password từ Gmail
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';

                    $mail->setFrom('nguyengiang2005e@gmail.com', 'Khám phá Bình Định');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Đăng ký nhận tin thành công!';
                    $mail->Body = '
                        <h3>Chào bạn!</h3>
                        <p>Bạn đã đăng ký thành công nhận tin từ <strong>Khám phá Bình Định</strong>.</p>
                        <p>Hãy đón chờ các bài viết mới về địa điểm đẹp, món ngon và văn hóa Bình Định nhé!</p>
                        <hr>
                        <p><i>Đây là email tự động, vui lòng không trả lời.</i></p>
                    ';

                    $mail->send();
                    $success = "Đăng ký thành công! Email xác nhận đã được gửi.";
                } catch (Exception $e) {
                    $success = "Đăng ký thành công nhưng không gửi được email: " . $mail->ErrorInfo;
                }
            } else {
                $error = "Lỗi khi lưu: " . mysqli_error($link);
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Trả về kết quả HTML cho AJAX
    echo '<div id="responseMessage">';
    if ($error) echo '<p class="glow error">' . $error . '</p>';
    elseif ($success) echo '<p class="glow success">' . $success . '</p>';
    echo '</div>';
}
