<?php
Translate::setLang($user['lang']);
$dark     = Request::getCookie('dayNight') == 'dark' ? 'dark' : '';
$css      = $data['type'] == 'web' || $data['type'] == 'page'  ? '' : 'body-bg-fon';
$type     = $data['type'] ?? false;
$facet    = $data['facet'] ?? false;
?>

<!DOCTYPE html>
<html lang="<?= Translate::getLang(); ?>" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= $meta; ?>
  <?php getRequestHead()->output(); ?>
  <link rel="stylesheet" href="/assets/css/style.css?51">
  <link rel="icon" sizes="16x16" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" sizes="120x120" href="/favicon-120.ico" type="image/x-icon">
</head>

<body class="<?= $css; ?><?php if ($dark == 'dark') { ?> dark<?php } ?>">

  <header class="bg-white box-shadow <?php if ($type != 'page') { ?>sticky top0<?php } ?> z-30">
    <div class="box-flex-white pl10 pr10 h50">
      <div class="flex items-center">

        <div class="none mb-block">
          <div class="trigger">
            <i class="bi bi-list gray-400 text-xl mr10"></i>
          </div>
          <ul class="dropdown left">
            <?= tabs_nav(
              'menu',
              $type,
              $user,
              $pages = Config::get('menu.mobile')
            ); ?>
            <ul>
        </div>

        <a title="<?= Translate::get('home'); ?>" class="logo black" href="/">
          <?= Config::get('meta.name'); ?>
        </a>
      </div>
      <?php if (Request::getUri() != getUrlByName('search')) { ?>
        <div class="ml45 relative mb-none w-100">
          <form class="form" method="post" action="<?= getUrlByName('search'); ?>">
            <input type="text" autocomplete="off" name="q" id="find" placeholder="<?= Translate::get('to find'); ?>" class="bg-gray-100 br-rd20 pl15 w-100 h30 gray">
            <input name="token" value="<?= csrf_token(); ?>" type="hidden">
            <input name="url" value="<?= AG_PATH_FACETS_LOGOS; ?>" type="hidden">
          </form>
          <div class="absolute box-shadow bg-white p15 pt0 mt5 max-w460 br-rd3 none" id="search_items"></div>
        </div>
      <?php } ?>
      <?php if (!UserData::checkActiveUser()) { ?>
        <div class="flex right col-span-4 items-center">
          <div id="toggledark" class="header-menu-item mb-none ml45 mb-ml-20">
            <i class="bi bi-brightness-high gray-400 text-xl"></i>
          </div>
          <?php if (Config::get('general.invite') == false) { ?>
            <a class="register gray ml45 mr15 mb-ml-20 mb-mr-5 block" title="<?= Translate::get('sign up'); ?>" href="<?= getUrlByName('register'); ?>">
              <?= Translate::get('sign up'); ?>
            </a>
          <?php } ?>
          <a class="btn btn-outline-primary ml20" title="<?= Translate::get('sign.in'); ?>" href="<?= getUrlByName('login'); ?>">
            <?= Translate::get('sign.in'); ?>
          </a>
        </div>
      <?php } else { ?>
        <div class="col-span-4">
          <div class="flex right ml45 mb-ml-20 items-center text-xl">

            <?= add_post($facet, $user['id']); ?>

            <div id="toggledark" class="only-icon ml45 mb-ml-20">
              <i class="bi bi-brightness-high gray-400"></i>
            </div>

            <a class="gray-400 ml45 mb-ml-20" href="<?= getUrlByName('notifications'); ?>">
              <?php $notif = \App\Controllers\NotificationsController::setBell($user['id']); ?>
              <?php if (!empty($notif)) { ?>
                <?php if ($notif['notification_action_type'] == 1) { ?>
                  <i class="bi bi-envelope red-500"></i>
                <?php } else { ?>
                  <i class="bi bi-bell-fill red-500"></i>
                <?php } ?>
              <?php } else { ?>
                <i class="bi bi-bell"></i>
              <?php } ?>
            </a>

            <div class="ml45 mb-ml-20">
              <div class="trigger">
                <?= user_avatar_img($user['avatar'], 'small', $user['login'], 'w30 h30 br-rd-50'); ?>
              </div>
              <ul class="dropdown">
                <?= tabs_nav(
                  'menu',
                  $type,
                  $user,
                  $pages = Config::get('menu.user')
                ); ?>
                <ul>
            </div>
          </div>
        </div>
      <?php }  ?>
    </div>
  </header>
  <div id="contentWrapper">