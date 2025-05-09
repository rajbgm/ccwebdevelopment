<?php if (have_rows('multi', 547)) : ?>
  <?php while (have_rows('multi', 547)) : the_row(); ?>

    <?php
    $phone = get_sub_field('phone_multi');
    $phone_clean = preg_replace('/[^0-9]/', '', $phone);
    $email = get_sub_field('email_multi');
    $address = str_replace('USA', '', get_sub_field('address_multi'));
    ?>

    <div class="hq">
      <h3 class="h3c"><?php echo get_the_title(547) ?></h3>
      <div class="flex">
        <div class="address">
          <p><?php echo $address ?></p>
        </div>
        <div class="contact">
          <h3 class="eyebrow-1 v1">Phone</h3>
          <p><?php echo $phone ?></p>
          <h3 class="eyebrow-1 v1">Email</h3>
          <p><a href="mailto:<?php echo $email ?>"><?php echo $email ?></a></p>
        </div>
      </div>
    </div>

  <?php endwhile; ?>
<?php endif; ?>