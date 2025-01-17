<main class="col-span-9 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
  </div>
  <div class="bg-white br-rd5 br-box-gray p15">
    <?php if ($uid['user_trust_level'] > 1) { ?>
      <form method="post" action="<?= getUrlByName('invit.create'); ?>">
        <?php csrf_field(); ?>
        <div class="mb20">
          <input class="w-100 h30" type="email" name="email">
          <div class="right pt5"><?= sumbit(Translate::get('send')); ?></div>
          <div class="text-sm pt5 gray-400"><?= Translate::get('enter'); ?> E-mail</div>
        </div>
        <?= Translate::get('invitations left'); ?> <?= 5 - $data['count_invites']; ?>
      </form>

      <h3><?= Translate::get('invited guests'); ?></h3>

      <?php if (!empty($data['invitations'])) { ?>

        <?php foreach ($data['invitations'] as $invite) { ?>
          <?php if ($invite['active_status'] == 1) { ?>
            <div class="text-sm gray">
              <?= user_avatar_img($invite['user_avatar'], 'small', $invite['user_login'], 'ava'); ?>
              <a href="<?= $invite['user_login']; ?>"><?= $invite['user_login']; ?></a>
              - <?= Translate::get('registered'); ?>
            </div>

            <?php if (UserData::checkAdmin()) { ?>
              <?= Translate::get('the link was used to'); ?>: 
              <?= $invite['invitation_email']; ?>
              <code class="block">
                <?= Config::get('meta.url'); ?><?= getUrlByName('invite.reg', ['code' => $invite['invitation_code']]); ?>
              </code>
            <?php } ?>

            <span class="text-sm gray"><?= Translate::get('link has been used'); ?></span>
          <?php } else { ?>
            <?= Translate::get('for'); ?> (<?= $invite['invitation_email']; ?>) 
            <?= Translate::get('can send this link'); ?>:
            <code class="block">
              <?= Config::get('meta.url'); ?><?= getUrlByName('invite.reg', ['code' => $invite['invitation_code']]); ?>
            </code>
          <?php } ?>

          <br><br>
        <?php } ?>

      <?php } else { ?>
        <?= Translate::get('no invitations'); ?>
      <?php } ?>

    <?php } else { ?>
      <?= Translate::get('limit-tl-invitation'); ?>.
    <?php } ?>
  </div>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('you can invite your friends'), 'uid' => $uid]); ?>