<?php
// Pass only the variables that exist
$this->load->view("templates/header", compact("title"));
$this->load->view("templates/sidebar", compact("active"));
?>

<div class="layout-page">
<?php $this->load->view("templates/navbar"); ?>
  <div class="content-wrapper">
	  <div class="container-xxl flex-grow-1 container-p-y">
	    <?php $this->load->view($content); ?>
	  </div>
  </div>
  <div class="layout-overlay layout-menu-toggle"></div>
</div>

<?php $this->load->view("templates/footer"); ?>
