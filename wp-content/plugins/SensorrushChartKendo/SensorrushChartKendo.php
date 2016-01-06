<?php
/**
 * @package SensorrushChartKendo
 * @version 1.0
 */
/*
Plugin Name: SensorrushChartKendo
Plugin URI: https://github.com/evgenbel/SensorrushChartKendo
Description: Plugin for view Kendo Chart with data from http://sensorrush.net/{username}/{apikey}/{sensorname}/{channels}/Read/{limit}/{order}. Example: [sensorrush_kendo_chart username=sensorrush sensorname=MyPiSenseHAT apikey=1234 channels=Humidity limit=10 order=desc] - this parameters is default
Author: Evgeniy Belov
Version: 1.0
Author URI:
*/

class sensorrushChartKendo{
    public function __construct() {
        session_start();
        if (isset($_GET['ajaxurl']))
            add_action('template_redirect', array($this, 'hijackRequests'), -100);
        else
            add_shortcode('sensorrush_kendo_chart', array($this, 'show_chart'));
    }

    public function hijackRequests() {
        $this->writeJsonResponse();
    }

    protected function writeJsonResponse($status = 200) {
        $url = $this->getUrl();
        $data = file_get_contents(/*'http://sensorrush.net/sensorrush/1234/MyPiSenseHAT/Humidity/Read/10/desc'*/$url);
        //
        header('content-type: application/json; charset=utf-8', true, $status);
        echo($data);
        exit;
    }

    protected function getUrl(){
        return 'http://sensorrush.net/' .
        $_SESSION['username'] . '/' .
        $_SESSION['apikey'] . '/' .
        $_SESSION['sensorname'] . '/' .
        $_SESSION['channels'] . '/Read/' .
        $_SESSION['limit'] . '/' .
        $_SESSION['order'];
    }

    public function show_chart($attr){
        $_SESSION['username'] = (isset($attr['username'])?$attr['username']:'sensorrush') ;
        $_SESSION['apikey'] = (isset($attr['apikey'])?$attr['apikey']:'1234') ;
        $_SESSION['sensorname'] = (isset($attr['sensorname'])?$attr['sensorname']:'MyPiSenseHAT') ;
        $_SESSION['channels'] = (isset($attr['channels'])?$attr['channels']:'Humidity') ;
        $_SESSION['limit'] = (isset($attr['limit'])?$attr['limit']:'10') ;
        $_SESSION['order'] = (isset($attr['order'])?$attr['order']:'desc') ;

        $url = $this->getUrl();
        $this->addStyles();
        $chart = '<div id="chart" url="' . $url . '"></div> <button onclick="refreshKendoChart()">Refresh</button>';
        return  $chart;
    }

    protected function addStyles(){
        wp_register_style( 'kendo-common',  'http://kendo.cdn.telerik.com/2014.2.716/styles/kendo.common.min.css' );
        wp_register_style( 'kendo-rtl',  'http://kendo.cdn.telerik.com/2014.2.716/styles/kendo.rtl.min.css' );
        //wp_register_style( 'default-common',  'http://kendo.cdn.telerik.com/2014.2.716/styles/default.common.min.css' );
        wp_register_style( 'kendo-dataviz',  'http://kendo.cdn.telerik.com/2014.2.716/styles/kendo.dataviz.min.css' );
        wp_register_style( 'kendo-dataviz-default',  'http://kendo.cdn.telerik.com/2014.2.716/styles/kendo.dataviz.default.min.css' );
        wp_enqueue_style( 'kendo-common' );
        wp_enqueue_style( 'kendo-rtl' );
        wp_enqueue_style( 'default-common' );
        wp_enqueue_style( 'kendo-dataviz' );
        wp_enqueue_style( 'kendo-dataviz-default' );

        wp_enqueue_script( 'jquery-min', 'http://kendo.cdn.telerik.com/2014.2.716/js/jquery.min.js', array(), '20141010', true );
        wp_enqueue_script( 'kendo-all-min', 'http://kendo.cdn.telerik.com/2014.2.716/js/kendo.all.min.js', array(), '20141010', true );
        wp_enqueue_script( 'chart', plugins_url('SensorrushChartKendo/chart.js'), array(), '20141010', true );
    }
}

$plugin = new sensorrushChartKendo();