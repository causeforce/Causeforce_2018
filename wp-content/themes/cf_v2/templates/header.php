<header class="banner">
  <div class="container-fluid menu-cont">
<!--    <a class="brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>-->
    <a class="brand" href="<?= esc_url(home_url('/')); ?>">
        <img src="../wp-content/uploads/2017/10/logo_01.svg" alt="Causeforce LLC">
        <img class="cf-infi" src="../wp-content/uploads/2017/10/Group-236.svg" alt="Logo">
    </a>
    <nav class="cf-nav">
        <div class="block">
            <a href="#" class="bt-menu">
              <span></span>
            </a>
        </div>
    </nav>
    <div class="mobile-overlay">
      <a class="brand mobile-brand" href="<?= esc_url(home_url('/')); ?>">
          <img src="../wp-content/uploads/2017/10/logo_01.svg" alt="Causeforce LLC">
          <img class="cf-infi" src="../wp-content/uploads/2017/10/Group-236.svg" alt="Logo">
       </a>
       <div class="container">
           <div class="row links-row">
               <div class="col-12">
                    <div class="burger_menu">
                        <?php
                        if (has_nav_menu('primary_navigation')) :
                            wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
                        endif;
                        ?>
                    </div>
                    <div class="mobile-footer">
                        <a href="https://www.facebook.com/CauseForce/"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                        <a href="https://www.linkedin.com/company/54640/"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>
                    </div>
               </div>
           </div>
        </div>
    </div>
  </div>
</header>
