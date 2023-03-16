<?php
require "scripts/connect.php";
$userRepo = new UsersRepository($pdo);
$user = $userRepo->get($_SESSION["user"]);
$userPicUrl = $user->getProfileImageUrl();
$isOnChat = false;
?>

<header class="feed_header">
    <section>
        <a href="" class="settings-icon" id="search_settings_icon"><i class="bi bi-sliders2-vertical"></i></a>
        <form action="" method="get" id="search_form">
            <input type="search" name="search" id="search" placeholder="Rechercher" class="text-input">
            <input type="hidden" name="category" value="<?php echo $category; ?>">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>
        <a href="userProfile.php?previous_url=feed.php?category=<?php echo $category; ?>"><img src="<?php echo strlen($userPicUrl) > 0 ? $userPicUrl : "img/Placeholder.png" ?>" alt="Image de profil de l'utilisateur"><br>Mon Compte</a>
    </section>
    <h1><?php echo $title ?></h1>
</header>
<?php
if (substr($category, 0, 4) == "chat") {
    require "include/chatSubHeader.php";
    $isOnChat = true;
}
?>
<aside class="header_search_options <?php if ($isOnChat) echo "header_search_options_chat"; ?>" id="header_search_options">
    <?php if ($category == "search") { ?>
        <h4>Il n'y a pas d'option de recherche pour cette page.</h4>
    <?php } else { ?>
        <input type="checkbox" name="restrict" id="restrict" form="search_form" checked>
        <label for="restrict">Limiter la recherche à la catégorie <?php
            switch ($category) {
                case "news":
                    echo "News";
                    break;
                case "games":
                    echo "Jeux Préférés";
                    break;
                case "game_list":
                    echo "Liste des Jeux";
                    break;
                case "friends":
                    echo "Liste d'Amis";
                    break;
                case "chat-friends":
                    echo "Messages d'Amis";
                    break;
                case "chat-forum":
                    echo "Messages de Forum";
                    break;
            }
        ?></label>
        <div id="other_options">
            <?php if ($category == "news") { ?>
                <input type="checkbox" name="search_title" id="search_title" form="search_form" checked>
                <label for="search_title">Rechercher dans le titre</label>
                <br>
                <input type="checkbox" name="search_content" id="search_content" form="search_form" checked>
                <label for="search_content">Rechercher dans le contenu</label>
            <?php } else if ($category == "games" || $category == "game_list") { ?>
                <input type="checkbox" name="search_name" id="search_name" form="search_form" checked>
                <label for="search_name">Rechercher dans le nom</label>
                <br>
                <input type="checkbox" name="search_desc" id="search_desc" form="search_form" checked>
                <label for="search_desc">Rechercher dans la description</label>
            <?php } else if ($category == "friends") { ?>
                <input type="checkbox" name="search_all_users" id="search_all_users" form="search_form" <?php if (isset($searchAllUsers) && $searchAllUsers) echo "checked"; ?>>
                <label for="search_all_users">Rechercher dans tous les utilisateurs</label>
            <?php } else if ($category == "chat-forum") { ?>
                <input type="checkbox" name="search_game_name" id="search_game_name" form="search_form" checked>
                <label for="search_game_name">Rechercher dans le nom du jeu</label>
                <br>
                <input type="checkbox" name="search_poster_name" id="search_poster_name" form="search_form" checked>
                <label for="search_poster_name">Rechercher dans le nom de l'utilisateur</label>
            <?php } ?>
        </div>
    <?php } ?>
</aside>