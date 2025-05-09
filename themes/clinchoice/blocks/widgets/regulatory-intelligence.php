<div class="wrapper">

  <div class="sort_dropdown">
    <?php echo facetwp_display('facet', 'regulatory_country_region'); ?>
    <?php echo facetwp_display('facet', 'regulatory_health_authority'); ?>
    <?php echo facetwp_display('facet', 'regulatory_date'); ?>
    <?php echo facetwp_display('facet', 'regulatory_product_type'); ?>
    <?php echo facetwp_display('facet', 'regulatory_domain'); ?>
  </div>

  <?php
  // Define the query arguments
  $args = [
    "post_type"      => ["regulatory"],
    "posts_per_page" => 25,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
    // ... your arguments
    "facetwp"        => true //This flags this custom query as the one to be used by FacetWP
  ];

  // Run the query
  $my_query = new WP_Query($args);
  ?>

  <table id="regulatory-list" class="main_table">
    <thead>
      <tr>
        <th>Country/Region</th>
        <th>Health Authority</th>
        <th>Date of Publishing</th>
        <th>Product Type</th>
        <th>Topic</th>
        <th>Domain</th>
        <th>URL</th>
      </tr>
    </thead>
    <tbody>

      <?php
      if ($my_query->have_posts()) :
        while ($my_query->have_posts()) :
          $my_query->the_post();
          $id = get_the_id();
          $country_region = get_field('country_region', $id) ?? null;
          $health_authority = get_field('health_authority', $id) ?? null;
          $product_type = get_field('product_type', $id) ?? null;
          $domain = get_field('domain', $id) ?? null;
          $url = get_field('url', $id) ?? null;
          $date_string = get_field('date', $id);
          $date_default = new DateTime($date_string);
          $date = $date_default->format('d-m-y');
      ?>
          <tr>
            <td class="col-country" data-val="<?php echo $country_region ?>"><?php echo $country_region ?></td>
            <td width="220" class="col-health_authority" data-val="<?php echo $health_authority ?>"><?php echo $health_authority ?></td>
            <td class="col-date" data-val="<?php echo $date_string ?>"><?php echo $date; ?></td>
            <td class="col-product_type" data-val="<?php echo $product_type ?>"><?php echo $product_type ?></td>
            <td width="520"><?php the_title() ?></td>
            <td class="col-domain" data-val="<?php echo $domain ?>"><?php echo $domain ?></td>
            <td><a href="<?php echo $url ?>" target="_blank">View Page</a></td>
          </tr>
        <?php
        endwhile;
      else :
        ?>
        <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
      <?php
      endif;
      wp_reset_postdata();
      ?>

    </tbody>
  </table>

  <div id="regulatory-pager" class="pager">
    <?php echo facetwp_display('facet', 'regulatory_pager'); ?>
  </div>

</div>