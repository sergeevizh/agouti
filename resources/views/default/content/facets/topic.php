<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>  
  </nav>
</div>

<?php $topic = $data['facet']; ?>
<main class="col-span-7 mb-col-12">
  <?php if ($topic['facet_is_deleted'] == 0) { ?>
    <div class="box-flex-white">
      <div class="mb-none">
        <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w94 br-box-gray mr15'); ?>
      </div>
      <div class="flex-auto">
        <h1 class="text-2xl">
          <?= $topic['facet_seo_title']; ?>
          <?php if (UserData::checkAdmin() || $topic['facet_user_id'] == $user['id']) { ?>
            <a class="right gray-600" href="<?= getUrlByName('topic.edit', ['id' => $topic['facet_id']]); ?>">
              <i class="bi bi-pencil"></i>
            </a>
          <?php } ?>
        </h1>
        <div class="text-sm gray-400"><?= $topic['facet_short_description']; ?></div>

        <div class="mt15 right">
          <?= Tpl::import('/_block/facet/signed', [
            'user'            => $user,
            'topic'          => $topic,
            'topic_signed'   => is_array($data['facet_signed']),
          ]); ?>
        </div>

        <?= Tpl::import('/_block/facet/focus-users', [
          'user'               => $user,
          'topic_focus_count' => $topic['facet_focus_count'],
          'focus_users'       => $data['focus_users'] ?? '',
        ]); ?>

      </div>
    </div>

    <div class="box-flex-white">
      <p class="m0 text-xl mb-none"><?= Translate::get('feed'); ?></p>
      <ul class="flex flex-row list-none text-sm">

        <?= tabs_nav(
          'nav',
          $data['sheet'],
          $user,
          $pages = [
            [
              'id'      => 'facet.feed',
              'url'     => getUrlByName('topic', ['slug' => $topic['facet_slug']]),
              'title'   => Translate::get('feed'),
              'icon'    => 'bi bi-sort-down'
            ],
            [
              'id'      => 'facet.recommend',
              'url'     => getUrlByName('topic', ['slug' => $topic['facet_slug']]) . '/recommend',
              'title'   => Translate::get('recommended'),
              'icon'    => 'bi bi-lightning'
            ],
            [
              'id'      => 'info',
              'url'     => getUrlByName('topic.info', ['slug' => $topic['facet_slug']]),
              'title'   => Translate::get('info'),
              'icon'    => 'bi bi-info-lg'
            ],
          ]
        ); ?>

      </ul>
    </div>

    <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('topic', ['slug' => $topic['facet_slug']])); ?>

  <?php } else { ?>
    <div class="center col-span-10">
      <i class="bi bi-x-octagon text-8xl"></i>
      <div class="mt5 gray"><?= Translate::get('remote'); ?></div>
    </div>
  <?php } ?>
</main>
<aside class="col-span-3 relative mb-none">
  <?php if ($topic['facet_is_deleted'] == 0) { ?>
    <div class="box-flex-white">
      <div class="mr15 center box-number">
        <div class="uppercase mb5 text-sm gray"><?= Translate::get('posts'); ?></div>
        <?= $topic['facet_count']; ?>
      </div>
      <div class="ml15 center box-number">
        <div class="uppercase mb5 text-sm gray"><?= Translate::get('reads'); ?></div>
        <div class="focus-user sky-500">
          <?= $topic['facet_focus_count']; ?>
        </div>
      </div>
    </div>

    <?php if (!empty($data['pages'])) { ?>
      <div class="sticky top0 top-sm">
        <div class="box-white text-sm">
          <h3 class="uppercase-box"><?= Translate::get('pages'); ?></h3>
          <?php foreach ($data['pages'] as $ind => $row) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="">
              <?= $row['post_title']; ?>
            </a>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

    <?= Tpl::import('/_block/sidebar/topic', ['data' => $data]); ?>
    <?php if (!empty($data['writers'])) { ?>
      <div class="sticky top0 top-sm">
        <div class="box-white text-sm">
          <h3 class="uppercase-box"><?= Translate::get('writers'); ?></h3>
          <?php foreach ($data['writers'] as $ind => $row) { ?>
            <a class="flex relative pb15 items-center hidden gray-600" href="<?= getUrlByName('profile', ['login' => $row['login']]); ?>">
              <?= user_avatar_img($row['avatar'], 'max', $row['login'], 'ava-sm mr10'); ?>
              <?= $row['login']; ?> (<?= $row['hits_count']; ?>)
            </a>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

  <?php } ?>
</aside>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', function() {
    tippy('.focus-user', {
      allowHTML: true,
      trigger: 'click',
      trigger: 'mouseenter click',
      allowHTML: 'true',
      hideOnClick: 'toggle',
      maxWidth: 'none',
      interactive: 'true',
      placement: 'auto',
      theme: 'light',
      onShow(instance) {
        fetch('/topic/<?= $topic['facet_slug']; ?>/followers/<?= $topic['facet_id']; ?>')
          .then((response) => response.text())
          .then(function(data) {
            instance.setContent(data);
          })
          .catch((error) => {
            instance.setContent(`Request failed. ${error}`);
          });
      },
    });
  });
</script>