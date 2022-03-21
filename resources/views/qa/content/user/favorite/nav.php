<div class="box-flex-white">
  <ul class="nav">

    <?= tabs_nav(
      'nav',
      $data['sheet'],
      $user,
      $pages = Config::get('menu.favorites'),
    ); ?>

  </ul>
  <div class="text-sm">
    <i class="bi-plus-lg gray-600 mr5"></i>
    <a href="<?= getUrlByName('favorites.folders'); ?>"><?= Translate::get('folders'); ?></a>
  </div>
</div>