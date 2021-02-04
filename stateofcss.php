#!/usr/bin/php
<?php
include_once ('partials/header.php');

// Enregistrement de ces données en BDD
try{
	$bdd = new PDO('mysql:host=localhost;dbname=stateofcss','root','admin') or die(print_r($bdd->errorInfo()));
	$bdd->exec('SET NAMES utf8');
} catch(Exeption $e){
	die('Erreur:'.$e->getMessage());
}

// Lire le fichier JSON
$string = file_get_contents("data/state/state_of_css_2019-2020.json");
$data = json_decode($string, true);
$i = 0;

foreach ($data as $d) {
	// table 'basic'
	$d['_id'] = ($d['year'] === 2020) ? $d['_id'] : $d['_id']['$oid'];
	$d['surveySlug'] = (isset($d['surveySlug']) && !empty($d['surveySlug'])) ? $d['surveySlug'] : "";
	$d['createdAt'] = ($d['year'] === 2020) ? $d['createdAt']['$date'] : $d['createdAt'];
	$d['updatedAt'] = ($d['year'] === 2020) ? $d['updatedAt']['$date'] : $d['updatedAt'];
	$d['year']	= (isset($d['year']) && !empty($d['year'])) ? $d['year'] : "";
	$d['completion'] = (isset($d['completion']) && !empty($d['completion'])) ? $d['completion'] : "";
	$d['userId']	= (isset($d['userId']) && !empty($d['userId'])) ? $d['userId'] : "";
	$d['survey']	= (isset($d['survey']) && !empty($d['survey'])) ? $d['survey'] : "";

	$sql = "INSERT INTO `basic` (`id`, `surveySlug`, `createdAt`, `updatedAt`, `year`, `completion`, `userId`, `survey`) VALUES (?,?,?,?,?,?,?,?)";
	$req = $bdd->prepare($sql)->execute([$d['_id'], $d['surveySlug'], $d['createdAt'], $d['updatedAt'], $d['year'], $d['completion'], $d['userId'], $d['survey']]);

	// table 'user_info'
	echo "Enregistrement n°".$i++." effectué !!!\n";

    $user_info['knowledge_score'] = (isset($d['user_info']['knowledge_score']) && !empty($d['user_info']['knowledge_score'])) ? $d['user_info']['knowledge_score'] : "";
    $user_info['device'] = (isset($d['user_info']['device']) && !empty($d['user_info']['device'])) ? $d['user_info']['device'] : "";
    $user_info['browser'] = (isset($d['user_info']['browser']) && !empty($d['user_info']['browser'])) ? $d['user_info']['browser'] : "";
    $user_info['version'] = (isset($d['user_info']['version']) && !empty($d['user_info']['version'])) ? $d['user_info']['version'] : "";
    $user_info['os'] = (isset($d['user_info']['os']) && !empty($d['user_info']['os'])) ? $d['user_info']['os'] : "";
    $user_info['referrer'] = (isset($d['user_info']['referrer']) && !empty($d['user_info']['referrer'])) ? $d['user_info']['referrer'] : "";
    $user_info['hash'] = (isset($d['user_info']['hash']) && !empty($d['user_info']['hash'])) ? $d['user_info']['hash'] : "";
    $user_info['locale'] =  (isset($d['user_info']['locale']) && !empty($d['user_info']['locale'])) ? $d['user_info']['locale'] : "";
    $user_info['years_of_experience'] = (isset($d['user_info']['years_of_experience']) && !empty($d['user_info']['years_of_experience'])) ? $d['user_info']['years_of_experience'] : "";
    $user_info['company_size'] = (isset($d['user_info']['company_size']) && !empty($d['user_info']['company_size'])) ? $d['user_info']['company_size'] : "";
    $user_info['yearly_salary'] = (isset($d['user_info']['yearly_salary']) && !empty($d['user_info']['yearly_salary'])) ? $d['user_info']['yearly_salary'] : "";
    $user_info['job_title'] = (isset($d['user_info']['job_title']) && !empty($d['user_info']['job_title'])) ? $d['user_info']['job_title'] : "";
    $user_info['css_proficiency'] = (isset($d['user_info']['css_proficiency']) && !empty($d['user_info']['css_proficiency'])) ? $d['user_info']['css_proficiency'] : "";
    $user_info['javascript_proficiency'] = (isset($d['user_info']['javascript_proficiency']) && !empty($d['user_info']['javascript_proficiency'])) ? $d['user_info']['javascript_proficiency'] : "";
    $user_info['backend_proficiency'] = (isset($d['user_info']['backend_proficiency']) && !empty($d['user_info']['backend_proficiency'])) ? $d['user_info']['backend_proficiency'] : "";
    $user_info['how_did_user_find_out_about_the_survey'] = (isset($d['user_info']['how_did_user_find_out_about_the_survey']) && !empty($d['user_info']['how_did_user_find_out_about_the_survey'])) ? $d['user_info']['how_did_user_find_out_about_the_survey'] : "";
    $user_info['gender'] = (isset($d['user_info']['gender']) && !empty($d['user_info']['gender'])) ? $d['user_info']['gender'] : "";
    $user_info['race_ethnicity'] = (isset($d['user_info']['race_ethnicity']['choices'][0]) && !empty($d['user_info']['race_ethnicity']['choices'][0])) ? implode(",", $d['user_info']['race_ethnicity']['choices']) : "";
    $user_info['country_name'] = (isset($d['user_info']['country_name']) && !empty($d['user_info']['country_name'])) ? $d['user_info']['country_name'] : "";

    $sql = "INSERT INTO `user_info` (`id`, `knowledge_score`, `device`, `browser`, `version`, `os`, `referrer`, `hash`, `locale`, `years_of_experience`, `company_size`, `yearly_salary`, `job_title`, `css_proficiency`, `javascript_proficiency`, `backend_proficiency`, `how_did_user_find_out_about_the_survey`, `gender`, `race_ethnicity`, `country_name`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $req = $bdd->prepare($sql)->execute([
        $d['_id'], $user_info['knowledge_score'], $user_info['device'], $user_info['browser'],
        $user_info['version'], $user_info['os'], $user_info['referrer'], $user_info['hash'],
        $user_info['locale'], $user_info['years_of_experience'], $user_info['company_size'], $user_info['yearly_salary'],
        $user_info['job_title'], $user_info['css_proficiency'], $user_info['javascript_proficiency'], $user_info['backend_proficiency'],
        $user_info['how_did_user_find_out_about_the_survey'], $user_info['gender'], $user_info['race_ethnicity'], $user_info['country_name']
    ]);
}

include_once ('partials/footer.php');