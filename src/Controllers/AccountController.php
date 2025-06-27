<?php

namespace App\Controllers;

use App\Repository\UserRepository;
use App\Support\SessionService;

class AccountController {

    public function __construct(protected UserRepository $userRepository){}

    public function modifyUserAccountStatus($request) {
       $userId = (int) $request["post"]["userId"] ?? "";
       $status = $request["post"]["status"] ?? "";
       $redirect = $request["post"]["redirectBack"] ?? "index.php";

       $success = $this->userRepository->handleUpdateAccountStatus($userId, $status);
       
       if ($success) {
            SessionService::setAlertMessage("success_message", "Suspend Account sucessully");
       }
       else {
            SessionService::setAlertMessage("success_message", "Suspend Account failed");
       }

       header("Location: " . $redirect);
       exit;
    }
}