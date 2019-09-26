<?php

/**
 * Plugin Name: Badili Projects
 * Description: The badili customization of the projects page
 * Version: 1.0
 * Author: Badili Innovations
 *
 */
defined('ABSPATH') or exit();
define('PROJECTS_PAGE', 'all-projects-2');

class BadiliProjects {

    public function __construct() {
        //coming soon template enabled in settings
        add_filter('page_template', function ($page_template) {
            if (is_page(PROJECTS_PAGE)) {
                $page_template = __DIR__ . '/views/projects.php';
            }
            return $page_template;
        });

        add_filter('language_attributes', function( $attr ) {
            if (is_page(PROJECTS_PAGE)) {
                return "{$attr} ng-app=\"myApp\"";
            } else {
                return $attr;
            }
        });

        //for local dev, reload js with each request
        $timestamp = (site_url() == 'http://localhost/sasha') ? time() : ''; //only for dev purposes
        $timestamp = '?t=' . $timestamp;

        wp_register_script('angular-sanitize', plugins_url('assets/dist/lib/angular-sanitize.min.js', __FILE__), array('angular-js'));
        wp_register_script('badili-projects-js', plugins_url('assets/dist/js/badili-projects-combined.min.js' . $timestamp, __FILE__), array('jquery', 'angular-sanitize'), '', true);

        add_action('wp_enqueue_scripts', function () {
            if (is_page(PROJECTS_PAGE)) {
                $args = array(
                    'post_type' => 'projects',
                    'posts_per_page' => -1,
                    'orderby' => 'post_title',
                    'order' => 'ASC'
                );
                $projects = get_posts($args);
                $projects_array = array();

                foreach ($projects as $key => $project) {
                    $project = (array) $project;

                    //get the image
                    if (has_post_thumbnail($project['ID'])) {
                        $project['thumbnail'] = get_the_post_thumbnail_url($project['ID'], 'thumbnail');
                        $project['thumbnail_full'] = get_the_post_thumbnail_url($project['ID'], 'full');
                    } else {
                        //todo: replace this
                        $project['thumbnail'] = site_url('/wp-content/uploads/2016/11/IMG-20160703-WA0007-ed-150x150.jpg');
                        $project['thumbnail_full'] = site_url('/wp-content/uploads/2016/11/IMG-20160703-WA0007-ed.jpg');
                    }

                    //the project status
                    $status = wp_get_post_terms($project['ID'], 'spkp_project_status', array("fields" => "names"));
                    $project['status'] = implode(',', $status);
                    $project['project_region'] = get_post_meta($project['ID'], 'project_region', true);
                    $project['permalink'] = get_permalink($project['ID']);                    
                    $project['collaborating_orgs'] = get_post_meta($project['ID'], 'project_collaborating_org', true);
                    $project['countries'] = get_post_meta($project['ID'], 'project_countries', true);


                    //the project meta data

                    $project['meta'] = array();
                    //copied straight from here: /wp-content/plugins/sasha_manager/templates/parts/key_information.php


                    if (get_post_meta($project['ID'], 'project_leader', true) && get_post_meta($project['ID'], 'project_leader', true) != 'selected="selected"') {

                        $uid = get_post_meta($project['ID'], 'project_leader', true);
                        $terms = wp_get_post_terms($post->ID, "spkp_project_status");
                        $term = $terms[0]->slug;
                        $project['meta']['Leader'] = author_link($uid);
                    };

                    if (!empty($start_year)) {

                        $date_start = ($start_day && is_numeric($start_day) ? $start_day . '-' : '') . ($start_month && is_numeric($start_month) ? $start_month . '-' : '') . ($start_year ? $start_year : '');
                        $project['meta']['Start date'] = $date_start;
                    }
                    if (!empty($end_year)) {

                        $date_end = ($end_day && is_numeric($end_day) ? $end_day . '-' : '') . ($end_month && is_numeric($end_month) ? $end_month . '-' : '') . ($end_year ? $end_year : '');
                        $project['meta']['End date'] = $date_end;
                    }

                    if (get_post_meta($project['ID'], 'project_lead_organization', true)) {
                        $project['meta']['Lead organization'] = get_post_meta($project['ID'], 'project_lead_organization', true);
                    }

                    if (get_post_meta($project['ID'], 'project_collaborating_org', true)) {
                        $project['meta']['Collaborating organizations'] = get_post_meta($project['ID'], 'project_collaborating_org', true);
                    }

                    if (get_post_meta($project['ID'], 'project_region', true)) {
                        $project['meta']['Region'] = get_post_meta($project['ID'], 'project_region', true);
                    }

                    if (get_post_meta($project['ID'], 'project_countries', true)) {
                        $project['meta']['Countries'] = get_post_meta($project['ID'], 'project_countries', true);
                    }

                    if (!empty($terms)) {
                        $project['meta']['Status'] = ucfirst($term);
                    }

                    if (get_post_meta($project['ID'], 'project_type', true)) {

                        switch (get_post_meta($project['ID'], 'project_type', true)) {
                            case 'development-dissemination':
                                $project['meta']['Type of project'] = 'Development / Dissemination';
                                break;

                            case 'research':
                                $project['meta']['Type of project'] = 'Research';
                                break;

                            case 'both':
                                $project['meta']['Type of project'] = 'Development / Dissemination and Research';
                                break;
                        }
                    }

                    if (get_post_meta($project['ID'], 'project_financing', true)) {
                        $project['meta']['Financing Sources'] = get_post_meta($project['ID'], 'project_financing', true);
                    }

                    if (get_post_meta($project['ID'], 'project_founding', true)) {
                        $project['meta']['Funding Amount (USD)'] = number_format(intval(get_post_meta($project['ID'], 'project_founding', true)));
                    }

                    $pareas = get_post_meta($project['ID']);
                    $areas_intervention = '';
                    for ($i = 1; $i < 13; $i++) {
                        if (!empty($pareas['project-areas-' . $i][0])) {
                            $areas_intervention .= '<span class="key-info-text">' . ucfirst(str_replace('-', ' ', $pareas['project-areas-' . $i][0])) . '</span>, ';
                        }
                    }

                    if ($areas_intervention != '') {
                        $project['meta']['Areas of Intervention'] = rtrim($areas_intervention, ', ');
                    }


                    // global $wpdb;
                    // $current_members = get_project_members($project['ID']);
                    // $list = array();
                    // foreach ($current_members as $member) {
                    //     $list[] = author_link($member);
                    // }

                    // if (count($list) > 0) {
                    //     $project['meta']['Members'] = implode(", ", $list);
                    // }

                    //end project meta data

                    array_push($projects_array, $project);
                }

                wp_enqueue_script('badili-projects-js');
                wp_localize_script('badili-projects-js', 'PROJECTS', array(
                    'projects' => $projects_array
                ));

                wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
            }
        });
    }

}

new BadiliProjects();

