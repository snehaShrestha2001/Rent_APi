<?php
function signup($name,$phone,$email,$userName,$password){
    global $con;
    $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_user = "INSERT INTO `users`(`name`, `phone`, `email`, `userName`, `password`) VALUES ('$name','$phone','$email','$userName','$encrypted_password')";
    $result = mysqli_query($con,$insert_user);
    if ($result){
        echo json_encode(
            [
                'success' => true,
                'password'=>$password,
                'message' => 'User created successfully!'
            ]
            );
    }else{
        echo json_encode(
            [
                'success' => false,
                'message' => 'User creation failed'
            ]
            );
    }
}

function login($password, $databasePassword, $userID)
{
    
    if(password_verify($password,$databasePassword)){

        //generating token for logged in user
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        global $con;

        //inserting token into database
        $insert_token = "INSERT INTO `personal_access_tokens`(`user_id`,`token`) VALUES ('$userID','$token')";
        $result = mysqli_query($con, $insert_token);

        if($result){
            echo json_encode(
                [
                    'success'=> true,
                    'message'=> 'User logged in successfully.',
                    'token'=> $token
                ]
            );
        }
        else {
            echo json_encode(
                [
                    'success' => false,
                    'message' => 'User login failed'
                ]
            );
        }
    }else{
       echo json_encode(
          [
              'success' => false,
              'message' => 'Password is incorrect',
          ]
      );
    }
}

function checkIdValidUser($token){
    global $con;
    if($token != null){
        $check_token = "SELECT * FROM `personal_access_tokens` WHERE token = '$token'";
        $result = mysqli_query($con,$check_token);
        $count = mysqli_num_rows($result);
        if($count > 0){
            $userID = mysqli_fetch_assoc($result)['user_id'];
            return $userID;
        }
        else {
            return null;
        }
    }else{
        return null;
    }

}

function checkIfAdmin($token)
{
    $userId=checkIdValidUser($token);
    if($userId!=null){
        global $con;
        $check_admin = "SELECT * FROM users WHERE id = '$userId'";
        $result = mysqli_query($con, $check_admin);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $user = mysqli_fetch_assoc($result);
            if ($user['isAdmin'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }else{
        return false;
    }
}
?>