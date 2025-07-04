<?php

namespace App\Controllers;

use App\Repository\UserRepository;
use App\Support\SessionService;
use App\Support\Validation;

class AccountController extends AbstractController {

    public function __construct(protected UserRepository $userRepository){}

    public function updateAccountInfoForm($request) {
        $userAccount = json_decode($request["post"]["userAccount"], true) ?? "";
        $newUserName = trim(sanitize($request["post"]["username"])) ?? ""; 
        $newFullName = trim(sanitize($request["post"]["fullname"])) ?? "";
        $newPassword =  trim(sanitize($request["post"]["password"])) ?? "";
        $newConfirmPassword =  trim(sanitize($request["post"]["confirmPassword"])) ?? "";

        $errors = [];
        if (empty($newUserName)) {
            $errors["usernameErr"] = "Please enter your new username";
        }
        if (empty($newFullName)) {
            $errors["fullNameErr"] = "Please enter your new fullname";
        }

        $hasChanged = false;
        $fieldsToUpdate = [];
        $params = [];

        if ($userAccount["username"] !== $newUserName ) {
             $hasChanged = true;
             $fieldsToUpdate[] = "username = :username";
             $params[':username'] = $newUserName;
        }

        if ($userAccount["fullname"] !== $newFullName) {
             $hasChanged = true;
             $fieldsToUpdate[] = "fullname = :fullname";
             $params[':fullname'] = $newFullName;
        }

        //validating the password
        if (!empty($newPassword) && !Validation::string($newPassword, 6, 50)) {
             $errors["newPasswordErr"] = "Please confirm password must at least 6 character long";
        }
        if (!empty($newConfirmPassword) && !Validation::match($newPassword, $newConfirmPassword)) {
             $errors["newPasswordErr"] = "Password not match";
             $errors["confirmPasswordErr"] = "Password Confirm not match";
        }

        if (password_verify($newConfirmPassword, $userAccount["password"])) {
            $errors["confirmPasswordErr"] = "New password must not be the same as the old password";
        }

        $hashedNewPassword = "";
        if (!empty($newPassword) && !empty($newConfirmPassword) && !isset($errors["newPasswordErr"]) && !isset($errors["confirmPasswordErr"]) ) {
            $hasChanged = true;
            $hashedNewPassword = password_hash($newConfirmPassword, PASSWORD_DEFAULT);
            $fieldsToUpdate[] = "password = :password";
            $params[':password'] = $hashedNewPassword;
        }

        if (!empty($errors)) {
            $this->render("accountUpdateForm.view", [
                "errors" => $errors,
                "userAccount" => $userAccount,
                "newUserName" => $newUserName,
                "newFullName" => $newFullName
            ]);
            exit;
        }

        if ($hasChanged && empty($errors)) {
             $sql = "UPDATE users SET " . implode(", ", $fieldsToUpdate) . " WHERE id = :id";
             $params[':id'] = $userAccount["id"];
             $success = $this->userRepository->handleUpdateAccount($sql, $params);

             if ($success) {
                  SessionService::setAlertMessage("success_message", "Updated Account Successfully");
             }
             else {
                  SessionService::setAlertMessage("error_message", "Failed to update Account");
             }


             header("Location: " . "index.php?page=updateAccountInfoForm&" . http_build_query(["userId" => $userAccount["id"]]));
             exit;
        }
        else {
            SessionService::setAlertMessage("error_message", "The account information is already up to date");
            $this->render("accountUpdateForm.view", [
                "errors" => $errors,
                "userAccount" => $userAccount,
                "newUserName" => $newUserName,
                "newFullName" => $newFullName
            ]);
            exit;
        }
    }


    public function modifyUserAccountStatus($request) {
       $userId = (int) $request["post"]["userId"] ?? "";
       $status = $request["post"]["status"] ?? "";
       $redirect = $request["post"]["redirectBack"] ?? "index.php";

       $success = $this->userRepository->handleUpdateAccountStatus($userId, $status);
       
       if ($success) {
            SessionService::setAlertMessage("success_message", "Suspend Account sucessully");
       }
       else {
            SessionService::setAlertMessage("error_message", "Suspend Account failed");
       }

       header("Location: " . $redirect);
       exit;
    }
}