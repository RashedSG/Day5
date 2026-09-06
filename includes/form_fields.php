<?php
declare(strict_types=1);
/** @var array $old */
/** @var array $errors */
?>
<div class="field">
  <label for="name">اسم الأصل</label>
  <input type="text" id="name" name="name" maxlength="120" value="<?= e($old['name']) ?>"
         <?= isset($errors['name']) ? 'aria-invalid="true"' : '' ?>>
  <?php if (isset($errors['name'])): ?><p class="error"><?= e($errors['name']) ?></p><?php endif; ?>
</div>
<div class="field">
  <label for="code">الرمز / الرقم التسلسلي</label>
  <input type="text" id="code" name="code" maxlength="40" dir="ltr" value="<?= e($old['code']) ?>"
         <?= isset($errors['code']) ? 'aria-invalid="true"' : '' ?>>
  <?php if (isset($errors['code'])): ?><p class="error"><?= e($errors['code']) ?></p><?php endif; ?>
  <p class="hint">اختياري. مثال: IT-0142</p>
</div>
<div class="field">
  <label for="category">الفئة</label>
  <select id="category" name="category" <?= isset($errors['category']) ? 'aria-invalid="true"' : '' ?>>
    <option value="">اختر الفئة</option>
    <?php foreach (CATEGORIES as $slug => $label): ?>
      <option value="<?= e($slug) ?>" <?= $old['category'] === $slug ? 'selected' : '' ?>><?= e($label) ?></option>
    <?php endforeach; ?>
  </select>
  <?php if (isset($errors['category'])): ?><p class="error"><?= e($errors['category']) ?></p><?php endif; ?>
</div>
<div class="field">
  <label for="status">الحالة</label>
  <select id="status" name="status" <?= isset($errors['status']) ? 'aria-invalid="true"' : '' ?>>
    <?php foreach (STATUSES as $slug => $label): ?>
      <option value="<?= e($slug) ?>" <?= $old['status'] === $slug ? 'selected' : '' ?>><?= e($label) ?></option>
    <?php endforeach; ?>
  </select>
  <?php if (isset($errors['status'])): ?><p class="error"><?= e($errors['status']) ?></p><?php endif; ?>
</div>
<div class="field">
  <label for="location">الموقع</label>
  <input type="text" id="location" name="location" maxlength="80" value="<?= e($old['location']) ?>"
         <?= isset($errors['location']) ? 'aria-invalid="true"' : '' ?>>
  <?php if (isset($errors['location'])): ?><p class="error"><?= e($errors['location']) ?></p><?php endif; ?>
</div>
<div class="field">
  <label for="purchase_date">تاريخ الشراء</label>
  <input type="date" id="purchase_date" name="purchase_date" dir="ltr"
         max="<?= e(date('Y-m-d')) ?>" value="<?= e($old['purchase_date']) ?>"
         <?= isset($errors['purchase_date']) ? 'aria-invalid="true"' : '' ?>>
  <?php if (isset($errors['purchase_date'])): ?>
    <p class="error"><?= e($errors['purchase_date']) ?></p>
  <?php else: ?>
    <p class="hint">الصيغة YYYY-MM-DD. لا يُقبل تاريخ في المستقبل.</p>
  <?php endif; ?>
</div>
<div class="field">
  <label for="notes">ملاحظات</label>
  <textarea id="notes" name="notes" maxlength="500" <?= isset($errors['notes']) ? 'aria-invalid="true"' : '' ?>><?= e($old['notes']) ?></textarea>
  <?php if (isset($errors['notes'])): ?><p class="error"><?= e($errors['notes']) ?></p><?php endif; ?>
</div>
