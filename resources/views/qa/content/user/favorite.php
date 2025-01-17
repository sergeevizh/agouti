<main class="col-span-9 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <ul class="flex flex-row list-none m0 p0 center">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $uid,
        $pages = [
          [
            'id'    => 'favorites',
            'url'   => getUrlByName('favorites', ['login' => $uid['user_login']]),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark'
          ],
          [
            'id'    => 'subscribed',
            'url'   => getUrlByName('user.subscribed', ['login' => $uid['user_login']]),
            'title' => Translate::get('subscribed'),
            'icon'  => 'bi bi-bookmark-plus'
          ],
        ]
      ); ?>

    </ul>
  </div>

  <?php if (!empty($data['favorites'])) { ?>
    <?php foreach ($data['favorites'] as $content) { ?>
      <div class="bg-white br-rd5 br-box-gray mt10 pt5 pr15 pb15 pl15">
        <div class="pt5 pr15 pb0 text-sm flex">
          <?= user_avatar_img($content['post']['user_avatar'], 'small', $content['post']['user_login'], 'w18 mr10'); ?>
          <?= $content['post']['user_login']; ?>
          <span class="ml10 gray">
            <?= $content['post']['post_date']; ?>
          </span>
        </div>
        <h3 class="text-2xl mt0 mr15 mb0">
          <?php if ($content['favorite_type'] == 1) {  ?>
            <span id="favorite" class="add-favorite text-sm ml15 right" data-front="personal" data-id="<?= $content['post_id']; ?>" data-type="post">
              <i class="bi bi-trash text-2xl red-500"></i>
            </span>
            <a class="font-normal black text-2xl mt0 mb0" href="<?= getUrlByName('post', ['id' => $content['post_id'], 'slug' => $content['post_slug']]); ?>">
              <?= $content['post_title']; ?>
            </a>
          <?php } else { ?>
            <span id="fav-comm" class="add-favorite right  ml15 text-sm" data-front="personal" data-id="<?= $content['answer_id']; ?>" data-type="answer">
              <i class="bi bi-trash text-2xl red-500"></i>
            </span>
            <a class="black dark-gray-300" href="<?= getUrlByName('post', ['id' => $content['post']['post_id'], 'slug' => $content['post']['post_slug']]); ?>#answer_<?= $content['answer_id']; ?>">
              <?= $content['post']['post_title']; ?>
            </a>
          <?php } ?>
        </h3>
        <?php if (!empty($content['favorite_type']) == 2) {
          echo $content['answer_content'];
        } ?>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('there are no favorites'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('info-favorite'), 'uid' => $uid]); ?>