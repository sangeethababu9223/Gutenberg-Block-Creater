<?php
/**
 * Plugin Name:       Gutenberg Block Creator
 * Description:       Dynamic Gutenberg block creation
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Sangeetha
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gutenberg-block-creator
 *
 * @package           create-block
 */

class GutenbergBlockCreator {
	public function __construct(){
		if(!defined('WPINC')){
			die;
		}
		add_action('admin_menu', array($this,'gbblocksPlugin_settings_page'));
		add_filter( 'block_categories_all' , array($this,'add_custom_gblock_categories'));
		add_action( 'init', array($this,'create_block_gutenberg_block_creator_block_init'));
		$filter_name = 'plugin_action_links_'.plugin_basename(__FILE__);
		add_filter($filter_name, array($this, 'gbBlocksPlugin_add_settings_link'));
	}

	public function gbblocksPlugin_settings_page(){
		add_menu_page(
			'Gutenberg Blocks',
			'Gutenberg Blocks',
			'manage_options',
			'gbblocks-plugin',
			array($this,'gbblocks_settings_page_markup'),
			'dashicons-wordpress-alt',
			50
		);
		add_submenu_page(
			'gbblocks-plugin',
			'Add New',
			'Add New',
			'manage_options',
			'gbblocks-plugin',
			array($this,'gbblocks_settings_page_markup'),
		);
		add_submenu_page(
			'gbblocks-plugin',
			'List Blocks',
			'List Blocks',
			'manage_options',
			'gbblocks-list-blocks',
			array($this,'gbblocks_settings_list_page_markup'),
		);
	}

	public function gbblocks_settings_page_markup(){
		if(!current_user_can('manage_options')){
			return;
		}
		require_once plugin_dir_path( __FILE__ ) . 'includes/layouts/register-admin-page.php';
	}
	public function gbblocks_settings_list_page_markup(){
		if(!current_user_can('manage_options')){
			return;
		}
		require_once plugin_dir_path( __FILE__ ) . 'includes/layouts/register-admin-list-page.php';
	}

	public function add_custom_gblock_categories( $categories ) {
		$categories[] = array(
			'slug'  => 'custom-modules',
			'title' => 'Custom Modules'
		);
		return $categories;
	}

	public function create_block_gutenberg_block_creator_block_init() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/block-register.php';
	}
	
	public function create_folder(){
		if (!file_exists(plugin_dir_path( __FILE__ ) . 'src/block-three')) {
			$src = plugin_dir_path( __FILE__ ) . '/src-templates/basics/';
			$dst = plugin_dir_path( __FILE__ ) . '/src/block-three/';
			mkdir(plugin_dir_path( __FILE__ ) . 'src/block-three', 0777, true);
			require plugin_dir_path( __FILE__ ) . 'includes/files-copy.php';
			FileCopy::custom_copy($src,$dst);
		}
	}
	
	public function gbBlocksPlugin_add_settings_link($links){
		$settings_link ='<a href="admin.php?page=gbblocks-plugin">Settings</a>';
		array_push($links,$settings_link);
		return $links;
	}
}
$gblocks = new GutenbergBlockCreator();
$gblocks->create_folder();