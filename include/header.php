<?php
require "scripts/connect.php";
$userRepo = new UsersRepository($pdo);
$user = $userRepo->get($_SESSION["user"]);
$userPicUrl = $user->getProfileImageUrl();
?>

<header>
    <section>
        <a href="" class="settings-icon"><i class="bi bi-sliders"></i></a>
        <form action="" method="get">
            <input type="text" name="search" id="search" placeholder="Rechercher" class="text-input">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>
        <a href="userProfile.php"><img src="<?php echo strlen($userPicUrl) > 0 ? $userPicUrl : "img/Placeholder.png" ?>" alt="Image de profil de l'utilisateur"><br>Mon Compte</a>
    </section>
    <h1><?php echo $title ?></h1>
</header>