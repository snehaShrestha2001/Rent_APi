<?php
include 'DatabaseConfig.php';
include 'helper_functions/authentication_functions.php';

global $con;
if(isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['userName']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $userName = $_POST['userName'];
    $password = $_POST['password'];

    //check if email and phone number is valid
    if(strpos($email,"@gmail.com") !== false && preg_match('/^[0-9]{10}+$/', $phone)){

        //check of the email is already in the database
        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $check_email);
        //it counts the row
        $count = mysqli_num_rows($result);
        if ($count > 0){
            echo json_encode(
                [
                    'success' => false,
                    'message'=> "Account already exists in this email!"
                ]
                );
        }else{
            //creating an account by calling signup function
            signup($name,$phone,$email,$userName,$password);
        }
    }else{
        echo json_encode(
            [
                'message' => 'Incorrect email and phone number.',
                'success' => false
            ]
            );
    }

}
else{
    echo json_encode(
        [
            'message' => 'Please fill all the fields.',
            'success' => false
        ]
        );
}
?>