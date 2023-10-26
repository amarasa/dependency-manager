<?php
/*
Plugin Name: Dependency Manager
Description: This plugin is a container for the standard dependencies used.
Version: 1.1.0
Author: Angelo Marasa
Author URI: kaleidico.com
*/


/* -------------------------------------------------------------------------------------- */
// Updated
require 'puc/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/amarasa/dependency-manager',
    __FILE__,
    'dependency-manager-plugin'
);

//Set the branch that contains the stable release.
//$myUpdateChecker->setBranch('stable-branch-name');

//Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('your-token-here');

/* -------------------------------------------------------------------------------------- */


// Function to conditionally enqueue scripts and styles
function kaleidico_dependencies()
{
    $options = get_option('kaleidico_dependencies');

    if (isset($options['aoscss'])) {
        wp_enqueue_style("aoscss", "//cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css");
    }

    if (isset($options['fontawesomecss'])) {
        wp_enqueue_style("fontawesomecss", "//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css");
    }

    if (isset($options['jqueryvalidatejs'])) {
        wp_enqueue_script("jqueryvalidatejs", "//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js", ["jquery"], "0.7.2", true);
    }

    if (isset($options['matchheightjs'])) {
        wp_enqueue_script("matchheightjs", "//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js", ["jquery"], "1.19.3", true);
    }

    if (isset($options['alpinejs'])) {
        wp_enqueue_script("alpinejs", "//cdn.jsdelivr.net/gh/alpinejs/alpine@v2.3.5/dist/alpine.min.js?ver=2.3.5", ["jquery"], "2.3.5");
    }

    if (isset($options['aosjs'])) {
        wp_enqueue_script("aosjs", "//cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js", ["jquery"], "2.3.4", true);
    }

    if (isset($options['slickjs'])) {
        wp_enqueue_script('slickjs', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', ["jquery"], '1.8.1', true);
    }

    if (isset($options['slickcss'])) {
        wp_enqueue_style('slickcss', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css');
    }

    if (isset($options['slickthemecss'])) {
        wp_enqueue_style('slickthemecss', '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css');
    }
    if (isset($options['fluidvids'])) {
        wp_enqueue_script(
            'fluidvids',
            'https://cdnjs.cloudflare.com/ajax/libs/fluidvids.js/2.4.1/fluidvids.min.js',
            ["jquery"],
            "2.4.1",
            true
        );
    }
}
add_action("wp_enqueue_scripts", "kaleidico_dependencies");

// Add admin menu
function kaleidico_dependency_manager_add_menu()
{
    add_menu_page('Dependency Manager Options', 'Dependency Manager', 'manage_options', 'kaleidico-dependency-manager', 'kaleidico_dependency_manager_options_page');
    add_action('admin_init', 'kaleidico_dependency_manager_register_settings');
}
add_action('admin_menu', 'kaleidico_dependency_manager_add_menu');

// Register settings
function kaleidico_dependency_manager_register_settings()
{
    register_setting('kaleidico_dependency_manager_settings', 'kaleidico_dependencies');

    // Add settings section
    add_settings_section('kaleidico_dependency_manager_section', 'Manage Dependencies', 'section_callback_function', 'kaleidico_dependency_manager_settings');

    // Add settings fields for each dependency
    $dependencies = [
        'aoscss' => 'Animate on Site',
        'fontawesomecss' => 'Font Awesome',
        'jqueryvalidatejs' => 'jQuery Validate',
        'matchheightjs' => 'Match Height',
        'alpinejs' => 'AlpineJS',
        'aosjs' => 'AOS JS',
        'slickjs' => 'Slick Slider JS',
        'slickcss' => 'Slick Slider CSS',
        'slickthemecss' => 'Slick Slider Theme CSS',
        'fluidvids' => 'FluidVids'
    ];

    foreach ($dependencies as $key => $label) {
        add_settings_field($key, $label, 'field_callback_function', 'kaleidico_dependency_manager_settings', 'kaleidico_dependency_manager_section', ['name' => $key]);
    }
}

// Settings page content
function kaleidico_dependency_manager_options_page()
{
    echo '<h1>Dependency Manager Options</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('kaleidico_dependency_manager_settings');
    do_settings_sections('kaleidico_dependency_manager_settings');
    submit_button();
    echo '</form>';
}

// Sanitize callback
function sanitize_callback_function($input)
{
    return $input;
}

// Section callback
function section_callback_function()
{
    echo 'Toggle the dependencies you want to enable or disable.';
}

// Field callback
function field_callback_function($args)
{
    $options = get_option('kaleidico_dependencies');
    $name = $args['name'];
    $checked = isset($options[$name]) ? 'checked' : '';
    echo "<input type='checkbox' name='kaleidico_dependencies[$name]' value='1' $checked>";
}


function kaleidico_set_default_options()
{
    $default_options = [
        'aoscss' => 1,
        'fontawesomecss' => 1,
        'jqueryvalidatejs' => 1,
        'matchheightjs' => 1,
        'alpinejs' => 1,
        'aosjs' => 1,
        'slickjs' => 1,
        'slickcss' => 1,
        'slickthemecss' => 1,
        'fluidvids' => 1
    ];

    // Check if options already exist and if not, set default options
    if (false === get_option('kaleidico_dependencies')) {
        add_option('kaleidico_dependencies', $default_options);
    }
}

register_activation_hook(__FILE__, 'kaleidico_set_default_options');

function kaleidico_unset_default_options()
{
    delete_option('kaleidico_dependencies');
}
register_deactivation_hook(__FILE__, 'kaleidico_unset_default_options');
