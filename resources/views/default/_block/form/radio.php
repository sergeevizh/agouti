<?php foreach ($data as $value) :
  $checked  = $value['checked'] ?? 0;
  $help     = $value['help'] ?? null;
?>
  <fieldset>
    <label><?= $value['title']; ?></label>
    <input type="radio" name="<?= $value['name']; ?>" <?php if ($checked == 0) : ?>checked<?php endif; ?> value="0">
    <span class="checkmark"></span>      
    <?= __('no'); ?>

    <input type="radio" name="<?= $value['name']; ?>" <?php if ($checked == 1) : ?>checked<?php endif; ?> value="1">
    <span class="checkmark"></span>      
      <?= __('yes'); ?>
    <?php if ($help) : ?><div class="mt5 text-sm gray-600"><?= $help; ?></div><?php endif; ?>
  </fieldset>
<?php endforeach; ?>