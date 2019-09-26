<?php
/**
 * Plugin Name: Badili SPHI Dashboard
 * Plugin URI: http://
 * Description: This plugin adds a SPHI dashboard for displaying SPHI metrics
 * Version: 1.1
 * Author: Badili Innovations
 * Author URI: https://badili.co.ke
 */

defined('ABSPATH') or exit();
define('SPHI_DASHBOARD', 'sphi-dashboard');

class BadiliSPHIDash{
    public function __construct() {
        $this->register_scripts();
        $this->load_scripts();

        // now load the template
        add_filter('page_template', function ($page_template) {
            return $this->load_default_view($page_template);
        });
    }

    private function register_scripts(){
        // css styles
        wp_register_style('badili-inspinia-style-css', plugins_url("assets/src/css/inspinia_style.css", __FILE__));
        wp_register_style('bootstrap-css', plugins_url('assets/lib/bootstrap/dist/css/bootstrap.css', __FILE__));
        wp_register_style('leaflet-css', plugins_url('assets/lib/leaflet/dist/leaflet.css', __FILE__));
        
        wp_register_style('badili-sphi-dash-css', plugins_url("assets/src/css/badili-sphi-dash.css", __FILE__));

        wp_register_style('marker-cluster-css', plugins_url("assets/lib/leaflet.markercluster/MarkerCluster.css", __FILE__));
        wp_register_style('marker-cluster-default-css', plugins_url("assets/lib/leaflet.markercluster/MarkerCluster.Default.css", __FILE__));

        //for local dev, reload js with each request
        $timestamp = (site_url() == 'http://localhost/sasha') ? time() : ''; //only for dev purposes
        $timestamp = '?t=' . $timestamp;

        wp_register_script('prefix_bootstrap', plugins_url('assets/lib/bootstrap/dist/js/bootstrap.min.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('leaflet-js', plugins_url('assets/lib/leaflet/dist/leaflet.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('sprintf-js', plugins_url('assets/lib/sprintf.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('chart-js', plugins_url('assets/lib/chart.bundle.min.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('tilemap-js', plugins_url('assets/lib/TileLayer.Common.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('piecelabel-js', plugins_url('assets/lib/chart.piecelabel.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('badili-sphi-dash-js', plugins_url('assets/src/js/badili-sphi-dash.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('d3-js', plugins_url('assets/lib/d3.v3.min.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('highcharts-js', plugins_url('assets/lib/highcharts.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('highmaps-js', plugins_url('assets/lib/map.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('exporting-js', plugins_url('assets/lib/exporting.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('sunburst-js', plugins_url('assets/lib/sunburst.js', __FILE__), array('jquery'), NULL, false);
        wp_register_script('africa-js', plugins_url('assets/lib/africa.js', __FILE__), array('jquery'), NULL, false);

        wp_register_script('markercluster-js', plugins_url('assets/lib/leaflet.markercluster/leaflet.markercluster-src.js', __FILE__), array('jquery'), NULL, false);
    }

    private function load_scripts(){
        add_action('wp_enqueue_scripts', function () {
            if (is_page(SPHI_DASHBOARD)) {
                wp_enqueue_style('bootstrap-css');
                wp_enqueue_style('leaflet-css');
                // wp_enqueue_style('leaflet-css', esc_url_raw('https://npmcdn.com/leaflet@1.0.0-rc.1/dist/leaflet.css'), array(), null);
                wp_enqueue_style('marker-cluster-css');
                wp_enqueue_style('marker-cluster-default-css');

                wp_enqueue_style('badili-inspinia-style-css');
                wp_enqueue_style('badili-sphi-dash-css');

                wp_enqueue_script('jquery');
                wp_enqueue_script('prefix_bootstrap');
                wp_enqueue_script('leaflet-js');
                wp_enqueue_script('sprintf-js');
                wp_enqueue_script('chart-js');
                wp_enqueue_script('d3-js');
                wp_enqueue_script('highcharts-js');
                wp_enqueue_script('highmaps-js');
                wp_enqueue_script('exporting-js');
                wp_enqueue_script('sunburst-js');
                wp_enqueue_script('africa-js');
                wp_enqueue_script('piecelabel-js');
                wp_enqueue_script('markercluster-js');
                wp_enqueue_script('badili-sphi-dash-js');

                // load the default data
                $geo_jsons = $this->get_countries_geojson();
                $metrics = $this->get_sphi_metrics();
                $varieties_meta = $this->get_varieties_released();
                $front_end_data = array(
                    'metrics' => $metrics,
                    'geo_json' => $geo_jsons,
                    'varieties' => $varieties_meta
                );
                wp_localize_script('badili-sphi-dash-js', 'bi_dash_data', $front_end_data);
            }
        }); 
    }

    private function load_default_view($page_template){
        if (is_page(SPHI_DASHBOARD)) {
            $page_template = load_template(__DIR__ . '/views/dash_view.php');
        }
        return $page_template;
    }

    private function get_countries_geojson(){
        global $wpdb;
        $wpdb->show_errors();
        $geo_json = $wpdb->get_results('SELECT id, iso_code, lower(short_code) as short_code, name as country_name, center_lat, center_long, country_type FROM '. $wpdb->prefix .'bi_africa_countries_geojson ORDER by iso_code');

        return $geo_json;
    }

    private function get_sphi_metrics($country=Null){
        global $wpdb;
        $wpdb->show_errors();
        $targets = $wpdb->get_results('SELECT a.iso_code, lower(a.short_code) as short_code, b.*, a.name as country_name, number FROM '. $wpdb->prefix .'bi_africa_countries_geojson as a inner join '. $wpdb->prefix .'bi_beneficiaries as b on a.id =b.country_id WHERE a.country_type is not NULL and b.metric_type = "target" ORDER by iso_code');

        $grouped_metrics = $wpdb->get_results('SELECT a.iso_code, lower(a.short_code) as short_code, b.*, sum(b.number) as total, b.number FROM '. $wpdb->prefix .'bi_africa_countries_geojson as a inner join '. $wpdb->prefix .'bi_beneficiaries as b on a.id =b.country_id WHERE a.country_type is not NULL GROUP BY year, metric_type ORDER by iso_code');

        $all_metrics = $wpdb->get_results('SELECT a.iso_code, lower(a.short_code) as short_code, b.* FROM '. $wpdb->prefix .'bi_africa_countries_geojson as a inner join '. $wpdb->prefix .'bi_beneficiaries as b on a.id =b.country_id WHERE a.country_type is not NULL and b.metric_type != "target" ORDER by iso_code');

        $all_years = $wpdb->get_results('SELECT b.year FROM '. $wpdb->prefix .'bi_africa_countries_geojson as a inner join '. $wpdb->prefix .'bi_beneficiaries as b on a.id =b.country_id WHERE a.country_type is not NULL and b.metric_type != "target" GROUP by b.year');

        $projects = $wpdb->get_results('SELECT b.* FROM '. $wpdb->prefix .'bi_beneficiaries as b WHERE b.metric_type like "project_reach%" order by number desc');

        $org_reach = $wpdb->get_results('SELECT a.org_id, b.org_name, sum(hh_reached) as hh_reached, sum(ofsp_hh_reached) as ofsp_hh_reached, sum(direct_ben) as direct_ben, sum(indirect_ben) as indirect_ben FROM '. $wpdb->prefix .'bi_org_reach as a inner join '. $wpdb->prefix .'bi_organizations as b on a.org_id=b.id GROUP by org_id order by sum(hh_reached) desc');

        $dvm_dist = $wpdb->get_results('SELECT a.* FROM '. $wpdb->prefix .'bi_dvm_distribution as a ORDER by a00');

        $dvm_aggregated = $wpdb->get_results('SELECT a.id, b.short_code as a00, a.category, a.category2, count(*) as dvm_count FROM `wp_bi_dvm_distribution` as a inner join wp_bi_africa_countries_geojson as b on a.country_id=b.id where a.show_on_map = 1 group by b.short_code, a.category, a.category2');

        $dvm_by_age = $wpdb->get_results('SELECT Sex, age_cat, count(*) as dvm_count FROM wp_bi_dvm_distribution where age_cat != "" group by Sex, age_cat order by Sex, age_cat');

        $all_data = array();
        $all_data['sphi_countries'] = array();
        $all_target = 0;
        foreach($targets as $target){
            if(is_null($all_data[$target->short_code])){
                $all_data[$target->short_code] = array();
            }
            $all_data[$target->short_code]['target'] = $target->number;
            $all_target += $target->number;
            $all_data[$target->short_code]['country_name'] = $target->country_name;
            $all_data['sphi_countries'][] = array('name' => $target->short_code, 'value' => 1, 'color' => '#f57f21');
            $all_data['sphi_countries'][] = array($target->short_code, 1);
            $all_data[$target->short_code]['short_code'] = $target->short_code;
        }

        $all_data['org'] = array();
        foreach($org_reach as $org){
            $cur = array(
                'org_name' => $org->org_name,
                'hh_reached' => (int)$org->hh_reached,
                'ofsp_hh_reached' => (int)$org->ofsp_hh_reached,
                'direct_ben' => (int)$org->direct_ben,
                'indirect_ben' => (int)$org->indirect_ben,
                'hh_reached_log' => ($org->hh_reached == 0) ? 0 : sqrt($org->hh_reached),
                'ofsp_hh_reached_log' => ($org->ofsp_hh_reached == 0) ? 0 : sqrt($org->ofsp_hh_reached),
                'direct_ben_log' => ($org->direct_ben == 0) ? 0 : sqrt($org->direct_ben),
                'indirect_ben_log' => ($org->indirect_ben == 0) ? 0 : sqrt($org->indirect_ben),
            );
            $all_data['org'][] = $cur;
        }
        
        $all_data['all'] = array();
        foreach($grouped_metrics as $metric){
            $metric_year = ($metric->metric_type == 'target') ? 'all' : $metric->year;
            if(is_null($all_data['all'][$metric->metric_type])){
                $all_data['all'][$metric->metric_type] = array('all' => 0);
            }
            $all_data['all'][$metric->metric_type][$metric_year] = $metric->total;
            if($metric->metric_type == 'target'){
                $all_data['all'][$metric->metric_type]['all'] += $metric->number;
            }
            else{
                $all_data['all'][$metric->metric_type]['all'] += $metric->total;
            }
        }
        $all_data['all']['target']['all'] = $all_target;

        $all_data['years'] = array();
        foreach($all_years as $year){
            $all_data['years'][] = $year->year;
        }

        $all_data['dvm_dist'] = array();
        foreach($dvm_dist as $dvm){
            $all_data['dvm_dist'][] = $dvm;
        }

        $all_data['dvm_sunburst'] = array();
        $all_data['dvm_sunburst'][] =array(
            'id' => '0.0',
            'parent' => '',
            'name' => 'SPHI Countries'
        );
        $added_dvm_in_sunbursts = array();
        foreach($dvm_aggregated as $dvm){
            // add the country level if not added already
            if(!in_array($dvm->a00, array_keys($added_dvm_in_sunbursts))){
                $cur_id = '1.'.$dvm->id;
                $all_data['dvm_sunburst'][] = array(
                    'id' => $cur_id,
                    'parent' => '0.0',
                    'name' => $dvm->a00
                );
                $added_dvm_in_sunbursts[$dvm->a00] = $cur_id;
            }

            // add the first category if not added
            $dvm->category = (is_null($dvm->category)) ? 'Undefined' : $dvm->category;
            $dvm->category2 = (is_null($dvm->category2)) ? 'Undefined' : $dvm->category2;

            if(!in_array($dvm->a00.$dvm->category, array_keys($added_dvm_in_sunbursts))){
                $cur_id = '2.'.$dvm->id;
                $all_data['dvm_sunburst'][] = array(
                    'id' => $cur_id,
                    'parent' => $added_dvm_in_sunbursts[$dvm->a00],
                    'name' => $dvm->category
                );
                $added_dvm_in_sunbursts[$dvm->a00.$dvm->category] = $cur_id;
            }

            // add the first category2 if not added
            $all_data['dvm_sunburst'][] = array(
                'id' => '3.'.$dvm->id,
                'parent' => $added_dvm_in_sunbursts[$dvm->a00.$dvm->category],
                'name' => $dvm->category2,
                'value' => (int)$dvm->dvm_count
            );
        }

        $all_data['dvm_by_age_gender'] = array(
            'cat' => array(),
            'male' => array(),
            'female' => array()
        );

        foreach($dvm_by_age as $dvm){
            if(!in_array($dvm->age_cat, $all_data['dvm_by_age_gender']['cat'])){
                $all_data['dvm_by_age_gender']['cat'][] = $dvm->age_cat;
            }

            if($dvm->Sex == 'Male'){
                $all_data['dvm_by_age_gender']['male'][] = 0-(int)$dvm->dvm_count;
            }

            if($dvm->Sex == 'Female'){
                $all_data['dvm_by_age_gender']['female'][] = (int)$dvm->dvm_count;
            }
        }
        
        foreach($all_metrics as $metric){
            if(is_null($all_data[$metric->short_code][$metric->metric_type])){
                $all_data[$target->short_code][$metric->metric_type] = array('all' => 0);
            }
            $all_data[$metric->short_code][$metric->metric_type][$metric->year] = $metric->number;
            $all_data[$metric->short_code][$metric->metric_type]['all'] += $metric->number;
        }
        
        $all_projects = array();
        foreach($projects as $hhs){
            if(!array_key_exists($hhs->country, $all_projects)){
                $all_projects[$hhs->country] = array();
            }
            preg_match('/project_reach (hhs|ind)/', $hhs->metric_type, $matches);
            $all_projects[$hhs->country][$matches[1]] = $hhs->number;
        }

        $all_data['projects'] = array('hhs' => array(), 'ind' => array(), 'projects' => array());
        foreach($all_projects as $project => $proj){
            $all_data['projects']['hhs'][] = $proj['hhs'];
            $all_data['projects']['ind'][] = $proj['ind'];
            $all_data['projects']['projects'][] = $project;
        }

        return $all_data;
    }

    private function get_varieties_released(){
        global $wpdb;
        $wpdb->show_errors();
        $varieties = $wpdb->get_results('SELECT a.iso_code, a.short_code, b.*, a.name as country_name FROM '. $wpdb->prefix .'bi_africa_countries_geojson as a inner join '. $wpdb->prefix .'bi_varieties as b on a.id =b.country_id WHERE a.country_type is not NULL ORDER by iso_code');

        $all_metrics = $wpdb->get_results('SELECT * FROM '. $wpdb->prefix .'bi_varieties_metrics ORDER BY metric_name');

        return array('varieties' => $varieties, 'metrics' => $all_metrics);
    }
}

$dash = new BadiliSPHIDash();
