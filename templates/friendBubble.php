<article class="message_bubble friend_message_bubble">
    <img src="<?php echo $sender->getProfileImageUrl(); ?>" alt="Image de Profil de l'Utilisateur">
    <div class="arrow-left"></div>
    <p><?php echo $message->getContent(); ?></p>
</article>