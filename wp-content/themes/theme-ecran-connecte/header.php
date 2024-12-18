<?php ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php
	    bloginfo('name'); ?></title>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php wp_head(); ?>
</head>
<!-- BODY -->
<?php $current_user = wp_get_current_user();
if(in_array('television', $current_user->roles)) : ?>
    <body class="body_television_ecran" <?php body_class(); ?>>
<?php else: ?>
    <body <?php body_class(); ?>>
<?php endif; ?>

<!-- HEADER -->
<header>
    <!-- NAV -->
    <?php $current_user = wp_get_current_user();
    if(in_array('television', $current_user->roles)) : ?>
    <nav class="nav_ecran text-center">
        <a class="logo_ecran" href="<?php echo wp_logout_url(get_home_url()); ?>">
            <?php if (get_header_image()) : ?>
                <img src="<?php header_image(); ?>" class="d-inline-block align-top logo_ecran" alt="Logo du site">
            <?php endif; ?>
        </a>
    <?php else: ?>
    <nav class="navbar navbar-expand-lg navbar-dark nav_ecran">
        <a class="navbar-brand" href="<?php echo get_home_url(); ?>">
            <?php if (get_header_image()) : ?>
                <img src="<?php header_image(); ?>" class="d-inline-block align-top logo" alt="Logo du site">
            <?php endif; ?>
            <?php bloginfo('name'); ?>
        </a>
    <?php endif; ?>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- NAV CONTENT -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php if(is_user_logged_in()) :
                $user_id = get_current_user_id();
                $user_info = get_userdata($user_id); ?>
            <ul class="navbar-nav mr-auto">
            <?php if (in_array('administrator', $user_info->roles) || in_array('secretaire', $user_info->roles) || in_array('directeuretude', $user_info->roles)|| in_array('enseignant', $user_info->roles)): ?>
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Informations</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Créer une information'))); ?>">Créer une information</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Gestion des informations'))); ?>">Voir mes informations</a>
                    </div>
                </li>
            <?php endif;
            if (in_array('administrator', $user_info->roles) || in_array('secretaire', $user_info->roles) || in_array('directeuretude', $user_info->roles) || in_array('enseignant', $user_info->roles)) : ?>
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Alertes</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Créer une alerte'))); ?>">Créer une alerte</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Gestion des alertes'))); ?>">Voir mes alertes</a>
                    </div>
                </li>
            <?php endif;
            if (in_array('administrator', $user_info->roles) || in_array('secretaire', $user_info->roles)) : ?>
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Utilisateurs</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Créer un utilisateur'))); ?>">Créer un utilisateur</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Gestion des utilisateurs'))); ?>">Voir les utilisateurs</a>
                    </div>
                </li>
            <?php endif;
            if (in_array('administrator', $user_info->roles)) : ?>
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Départements</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Créer un département'))); ?>">Créer un département</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Gestion des départements'))); ?>">Voir les départements</a>
                    </div>
                </li>
            <?php endif;
            if (in_array('administrator', $user_info->roles)) : ?>
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo esc_url(get_permalink(get_page_by_title_V2('Gestion des codes ADE'))); ?>">Code ADE</a>
                </li>
            <?php endif; ?>
            </ul>
            <?php $current_user = wp_get_current_user();
            if(!in_array('television', $current_user->roles)) : ?>
            <ul class="nav navbar-nav navbar-right">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo esc_url(get_permalink(get_page_by_title_V2("Mon compte"))); ?>"><?php echo wp_get_current_user()->user_login; ?></a>
                    </li>
                    <li class="nav-item active my-2 my-lg-0">
                        <a class="nav-link" href="<?php echo wp_logout_url(get_home_url()); ?>">Déconnexion</a>
                    </li>
            </ul>
        <?php endif; ?>
        <?php else : ?>
            <ul class="nav navbar-nav navbar-right">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo wp_login_url(get_home_url()); ?>">Connexion</a>
                    </li>
            </ul>
        <?php endif; ?>
        </div>
    </nav>
</header>
