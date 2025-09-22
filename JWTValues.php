<?php
// require 'vendor/autoload.php';

// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;
// use Firebase\JWT\ExpiredException;
// use Firebase\JWT\SignatureInvalidException;
// use Firebase\JWT\BeforeValidException;
// use DomainException;
// use InvalidArgumentException;
// use UnexpectedValueException;

// class JWTValues {
//     private $secretKey;
//     private $userData = null;

//     public function __construct($secretKey) {
//         $this->secretKey = $secretKey;
//     }

//     // Decode the token and retrieve user data
//     public function decodeToken() {
//         if (isset($_COOKIE['token'])) {
//             $token = $_COOKIE['token'];
            
//             try {
//                 // Correct JWT decoding with Key object
//                 $decodedToken = JWT::decode($token, new Key($this->secretKey, 'HS256'));
                
//                 // Store the user data in the object
//                 $this->userData = (object) [
//                     'userID' => $decodedToken->data->id,
//                     'adminName' => $decodedToken->data->name,
//                     'userRole' => $decodedToken->data->role,
//                     'userEmail' => $decodedToken->data->email,
//                     'userDesignation' => $decodedToken->data->designation
//                 ];
                
//             } catch (Exception $e) {
//                 // Token decoding failed (e.g., expired or invalid)
//                 $this->userData = null;
//             }
//         }
//     }

//     // Check if user data is available
//     public function isLoggedIn() {
//         return $this->userData !== null;
//     }

//     // Get the user data
//     public function getUserData() {
//         return $this->userData;
//     }

//     // Get specific user information
//     public function getUserID() {
//         return $this->userData ? $this->userData->userID : null;
//     }

//     public function getAdminName() {
//         return $this->userData ? $this->userData->adminName : null;
//     }

//     public function getUserRole() {
//         return $this->userData ? $this->userData->userRole : null;
//     }

//     public function getUserEmail() {
//         return $this->userData ? $this->userData->userEmail : null;
//     }

//     public function getUserDesignation() {
//         return $this->userData ? $this->userData->userDesignation : null;
//     }
// }




require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class JWTValues {
    private $secretKey;
    private $userData = null;

    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }

    // Decode the token and retrieve user data
    public function decodeToken() {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            
            try {
                // Correct JWT decoding with Key object
                $decodedToken = JWT::decode($token, new Key($this->secretKey, 'HS256'));
                
                // Store the user data in the object
                // $this->userData = (object) [
                //     'userID' => $decodedToken->data->id,
                //     'adminName' => $decodedToken->data->name,
                //     'userRole' => $decodedToken->data->role,
                //     'userEmail' => $decodedToken->data->email,
                //     'userDesignation' => $decodedToken->data->designation,
                //     'clientID' => $decodedToken->data->cuid  

                // ];
                if (isset($decodedToken->data)) {
                    $this->userData = (object) [
                        'userID' => $decodedToken->data->id,
                        'adminName' => $decodedToken->data->name,
                        'userRole' => $decodedToken->data->role,
                        'userEmail' => $decodedToken->data->email,
                        'userDesignation' => $decodedToken->data->designation,
                        'clientID' => isset($decodedToken->data->cuid) ? $decodedToken->data->cuid : null // Safely accessing 'cuid'
                    ];
                }
                
            } catch (Exception $e) {
                // Token decoding failed (e.g., expired or invalid)
                $this->userData = null;
            }
        }
    }

    // Check if user data is available
    public function isLoggedIn() {
        return $this->userData !== null;
    }

    // Get the user data
    public function getUserData() {
        return $this->userData;
    }

    // Get specific user information
    public function getUserID() {
        return $this->userData ? $this->userData->userID : null;
    }

    public function getAdminName() {
        return $this->userData ? $this->userData->adminName : null;
    }

    public function getUserRole() {
        return $this->userData ? $this->userData->userRole : null;
    }

    public function getUserEmail() {
        return $this->userData ? $this->userData->userEmail : null;
    }

    public function getUserDesignation() {
        return $this->userData ? $this->userData->userDesignation : null;
    }
    
    public function getClientID() {
        return $this->userData ? $this->userData->clientID : null;
    }
}

?>