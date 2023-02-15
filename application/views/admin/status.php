 <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/talzo_logo.png">
<head>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/email.css"> 
</head>
<body>
<div class="email_msg_wrap">
<?php if($this->session->flashdata('success')){?>
<div class="content_data_email_verified">
<img src="<?php echo base_url()?>assets/images/email_verifier.png" alt="" width="200">
<h2><?php echo $this->session->flashdata('success'); ?></h2>
</div>
<?php }elseif ($this->session->flashdata('error')) {
?>
<div class="content_data_email_not_verified">
<img src="<?php echo base_url()?>assets/images/Deletion_icon.svg" alt="" width="200">
<h2> <?php echo $this->session->flashdata('error');?></h2>
</div>
<?php } else{?>
<div class="content_data_email_not_verified">
<img src="<?php echo base_url()?>assets/images/Deletion_icon.svg" alt="" width="200">
<h2> This link has been expired<br>Please try again.</h2>
</div>
<?php } ?>
</div>

</body>