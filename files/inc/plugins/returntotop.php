<?php
/**
 * Return to top postbit button 1.8.1

 * Copyright 2017 Matthew Rogowski

 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at

 ** http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
**/

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("postbit", "returntotop");

global $templatelist;

if($templatelist)
{
	$templatelist .= ',';
}
$templatelist .= 'returntotop';

function returntotop_info()
{
	return array(
		"name" => "Return to top postbit button",
		"description" => "Adds a 'Return to Top' button to the postbit.",
		"website" => "https://github.com/MattRogowski/Return-to-top-postbit-button",
		"author" => "Matt Rogowski",
		"authorsite" => "https://matt.rogow.ski",
		"version" => "1.8.1",
		"compatibility" => "16*,18*",
		"codename" => "returntotop"
	);
}

function returntotop_activate()
{
	global $mybb, $db;

	require_once MYBB_ROOT . "inc/adminfunctions_templates.php";

	returntotop_deactivate();

	$templates = array();
	if(substr($mybb->version, 0, 3) == '1.6')
	{
		$templates[] = array(
			"title" => "returntotop",
			"template" => "<a href=\"#top\"><img src=\"{\$mybb->settings['bburl']}/{\$theme['imgdir']}/{\$lang->language}/postbit_top.gif\" border=\"0\" alt=\"{\$lang->returntotop}\" /></a>"
		);
	}
	elseif(substr($mybb->version, 0, 3) == '1.8')
	{
		$templates[] = array(
			"title" => "returntotop",
			"template" => "<a href=\"#top\" title=\"{\$lang->returntotop}\" class=\"postbit_top\"><span style=\"background-image: url('{\$theme['imgdir']}/top.png');\">{\$lang->returntotop}</span></a>"
		);
	}
	foreach($templates as $template)
	{
		$insert = array(
			"title" => $db->escape_string($template['title']),
			"template" => $db->escape_string($template['template']),
			"sid" => "-1",
			"version" => "1800",
			"dateline" => TIME_NOW
		);
		$db->insert_query("templates", $insert);
	}

	find_replace_templatesets("postbit", '#'.preg_quote('{$post[\'button_report\']}').'#', '{$post[\'button_report\']}{$post[\'returntotop\']}');
	find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'button_report\']}').'#', '{$post[\'button_report\']}{$post[\'returntotop\']}');
}

function returntotop_deactivate()
{
	global $db;

	require_once MYBB_ROOT . "inc/adminfunctions_templates.php";

	$templates = array(
		"returntotop"
	);
	$templates = "'" . implode("','", $templates) . "'";
	$db->delete_query("templates", "title IN ({$templates})");

	find_replace_templatesets("postbit", '#'.preg_quote('{$post[\'returntotop\']}').'#', '', 0);
	find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'returntotop\']}').'#', '', 0);
}

function returntotop(&$post)
{
	global $mybb, $lang, $theme, $templates;

	$lang->load('returntotop');

	eval("\$post['returntotop'] = \"".$templates->get('returntotop')."\";");
}
?>
