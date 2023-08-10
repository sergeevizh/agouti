<div id="contentWrapper" class="wrap wrap-max justify-between">
  <main>
    <a class="text-sm" href="<?= url('web'); ?>">
      << <?= __('web.catalog'); ?></a>
        <span class="gray-600">/ <?= __('web.comments'); ?></span>

        <div class="flex justify-between mt10 mb20">
          <?= insert('/content/item/admin/menu'); ?>
        </div>

        <?php foreach ($data['comments'] as $comment) : ?>
          <div class="gray text-sm">
            <a class="gray-600" href="<?= url('profile', ['login' => $comment['login']]); ?>">
              <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
              <span class="mr5">
                <?= $comment['login']; ?>
              </span>
            </a>
            <span class="mr15 ml5 gray-600 lowercase">
              <?= Html::langDate($comment['date']); ?>
            </span>
            <a class="black" href="<?= url('website', ['id' => $comment['item_id'], 'slug' => $comment['item_slug']]); ?>">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#eye"></use>
              </svg>
            </a>
            <div class="gray-600 mb15 ind-first-p"><?= markdown($comment['content'], 'line'); ?></div>
          </div>
        <?php endforeach; ?>
  </main>
  <?= insert('/content/item/admin/sidebar'); ?>
</div>