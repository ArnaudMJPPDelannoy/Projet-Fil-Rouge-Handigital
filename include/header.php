<?php
require "scripts/connect.php";
$userRepo = new UsersRepository($pdo);
$user = $userRepo->get($_SESSION["user"]);
$userPicUrl = $user->getProfileImageUrl();
?>

<header class="feed_header">
    <section>
        <a href="" class="settings-icon"><i class="bi bi-sliders2-vertical"></i></a>
        <form action="" method="get">
            <input type="text" name="search" id="search" placeholder="Rechercher" class="text-input">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>
        <a href="userProfile.php?previous_url=feed.php?category=<?php echo $category; ?>"><img src="<?php echo strlen($userPicUrl) > 0 ? $userPicUrl : "img/Placeholder.png" ?>" alt="Image de profil de l'utilisateur"><br>Mon Compte</a>
    </section>
    <h1><?php echo $title ?></h1>
</header>