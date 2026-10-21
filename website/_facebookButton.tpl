
<?php
$openid_facebook_button_style = $this->openid_facebook_button_style;
$openid_facebook_landingpage = !empty($this->openid_facebook_landingpage ) ? $this->openid_facebook_landingpage : $this->url(array('openidservice' => 'facebook' ), 'socialdna_login' );
?>

<?php if($openid_facebook_button_style == 2): ?>
  <a href="javascript:void(0)" class="openidconnect_facebook_login_button">
  <img id="fb_login_image" src="application/modules/Socialdna/externals/images/brands/facebook32.png" alt="Facebook Connect" border="0" />
  </a>
<?php elseif($openid_facebook_button_style == 3) : ?>
  <a href="javascript:void(0)" class="openidconnect_facebook_login_button">
  <img id="fb_login_image" src="application/modules/Socialdna/externals/images/brands/logo_facebook_mini.png" alt="Facebook Connect" border="0" />
  </a>
<?php else: ?>
<!--<div>-->
  <a href="javascript:void(0)" class="openidconnect_facebook_login_button">
  <img id="fb_login_image" src="http://static.ak.fbcdn.net/images/fbconnect/login-buttons/connect_light_medium_long.gif" alt="Facebook Connect" border="0" />
  </a>
<!--</div>-->
<?php endif; ?>

<script type="text/javascript">
openidconnect_register_facebook_login_button('<?php echo $openid_facebook_landingpage ?>');
</script>