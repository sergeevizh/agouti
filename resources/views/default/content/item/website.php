<main class="col-span-9 mb-col-12">
  <div class="bg-white items-center justify-between ml5 pr15 mb15">

    <a href="<?= getUrlByName('web'); ?>"><?= Translate::get('sites'); ?></a> /
    <span class="red-500"><?= Translate::get('website'); ?></span>

    <div class="right">
      <?= votes($uid['user_id'], $data['item'], 'item', 'ps', 'text-2xl', 'block'); ?>
    </div>

    <h1 class="mt5 mb10 text-2xl font-normal"><?= $data['item']['item_title_url']; ?>
      <?php if ($uid['user_trust_level'] > 4) { ?>
        <a class="text-sm ml5" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $data['item']['item_id']]); ?>">
          <i class="bi bi-pencil"></i>
        </a>
      <?php } ?>
    </h1>

    <div class="flex flex-auto">
      <?= website_img($data['item']['item_url_domain'], 'thumbs', $data['item']['item_title_url'], 'mr5 mt5 w400 mb-w-100 box-shadow'); ?>
      <div class="ml60 mt15 mb-ml-0">
        <?= $data['item']['item_content_url']; ?>

        <div class="gray mt20 mb5">
          <a class="green-600" target="_blank" rel="nofollow noreferrer ugc" href="<?= $data['item']['item_url']; ?>">
            <?= website_img($data['item']['item_id'], 'favicon', $data['item']['item_url_domain'], 'mr5 w18 h18'); ?>
            <?= $data['item']['item_url']; ?>
          </a>
        </div>

        <?php if (!empty($data['topics'])) { ?>
          <div class="mt20 mb20 mb-mb-5 lowercase">
            <?php foreach ($data['topics'] as $topic) { ?>
              <?php if ($topic['facet_is_web'] == 1) { ?>
                <a class="pt5 pr20 pb5 sky-500 block text-xl" href="<?= getUrlByName('web.topic', ['slug' => $topic['facet_slug']]); ?>">
                  <?= $topic['facet_title']; ?>
                </a>
              <?php } ?>
            <?php } ?>
          </div>
        <?php } ?>

      </div>
    </div>
    <?php if ($data['item']['item_is_soft'] == 1) { ?>
      <h2 class="mb5 mb-mt-5 font-normal"><?= Translate::get('soft'); ?></h2>
      <h3 class="mt5 mb10 font-normal"><?= $data['item']['item_title_soft']; ?></h3>
      <div class="gray-600">
        <?= $data['item']['item_content_soft']; ?>
      </div>
      <div class="mb5">
        <i class="bi bi-github mr5"></i>
        <a target="_blank" rel="nofollow noreferrer ugc" href="<?= $data['item']['item_github_url']; ?>">
          <?= $data['item']['item_github_url']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
</main>
<aside class="col-span-3 relative no-mob">
  <div class="bg-white br-rd5 br-box-gray box-shadow-all p15 mb15">
    <?php if ($data['high_leve']) { ?>
      <div class="gray"><?= Translate::get('see more'); ?></div>
      <?php foreach ($data['high_leve'] as $rl) { ?>
        <?php if ($rl['facet_is_web'] == 1) { ?>
          <a class="inline mr20 text-sm black" href="<?= getUrlByName('web.topic', ['slug' => $rl['facet_slug']]); ?>">
            <?= $rl['facet_title']; ?>
          </a>
        <?php } ?>
      <?php } ?>
    <?php } else { ?>
      ....
    <?php } ?>
  </div>
  <?php if ($data['related_posts']) { ?>
    <div class="bg-white br-rd5 br-box-gray pt15 pl15 text-sm">
      <?= import('/_block/related-posts', ['related_posts' => $data['related_posts'], 'number' => 'no', 'uid' => $uid]); ?>
    </div>
  <?php } ?>
</aside>