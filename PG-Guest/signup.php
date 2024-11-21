<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader

session_start();
require 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for action
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Send OTP
        if ($action === 'send_otp') {
            $email = $_POST['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
                exit();
            }

            $otp = rand(100000, 999999); // Generate a 6-digit OTP

            // Save OTP and expiry time in the session
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 300; // OTP expires in 5 minutes

            // Insert or update the OTP in the database
            $stmt = $conn->prepare("INSERT INTO users (email, otp, otp_sent_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE otp = ?, otp_sent_at = NOW()");
            $stmt->bind_param("sss", $email, $otp, $otp);
            $stmt->execute();

            // Send OTP via email
            $mail = new PHPMailer(true);
            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'jagadeswararaovana@gmail.com'; // Your email
                $mail->Password = 'vienyxievujtsiit'; // Your app-specific password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email settings
                $mail->setFrom('your-email@gmail.com', 'StayConnect');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for StayConnect';
                $mail->Body = "Your OTP is <b>$otp</b>. It is valid for 5 minutes.";

                $mail->send();
                echo json_encode(['success' => true, 'message' => 'OTP sent to your email.']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
            }
        }

        // Verify OTP
        elseif ($action === 'verify_otp') {
            $email = $_POST['email'];
            $otp = $_POST['otp'];

            // Check OTP in the database
            $stmt = $conn->prepare("SELECT otp, otp_sent_at FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify if the OTP is correct and not expired (5 minutes validity)
                if ($row['otp'] === $otp && (time() - strtotime($row['otp_sent_at'])) <= 300) {
                    $_SESSION['otp_verified'] = true; // Mark OTP as verified
                    echo json_encode(['success' => true, 'message' => 'OTP verified successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid or expired OTP.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No OTP found for this email.']);
            }
        }

        // Finalize Signup
        elseif ($action === 'signup') {
            if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true) {
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                if (empty($name) || empty($phone) || empty($email) || empty($password)) {
                    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
                    exit();
                }

                // Hash the password for security
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert or update user data into the database
                $stmt = $conn->prepare("INSERT INTO users (email, name, phone, password) VALUES (?, ?, ?, ?)
                                        ON DUPLICATE KEY UPDATE name = ?, phone = ?, password = ?");
                $stmt->bind_param("sssssss", $email, $name, $phone, $hashedPassword, $name, $phone, $hashedPassword);

                if ($stmt->execute()) {
                    unset($_SESSION['otp_verified']); // Clear OTP session after successful registration
                    echo json_encode(['success' => true, 'message' => 'User registered successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error registering user.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'OTP not verified.']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action not specified.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
