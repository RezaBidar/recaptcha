<h2>Add API</h2>
<?php 

echo btform::form_open();
echo btform::form_input($this->lang->line('app_form_label') . ' <span style="color:red">*</span>' , array('name' => 'label' , 'placeholder' => $this->lang->line('app_form_label_placeholder')));
echo btform::form_textarea($this->lang->line('app_form_domains') , array('name' => 'domains' , 'placeholder' => $this->lang->line('app_form_domains_placeholder')) , '' ,'', $this->lang->line('app_form_domains_help'));
echo btform::form_textarea($this->lang->line('app_form_english_words') , array('name' => 'en-words' , 'placeholder' => $this->lang->line('app_form_english_words_placeholder')) , '','' , $this->lang->line('app_form_words_help'));
echo btform::form_textarea($this->lang->line('app_form_persian_words') , array('name' => 'fa-words' , 'placeholder' => $this->lang->line('app_form_persian_words_placeholder')) , '' ,'', $this->lang->line('app_form_words_help'));
echo btform::form_submit(array("name"=>"submit" , "class"=>"btn btn-primary" ) , 'Save');
echo btform::form_close();