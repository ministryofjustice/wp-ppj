<?php
// bootstrap WP
require_once(ABSPATH . "wp-load.php");

function ppj_import_jobs($force_pull = false)
{
    // get admin email for messaging
    $to = get_option('admin_email');

    if ($force_pull) {
        update_option('jobs_request_cron_is_running', false);
    }

    // check if this script is already running, bail if it is. Default to 'not running'.
    if (get_option('jobs_request_cron_is_running', false) == true) {
        jj_simple_mail($to, [
            '[PPJ] Job script already running',
            'WARNING -> the jobs script is already running. A request to refresh the job list has failed.'
        ]);
        return false;
    }

    // we are ready to start, lock the script...
    update_option('jobs_request_cron_is_running', true);

    // get the uploads directory path
    $upload_dir = wp_get_upload_dir();

    $url = "https://justicejobs.tal.net/vx/mobile-0/appcentre-1/brand-2/candidate/jobboard/vacancy/3/feed";
    $file = $upload_dir['basedir'] . "/job-feed/jobs.xml";

    $response = wp_remote_get($url, [
        'timeout' => 1800,
        'stream' => true,
        'filename' => $file
    ]);

    if (is_wp_error($response)) {
        ppj_simple_mail($to, [
            '[PPJ] Getting remote data',
            'WARNING -> We did not receive jobs data from the remote server. This could be a temporary error'
        ]);
        return false;
    }

    $xml = simplexml_load_file($file);

    // let's check the data is xml
    if (!$xml) {
        // inform admin
        ppj_simple_mail($to, [
            "[PPJ] Security: error detected in jobs XML feed",
            "Please check the following URL for errors. The job feed load process failed in " . __FUNCTION__ . "\n\n" . $url
        ]);

        return false;
    }

    $total_jobs = count($xml->entry);
    $prison_officer_jobs = array();
    $prisons = array();

    $youth_custody_jobs = array();
    $youth_custody_locations = array();

    $locations_json = file_get_contents(get_template_directory() . "/jobs-handler/locations.json");
    $locations_array = json_decode($locations_json, true);

    foreach($locations_array as $location){

        if($location["type"] == "Prison") {
            $prisons[$location["name"]] = array(
                "town" => $location["town"],
                "lat" => $location["lat"],
                "lng" => $location["lng"]

            );
        }
        else if ($location["type"] == "Youth Custody") {
            $youth_custody_locations[$location["name"]] = array(
                "town" => $location["town"],
                "lat" => $location["lat"],
                "lng" => $location["lng"]

            );
        }
    }

    for ($x = 0; $x < $total_jobs; $x++) {
        $job_title = (string) $xml->entry[$x]->title;
        $job_url = (string) $xml->entry[$x]->id;
        $job_part_time = false;
        $is_prison_job = true;

        if(str_contains($job_title, 'Prison Officer') || str_contains($job_title, 'Youth Justice Worker')){

            if(str_contains($job_title, 'Youth Justice Worker')){
                $is_prison_job = false;
            }

            //Some job titles can have multiple locations
            $confirmed_locations = array();

            //Location could be one or many locations using ',' or 'and'
            $location_names_pos = strpos($job_title, '-');
            $location_names = substr($job_title, $location_names_pos+1);
            $location_names = str_replace(",", "/", str_replace(" and ", "/", $location_names)); //replace ',''and'

            if(str_contains($location_names, "Part Time")){
                $job_part_time = true;
                $location_names = str_replace("Part Time", "", $location_names);
            }
            $location_names_array = explode("/", $location_names);

            if(is_array($location_names_array) && count($location_names_array) > 0){
                foreach ($location_names_array as $location_name){
                    $location_name = trim($location_name);
                    if($is_prison_job && key_exists($location_name, $prisons)){
                        $confirmed_locations[] = array("name" => $location_name, "location" => $prisons[$location_name]);
                    }
                    elseif(!$is_prison_job && key_exists($location_name, $youth_custody_locations)){
                        $confirmed_locations[] = array("name" => $location_name, "location" => $youth_custody_locations[$location_name]);
                    }
            }

            if(count($confirmed_locations) > 0) {
                $job_content = $xml->entry[$x]->content;

                if (!empty($job_content)) {
                    $job_content = (string)$job_content->div;

                    $job_details = preg_split("/\r\n|\n|\r/", $job_content);

                    foreach ($job_details as $job_detail) {
                        if (str_contains($job_detail, 'Salary:')) {
                            $salary = preg_replace("/[^0-9-]/", "", $job_detail);
                        }
                        if (str_contains($job_detail, 'Closing Date:')) {
                            $closing_date = str_replace('Closing Date:', "", $job_detail);
                            $closing_date = date("j/m/Y", strtotime($closing_date));
                        }
                    }

                    if (!empty($salary) && !empty($closing_date)) {

                        foreach ($confirmed_locations as $location) {

                            $new_job = array(
                                "title" => $job_title,
                                "salary" => $salary,
                                "url" => $job_url,
                                "part_time" => $job_part_time,
                                "closing_date" => $closing_date, //not currently used by PPJ
                                "prison_name" => $location['name'],
                                "prison_location" => $location['location']

                            );

                            if($is_prison_job){
                                $new_job["role"] = "prison-officer";
                                $new_job["type"] = "Prison";
                                $prison_officer_jobs[] = $new_job;
                            }
                            else {
                                $new_job["role"] = "youth-custody";
                                $new_job["type"] = "Youth Custody";
                                $youth_custody_jobs[] = $new_job;
                            }
                        }


                        }
                    }
                }
            }

        }
    }

    //Write JSON file even if no jobs to remove expired jobs

    //Prison Officer Jobs - write json file
    $fp = fopen($upload_dir['basedir'] . "/job-feed/prison-officer-jobs.json", 'w');
    fwrite($fp, json_encode($prison_officer_jobs));
    fclose($fp);


    //Youth Custody Jobs - write json file
    $fp = fopen($upload_dir['basedir'] . "/job-feed/youth-custody-jobs.json", 'w');
    fwrite($fp, json_encode($youth_custody_jobs));
    fclose($fp);

    update_option('jobs_request_cron_is_running', false);

    return true;
}


/**
 * There are only three variables needed to operate this function
 * Email, Subject and Message.
 * The email address is the first argument
 * The email subject is passed by the first pocket in the args array, message is the second.
 * @param $email string
 * @param $args array
 * @return true on success | false on failure | null if args are not set
 * @uses wp_mail()
 */
function ppj_simple_mail($email, $args)
{
    if (!isset($args[0]) || !isset($args[1])) {
        return null;
    }
    return wp_mail($email, $args[0], $args[1]);
}

// Create cron hook and schedule event
add_action('import_jobs_cron_hook', 'ppj_import_jobs');
if (!wp_next_scheduled('import_jobs_cron_hook')) {
    wp_schedule_event(time(), 'hourly', 'import_jobs_cron_hook');
}
