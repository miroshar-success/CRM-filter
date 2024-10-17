<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php foreach($technical_items as $index => $technical_item): ?>
<div class="form-group d-flex align-items-center">
    <input 
        type="checkbox" 
        class="technical_invoice_item_check"
        id="technical_invoice_item_<?= $index; ?>" 
        name="technical_invoice_item[<?= $index; ?>]" 
        value="<?= $technical_item['rate'] ?>" 
        <?= $technical_item['is_matched'] ? 'checked' : ''; ?>
    >
    <input type="hidden" class="technical_item_ids" value="<?= $technical_item['id']; ?>" name="technical_item_ids">
    <input type="hidden" class="itemable_id" value="<?= $technical_item['itemableid']; ?>" name="itemable_id">
    <label for="technical_invoice_item_<?= $index; ?>" class="control-label ml-2 mb-0">
        <?= $technical_item['description'] . ': '; ?>
        <span class="price"><?= intval($technical_item['rate']) ?></span>€
    </label>
</div>
<?php endforeach; ?>
<input type="hidden" name="checked_item_ids" id="checked_item_ids">
<input type="hidden" name="technical_items_total" class="technical_items_totals">
<div id="removed_items"></div>
