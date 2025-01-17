<?= import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'uid'   => $uid,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => getUrlByName('admin.' . $data['type']),
        'name' => Translate::get('all'),
        'icon' => 'bi bi-record-circle'
      ], [
        'id' => 'add',
        'url' => getUrlByName($data['type'] . '.add'),
        'name' => Translate::get('add'),
        'icon' => 'bi bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <?php if (!empty($data['domains'])) { ?>
    <?php foreach ($data['domains'] as $key => $item) { ?>
      <div class="domain-box">
        <span class="add-favicon right text-sm" data-id="<?= $item['item_id']; ?>">
          + favicon
        </span>
        <div class="text-2xl">
          <a href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
            <?= $item['item_title_url']; ?>
          </a>
        </div>
        <?= html_facet($item['facet_list'], 'web.topic', 'gray-600 text-sm mr10'); ?>
        <div class="max-w780">
          <?= $item['item_content_url']; ?>
        </div>
        <div class="br-bottom mb15 mt5 pb10 text-sm hidden gray">
          <span class="inline mr5">
            <?= votes($uid['user_id'], $item, 'item', 'ps', 'mr5'); ?>
          </span>
          <a class="green-600" rel="nofollow noreferrer" href="<?= $item['item_url']; ?>">
            <span class="green-600"><?= $item['item_url']; ?></span>
          </a> |
          id<?= $item['item_id']; ?>
          <span class="mr5 ml5"> &#183; </span>
          <?= $item['item_url_domain']; ?>
          <span class="mr5 ml5"> &#183; </span>
          <?php if ($item['item_is_deleted'] == 0) { ?>
            active
          <?php } else { ?>
            <span class="red-500">Ban</span>
          <?php } ?>
          <span class="mr5 ml5"> &#183; </span>
          <a href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
            <?= Translate::get('edit'); ?>
          </a>
          <span class="right mr5">
            <?= website_img($item['item_url_domain'], 'favicon', $item['item_url_domain'], 'mr5 w18 h18'); ?>
          </span>
          <?php if ($item['item_published'] == 0) { ?>
            <span class="ml15 red-500"> <?= Translate::get('posted'); ?> (<?= Translate::get('no'); ?>) </span>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
  
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.sites')); ?>
</div>
 
</main>