<?php
require_once '../config.php'; // Inclua o arquivo config.php com a conexão ao banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere os dados do formulário
    $fullName = isset($_POST['FullNameSignup']) ? filter_var(filter_var($_POST['FullNameSignup'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), FILTER_SANITIZE_SPECIAL_CHARS) : null;

    $userName = filter_var($_POST['UserNameSignup'], FILTER_SANITIZE_SPECIAL_CHARS);

    $email = isset($_POST['EmailSignup']) ? filter_var(filter_var($_POST['EmailSignup'], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) : null;

    $phone = filter_input(INPUT_POST, 'PhoneSignup', FILTER_SANITIZE_NUMBER_INT);

    $password = filter_input(INPUT_POST, 'passwordLogin', FILTER_SANITIZE_SPECIAL_CHARS);

    $day = filter_input(INPUT_POST, 'day', FILTER_VALIDATE_INT);

    $month = filter_var($_POST['month'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_SPECIAL_CHARS);

    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);

    $gender = filter_var(filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_FULL_SPECIAL_CHARS), FILTER_SANITIZE_SPECIAL_CHARS);

    // Hash da senha
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    if($fullName && $userName && $email && $phone && $password && $day && $month && $year && $gender){

        if (preg_match('/^[A-Za-z0-9._]+$/', $userName)) {

            if (preg_match('/\d/', $password) && preg_match('/[a-zA-Z]/', $password)) {

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':email', $email);
                $stmt->execute();

                $count = $stmt->fetchColumn();

                

                

                $sql = "SELECT COUNT(*) FROM users WHERE user_name = :user_name";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_name', $userName);
                $stmt->execute();

                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    // E-mail já está em uso, exibir mensagem de erro ou redirecionar para a página de registro com erro
                    $_SESSION['warning'] = 'The provided user name is already in use. Please choose another one.';
                    header("Location: ".$base."/signup");
                    exit;
                }


                $sql = "INSERT INTO users (full_name, user_name, email, phone_number, password, date_of_birth, gender) 
                VALUES (:full_name, :username, :email, :phone, :password, :date_of_birth, :gender)";

                $stmt = $pdo->prepare($sql);

                $stmt->bindValue(':full_name', $fullName);
                $stmt->bindValue(':username', $userName);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':phone', $phone);
                $stmt->bindValue(':password', $hashPassword);
                $stmt->bindValue(':date_of_birth', $year . '-' . $month . '-' . $day);
                $stmt->bindValue(':gender', $gender);
                $stmt->execute();

                $lastInsertedId = $pdo->lastInsertId();
                
                $sql = "SELECT * FROM users WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $lastInsertedId);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Sucesso - redirecione ou exiba uma mensagem de sucesso
                $_SESSION['user'] = $user;
                header("Location: ".$base);
                exit();
            }
            else{
                $_SESSION['warning'] = 'The password does not contain any numbers or letters';
                header("Location: ".$base."/signup");
                exit;
            }
        }else{
            $_SESSION['warning'] = 'The username is invalid. Only letters (a-z), numbers (0-9), period (.), or underscore (_) are allowed. Spaces are not permitted.';
            header("Location: ".$base."/signup");
            exit;
        }
    }else{
        $_SESSION['warning'] = 'Fill in all the data';
        header("Location: ".$base."/signup");
        exit;
    }

    
}
