</main>

<?php
global $assets;
global $images;
?>

<footer>
  <div class="wrapper">
    <div class="logo-social">
      <div class="logo">
        <a href="<?php echo home_url() ?>">
          <img src="<?php echo home_url() ?>/wp-content/uploads/clinchoice_logo.svg">
        </a>
      </div>
      <div class="social">
        <ul>
          <li><a href="https://www.linkedin.com/company/clinchoice/" target="_blank"><img src="<?php echo $images . '/social-linkedin.svg' ?>" class="linkedin" /></a></li>
          <li><a href="https://twitter.com/ClinChoice" target="_blank"><img src="<?php echo $images . '/social-x.svg' ?>" class="x" /></a></li>
          <li><a href="https://www.youtube.com/channel/UC7XvyulVTckU9rHrvdwtkdA?app=desktop" target="_blank"><img src="<?php echo $images . '/social-youtube.svg' ?>" class="youtube" /></a></li>
        </ul>
        <p class="copyright desktop">&copy; <?php echo date('Y') ?> ClinChoice.<br>All Rights Reserved.</p>
      </div>
    </div>
    <div class="contact-quicklinks">
      <div class="flex">
        <div class="contact">
          <p><strong>ClinChoice International<br>Headquarters</strong></p>
          <ul>
            <?php if (have_rows('multi', 547)) : ?>


              <?php while (have_rows('multi', 547)) : the_row(); ?>

                <?php
                $phone = get_sub_field('phone_multi');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                $email = get_sub_field('email_multi');
                $address = get_sub_field('address_multi');
                ?>

                <li>
                  <h3 class="eyebrow-3">Phone</h3>
                  <a href="tel:<?php echo $phone_clean ?>"><?php echo $phone ?></a>
                </li>
                <li>
                  <h3 class="eyebrow-3">Email</h3>
                  <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
                </li>
                <li>
                  <h3 class="eyebrow-3">Address</h3>
                  <?php echo $address ?>
                </li>

              <?php endwhile; ?>

            <?php endif; ?>

          </ul>

        </div>
        <div class="quicklinks">
          <div class="sectors-solutions">
            <div class="sectors">
              <h3 class="eyebrow-3">Sectors</h3>
              <ul>
                <li><a href="/sectors/pharma-biotech/">Pharma &amp; Biotech</a></li>
                <li><a href="/sectors/medical-devices-diagnostics/">Medical Devices &amp; Diagnostics</a></li>
                <li><a href="/sectors/consumer-health/">Consumer Health</a></li>
                <li><a href="/sectors/other">Other</a></li>
              </ul>
            </div>
            <div class="solutions">
              <h3 class="eyebrow-3">Solutions</h3>
              <ul>
                <li><a href="/solutions/clinical-development/">Clinical Development</a></li>
                <li><a href="/solutions/post-marketing-real-world-evidence/">Post-Marketing &amp;<br>Real-World Evidence</a></li>
                <li><a href="/solutions/project-management/">Project Management</a></li>
                <li><a href="/solutions/quality-compliance/">Quality &amp; Compliance</a></li>
                <li><a href="/solutions/technology/">Technology</a></li>
                <li><a href="/solutions/consulting/">Consulting</a></li>
              </ul>
            </div>
          </div>
          <div class="delivery-expertise-about">
            <div class="delivery-models">
              <div>
                <h3 class="eyebrow-3">Delivery Models</h3>
                <ul>
                  <li><a href="/delivery-models/full-service-outsourcing/">Full-Service Solutions</a></li>
                  <li><a href="/delivery-models/fsp/">FSP Solutions</a></li>
                </ul>
              </div>
              <div class="expertise">
                <h3 class="eyebrow-3">Expertise</h3>
                <ul>
                  <li><a href="/expertise/therapeutic-areas/">Therapeutic Areas</a></li>
                  <li><a href="/expertise/specialties/">Specialties</a></li>
                </ul>
              </div>
              <div class="about">
                <h3 class="eyebrow-3">About</h3>
                <ul>
                  <li><a href="/about/company-overview/">Company Overview</a></li>
                  <li><a href="/about/why-clinchoice/">Why ClinChoice</a></li>
                  <li><a href="/about/environmental-social-governance/">Environmental, Social &amp; Governance</a></li>
                  <li><a href="/about/leadership/">Leadership</a></li>
                  <li><a href="/about/locations-global-reach/">Locations & Global Reach</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="other">
            <div class="other">
              <h3 class="eyebrow-3">Other Links</h3>
              <ul class="sub-menu">
                <li id="menu-item-283" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-283"><a href="/investigators/">Investigators</a></li>
                <li id="menu-item-284" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-284"><a href="/careers/">Careers</a></li>
                <li id="menu-item-285" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-285"><a href="/insights/">Insights</a></li>
                <li id="menu-item-2318" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2318"><a href="/rfi-rfp/">RFI/RFP</a></li>
                <li id="menu-item-286" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-286"><a href="/contact-us/">Contact Us</a></li>
                <li id="menu-item-289" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-privacy-policy menu-item-289"><a rel="privacy-policy" href="/privacy-policy/">Privacy &amp; Cookies Policies</a></li>
                <li id="menu-item-290" class="cky-btn-revisit menu-item menu-item-type-custom menu-item-object-custom menu-item-290"><a href="#" class="cky-banner-element">Cookies Preferences</a></li>
                <li id="menu-item-291" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-291"><a href="/terms-and-conditions">Terms &amp; Conditions</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <p class="copyright mobile">&copy; <?php echo date('Y') ?> ClinChoice. All Rights Reserved.</p>
</footer>

<?php
if (!is_home()) {
  $page = $post->post_name;
} else {
  $page = 'insights';
}
?>

<script src="<?php echo get_template_directory_uri() ?>/assets/scripts/plugins.js"></script>
<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js-na2.hs-scripts.com/242728231.js"></script>
<!-- End of HubSpot Embed Code -->
<?php wp_footer(); ?>

</body>

</html>