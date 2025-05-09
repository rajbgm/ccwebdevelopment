<?php
// base filepath
$filepath = '';

// repeater layout
if (have_rows('layout')):
    while (have_rows('layout')):
        the_row();
        $repeater_class = 'repeater';
        $item_class = 'item';
        $layout_max = get_sub_field('layout_max') ?: '';
        $layout_min = get_sub_field('layout_min') ?: '';

        // desktop
        if ($layout_max > 0) {
            $repeater_class .= ' flex';
            if ($layout_max > 1) {
                $repeater_class .= ' wrap max-' . $layout_max;
            }

            // mobile
            if ($layout_min > 0) {
                $repeater_class .= ' min-' . $layout_min . ' flex-m';
            } else {
                $repeater_class .= ' global';
            }
        } else {
            $repeater_class .= ' swiper-wrapper';
            $item_class .= ' swiper-slide';
        }
    endwhile;
endif;

// repeater content
if (have_rows('repeater')):
    $col = 0;
    if ($layout_max < 0) {
        echo '<div class="swiper" id="' . $id . '-swiper">';
    }
    echo '<div class="' . $repeater_class . '">';
    while (have_rows('repeater')):
        $col++;
        the_row();
        $item_class_more = ' ' . get_sub_field('class') ?: '';
        if (get_sub_field('id')) {
            $id = get_sub_field('id');
        } else {
            $id = 'col-' . $col;
        }

        // background
        $item_style = 'opacity:1;';
        if (get_sub_field('background')) {
            $bg = get_sub_field('background') ?: '';
            $bg_mobile = get_sub_field('background_mobile') ?: $bg;
            $item_style .= '--bg-image-desktop:url(' . $bg . ');';
            $item_style .= '--bg-image-mobile:url(' . $bg_mobile . ');';
        }

        echo '<div class="' . $item_class . $item_class_more . ' col-' . $col . '" id="' . $id . '">';
        echo '<div class="inner-item" style="' . $item_style . '">';
        echo '<div class="content">';

        // features
        $repeater_features = get_sub_field('repeater_includes') ?: '';
        $i = 0;
        if ($repeater_features):
            foreach ($repeater_features as $repeater_feature):
                get_template_part($filepath . 'blocks/modules/repeater/repeater-' . $repeater_features[$i] . '.blade');
                //echo '<li>resources/views/blocks/modules/' . $repeater_features[$i] . '.blade</li>';
                $i++;
            endforeach;
        endif;
        echo '</div>';
        echo '</div>';
        echo '</div>';
    endwhile;
    echo '</div>';
    if ($layout_max < 0) {
        echo '<div class="swiper-pagination"></div>';
        echo '</div>';
    }
endif;
