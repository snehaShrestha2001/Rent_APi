<?php

include 'DatabaseConfig.php';
include 'helper_functions/authentication_functions.php';

$isAdmin = checkIfAdmin($_POST['token'] ?? null);
if ($isAdmin) {
    if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description']) && isset($_POST['category_id'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];

        $addCategory = "INSERT INTO `products`(`NAME`, `price`, `description`,`category_id`) VALUES ('$name','$price','$description','$category_id')";
        $result = mysqli_query($con, $addCategory);
        if ($result) {
            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Product added'
                ]
            );
        } else {
            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Error adding product'
                ]
            );
        }
    } else {
        echo json_encode(
            [
                'success' => false,
                'message' => 'Please fill all the fields.'
            ]
        );
    }
} else {
    echo json_encode(
        [
            'success' => false,
            'message' => 'Access denied'
        ]
    );
}
