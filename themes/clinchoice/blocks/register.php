<?php
if (function_exists('acf_register_block')) {

  // components

  // breadcrumbs
  acf_register_block_type(array(
    'name'              => 'breadcrumbs',
    'title'             => __('Breadcrumbs'),
    'description'       => __('Breadcrumbs'),
    'render_template'   => 'blocks/components/breadcrumbs.php',
    'category'          => 'common',
    'icon'              => 'ellipsis',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // buttons
  acf_register_block_type(array(
    'name'              => 'buttons',
    'title'             => __('Buttons'),
    'description'       => __('Buttons'),
    'render_template'   => 'blocks/components/buttons.php',
    'category'          => 'common',
    'icon'              => 'admin-links',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // headline
  acf_register_block_type(array(
    'name'              => 'headline',
    'title'             => __('Headline'),
    'description'       => __('Headline'),
    'render_template'   => 'blocks/components/headline.php',
    'category'          => 'common',
    'icon'              => 'editor-quote',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // image
  acf_register_block_type(array(
    'name'              => 'image',
    'title'             => __('Image'),
    'description'       => __('Image'),
    'render_template'   => 'blocks/components/image.php',
    'category'          => 'common',
    'icon'              => 'format-image',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // image background
  acf_register_block_type(array(
    'name'              => 'image_bg',
    'title'             => __('Background Image'),
    'description'       => __('Background Image'),
    'render_template'   => 'blocks/components/image_bg.php',
    'category'          => 'common',
    'icon'              => 'format-image',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // rich rext
  acf_register_block_type(array(
    'name'              => 'rich_text',
    'title'             => __('Archived'),
    'description'       => __('Rich Text'),
    'render_template'   => 'blocks/components/rich_text.php',
    'category'          => 'common',
    'icon'              => 'editor-paragraph',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // plain text
  acf_register_block_type(array(
    'name'              => 'plain_text',
    'title'             => __('Plain Text'),
    'description'       => __(''),
    'render_template'   => 'blocks/components/plain_text.php',
    'category'          => 'common',
    'icon'              => 'editor-paragraph',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // subhead
  acf_register_block_type(array(
    'name'              => 'subhead',
    'title'             => __('Subhead'),
    'description'       => __('Subhead'),
    'render_template'   => 'blocks/components/subhead.php',
    'category'          => 'common',
    'icon'              => 'editor-quote',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // text
  acf_register_block_type(array(
    'name'              => 'text',
    'title'             => __('Rich Text'),
    'description'       => __('Rich Text'),
    'render_template'   => 'blocks/components/text.php',
    'category'          => 'common',
    'icon'              => 'editor-paragraph',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));





  // widgets

  // events list
  acf_register_block_type(array(
    'name'              => 'event-list',
    'title'             => __('Event List'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/event-list.php',
    'category'          => 'widgets',
    'icon'              => 'calendar',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // expertise
  acf_register_block_type(array(
    'name'              => 'expertise',
    'title'             => __('Expertise'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/expertise.php',
    'category'          => 'widgets',
    'icon'              => 'admin-post',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // home - featured insights
  acf_register_block_type(array(
    'name'              => 'post-slider',
    'title'             => __('Post Slider'),
    'description'       => __('Post Slider'),
    'render_template'   => 'blocks/widgets/post-slider.php',
    'category'          => 'widgets',
    'icon'              => 'admin-post',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // video
  acf_register_block_type(array(
    'name'              => 'video',
    'title'             => __('Video'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/video.php',
    'category'          => 'widgets',
    'icon'              => 'admin-post',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // insights - post list
  acf_register_block_type(array(
    'name'              => 'post-list',
    'title'             => __('Post List'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/post-list.php',
    'category'          => 'widgets',
    'icon'              => 'admin-post',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

   // insights - post grid
   acf_register_block_type(array(
    'name'              => 'post-grid',
    'title'             => __('Post Grid'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/post-grid.php',
    'category'          => 'widgets',
    'icon'              => 'admin-post',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // leadership
  acf_register_block_type(array(
    'name'              => 'leadership',
    'title'             => __('Leadership'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/leadership.php',
    'category'          => 'widgets',
    'icon'              => 'groups',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // locations - hq
  acf_register_block_type(array(
    'name'              => 'hq',
    'title'             => __('Headquarters Information'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/hq.php',
    'category'          => 'widgets',
    'icon'              => 'admin-site',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // locations - map
  acf_register_block_type(array(
    'name'              => 'map',
    'title'             => __('Map + Locations'),
    'description'       => __('Map'),
    'render_template'   => 'blocks/widgets/map.php',
    'category'          => 'widgets',
    'icon'              => 'admin-site',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // regulatory intelligence
  acf_register_block_type(array(
    'name'              => 'regulatory-intelligence',
    'title'             => __('Regulatory Intelligence'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/regulatory-intelligence.php',
    'category'          => 'widgets',
    'icon'              => 'editor-table',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // testimonials
  acf_register_block_type(array(
    'name'              => 'testimonials',
    'title'             => __('Testimonials'),
    'description'       => __(''),
    'render_template'   => 'blocks/widgets/testimonials.php',
    'category'          => 'widgets',
    'icon'              => 'testimonial',
    'mode'              => 'edit',
    'keywords'          => array('')
  ));

  // Swiper Slider
  acf_register_block_type([
    'name'              => 'swiper-slider',
    'title'             => __('Swiper Slider'),
    'description'       => __('A custom slider block using Swiper.js'),
    'render_template'   => 'blocks/widgets/slider.php',
    'category'          => 'formwidgetsatting',
    'icon'              => 'images-alt2',
    'keywords'          => ['slider', 'swiper', 'carousel'],
    'mode'              => 'edit',
    'supports' => ['align' => true],
]);
}
