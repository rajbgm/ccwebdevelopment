<?php
/**
 * Plugin Name: Greenhouse Job Listings
 * Description: Fetch and display job listings from Greenhouse API.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register shortcode
add_shortcode('greenhouse_jobs', 'ghjl_display_jobs');

function ghjl_display_jobs($atts) {
    $atts = shortcode_atts([
        'company' => 'yourcompany',
        'detail_page' => '', // Add a new option
    ], $atts, 'greenhouse_jobs');

    $jobs = ghjl_fetch_jobs($atts['company']);

    if (!$jobs) {
        return '<p>No job listings found.</p>';
    }

    $output = '<div class="greenhouse-jobs">';
    foreach ($jobs as $job) {
        $job_id = $job['id'];
        $detail_url = !empty($atts['detail_page'])
            ? add_query_arg(['job_id' => $job_id], esc_url($atts['detail_page']))
            : esc_url($job['absolute_url']); // fallback to Greenhouse link

        $output .= '<div class="job-item">';
        $output .= '<h3><a href="' . $detail_url . '">' . esc_html($job['title']) . '</a></h3>';
        if (!empty($job['location']['name'])) {
            $output .= '<p><strong>Location:</strong> ' . esc_html($job['location']['name']) . '</p>';
        }
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}

// Fetch jobs from Greenhouse API
function ghjl_fetch_jobs($company) {
    $cache_key = 'ghjl_jobs_' . $company;
    $jobs = get_transient($cache_key);

    if ($jobs !== false) {
        return $jobs;
    }

    $url = 'https://boards-api.greenhouse.io/v1/boards/' . $company . '/jobs';

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($data['jobs'])) {
        return false;
    }

    set_transient($cache_key, $data['jobs'], HOUR_IN_SECONDS);

    return $data['jobs'];
}


// Shortcode to display a single job detail
add_shortcode('greenhouse_job_detail', 'ghjl_display_job_detail');

function ghjl_display_job_detail($atts) {
    if (empty($_GET['job_id'])) {
        return '<p>No job ID provided.</p>';
    }

    $atts = shortcode_atts([
        'company' => 'yourcompany',
    ], $atts, 'greenhouse_job_detail');

    $job_id = intval($_GET['job_id']);
    $job = ghjl_fetch_job_detail($atts['company'], $job_id);

    if (!$job) {
        return '<p>Job not found.</p>';
    }

    $output = '<div class="greenhouse-job-detail">';
    $output .= '<h2>' . esc_html($job['title']) . '</h2>';
    if (!empty($job['location']['name'])) {
        $output .= '<p><strong>Location:</strong> ' . esc_html($job['location']['name']) . '</p>';
    }
    if (!empty($job['content'])) {
        $output .= '<div>' . $job['content'] . '</div>'; // This is HTML from Greenhouse
    }
    $output .= '<p><a href="' . esc_url($job['absolute_url']) . '" target="_blank">Apply Now</a></p>';
    $output .= '</div>';

    return $output;
}

// Fetch a single job detail
function ghjl_fetch_job_detail($company, $job_id) {
    $url = 'https://boards-api.greenhouse.io/v1/boards/' . $company . '/jobs/' . $job_id;

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return false;
    }

    $job = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($job['id'])) {
        return false;
    }

    return $job;
}

add_shortcode('greenhouse_job_detail_iframe', 'ghjl_display_job_detail_iframe');

function ghjl_display_job_detail_iframe($atts) {
    if (empty($_GET['job_id'])) {
        return '<p>No job ID provided.</p>';
    }

    $atts = shortcode_atts([
        'company' => 'yourcompany',
        'height' => '800px', // Allow custom height if needed
    ], $atts, 'greenhouse_job_detail');

    $job_id = intval($_GET['job_id']);
    $job_url = 'https://boards.greenhouse.io/' . $atts['company'] . '/jobs/' . $job_id;

    $output = '<div class="greenhouse-job-iframe">';
    $output .= '<iframe src="' . esc_url($job_url) . '" width="100%" height="' . esc_attr($atts['height']) . '" style="border:0;"></iframe>';
    $output .= '</div>';

    return $output;
}
//add_shortcode('greenhouse_job_embed', 'ghjl_display_job_embed');

function ghjl_display_job_embed($atts) {
    if (empty($_GET['job_id'])) {
        return '<p>No job ID provided.</p>';
    }

    $atts = shortcode_atts([
        'company' => 'yourcompany',
    ], $atts, 'greenhouse_job_embed');

    $job_id = intval($_GET['job_id']);

    ob_start();
    ?>
    <div id="grnhse_app"></div>
    <script src="https://boards.greenhouse.io/embed/job_app?for=<?php echo esc_attr($atts['company']); ?>&token=<?php echo esc_attr($job_id); ?>"></script>
    <?php
    return ob_get_clean();
}
add_shortcode('greenhouse_job_embed', 'ghjl_display_job_embed_debug');

function ghjl_display_job_embed_debug($atts) {
    if (empty($_GET['job_id'])) {
        return '<p>No job ID provided in the URL.</p>';
    }

    $atts = shortcode_atts([
        'company' => 'yourcompany',
    ], $atts, 'greenhouse_job_embed');

    $job_id = intval($_GET['job_id']);

    ob_start();
    ?>
    <div id="grnhse_app">
        <p>Loading job details...</p>
    </div>
    <script type="text/javascript">
        console.log('Embedding job: Company=<?php echo esc_js($atts['company']); ?>, Job ID=<?php echo esc_js($job_id); ?>');
    </script>
    <script src="https://boards.greenhouse.io/embed/job_app?for=<?php echo esc_attr($atts['company']); ?>&token=<?php echo esc_attr($job_id); ?>"></script>
    <?php
    return ob_get_clean();
}
