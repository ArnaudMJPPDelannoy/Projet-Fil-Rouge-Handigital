<?php
if (isSetAndNotEmptyObject($_SESSION, "user")) {
    $userRepo = new UsersRepository($pdo);
    $curUser = $userRepo->get($_SESSION["user"]);

    $curDate = new DateTime();
    $disconnectTime = $curUser->getDisconnectDate();

    if ($curDate > $disconnectTime) {
        $curUser->setConnected(false);
        $curUser->setDisconnectDate("");
        $userRepo->disconnectUser($curUser);
        unset($_SESSION["user"]);
        header("Location:expired.php");
    } else {
        $newDate = $curDate->add(new DateInterval("PT1H"));
        $curUser->setDisconnectDate($newDate->format("Y-m-d H:i:s"));
        $userRepo->updateConnect($curUser);
    }
}
?>