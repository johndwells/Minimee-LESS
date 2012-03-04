<?=form_open('C=addons_extensions'.AMP.'M=save_extension_settings'.AMP.'file=minimee_less');?>

<?php

$this->table->set_template($cp_pad_table_template);
$this->table->set_heading(
    array('data' => lang('preference'), 'style' => 'width:40%;'),
    lang('setting')
);



$label = array(
	'data' => lang('enable', 'enable'),
	'style' => 'vertical-align: top'
);
$setting = form_dropdown('enable', array('yes' => lang('yes'), 'no' => lang('no')), $enable, 'id="enable"');
$this->table->add_row($label, $setting);

$label = array(
	'data' => lang('import_dirs', 'import_dirs') . '<br />' . lang('import_dirs_note'),
	'style' => 'vertical-align: top'
);
$setting = form_textarea(array('name' => 'import_dirs', 'id' => 'import_dirs', 'value' => $import_dirs));
$this->table->add_row($label, $setting);

echo $this->table->generate();

?>

<p><?=form_submit('submit', lang('save'), 'class="submit"')?></p>
<?php $this->table->clear()?>
<?=form_close()?>

<script type="text/javascript">
	jQuery(function($) {
	
		<?php if ($flashdata_success) : ?>
			$.ee_notice( '<?php echo $flashdata_success; ?>' , {type: "success", open:false}); 
		<?php endif; ?>

	});
</script>


<?php
/* End of file index.php */
/* Location: ./system/expressionengine/third_party/minimee_less/views/settings_form.php */
