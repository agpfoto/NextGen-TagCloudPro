<?php

// phpcs:disable Generic.Commenting.DocComment.MissingShort
/**
 * Provides display type admin forms
 *
 * @package NextGEN Gallery Pro
 */
/**
 * Class M_Pro_DisplayType_Admin
 */
class M_Pro_DisplayType_Admin extends C_Base_Module
{
    /**
     * The object.
     *
     * @var C_Component $object The object instance
     */
    public $object;
    /**
     * Define the module.
     *
     * @param string $id The module ID.
     * @param string $name The module name.
     * @param string $description The module description.
     * @param string $version The module version.
     * @param string $uri The module URI.
     * @param string $author The module author.
     * @param string $author_uri The module author URI.
     * @param bool   $context The module context.
     *
     * @return void
     */
    public function define($id = 'pope-module', $name = 'Pope Module', $description = '', $version = '', $uri = '', $author = '', $author_uri = '', $context = false)
    {
        parent::define('imagely-pro_displaytype_admin', 'NextGEN Pro Display Type Manager', 'Provides display type admin forms', '3.15.1', 'https://www.imagely.com/wordpress-gallery-plugin/nextgen-gallery-pro/', 'Imagely', 'https://www.imagely.com');
    }
    /**
     * Determine if the current request is an admin request.
     *
     * @return bool
     */
    public function is_admin()
    {
        return \Imagely\NGG\IGW\ATPManager::is_atp_url() || is_admin();
    }
    /**
     * Initialize the module.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        if ($this->is_admin()) {
            $forms = \Imagely\NGG\Admin\FormManager::get_instance();
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_BLOG_GALLERY);
            }
            $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_FILM);
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_GRID_ALBUM);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_LIST_ALBUM);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_HORIZONTAL_FILMSTRIP);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_IMAGEBROWSER);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_MASONRY);
            }
            $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_MOSAIC);
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_SEARCH);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_TAGCLOUD);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_SIDESCROLL);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_SLIDESHOW);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_THUMBNAIL_GRID);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $forms->add_form(NGG_DISPLAY_SETTINGS_SLUG, NGG_PRO_TILE);
            }
        }
    }
    /**
     * Register adapters.
     *
     * @return void
     */
    public function _register_adapters()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        if (\Imagely\NGG\IGW\ATPManager::is_atp_url() || is_admin()) {
            $registry = C_Component_Registry::get_instance();
            $registry->add_adapter('I_Form', 'A_Mosaic_Form', NGG_PRO_MOSAIC);
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_Blog_Form', NGG_PRO_BLOG_GALLERY);
            }
            $registry->add_adapter('I_Form', 'A_NextGen_Pro_Film_Form', NGG_PRO_FILM);
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_Grid_Album_Form', NGG_PRO_GRID_ALBUM);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_Horizontal_Filmstrip_Form', NGG_PRO_HORIZONTAL_FILMSTRIP);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_ImageBrowser_Form', NGG_PRO_IMAGEBROWSER);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_List_Album_Form', NGG_PRO_LIST_ALBUM);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_Masonry_Form', NGG_PRO_MASONRY);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_Sidescroll_Form', NGG_PRO_SIDESCROLL);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_Slideshow_Form', NGG_PRO_SLIDESHOW);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_Thumbnail_Grid_Form', NGG_PRO_THUMBNAIL_GRID);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_NextGen_Pro_Tile_Form', NGG_PRO_TILE);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_Search_Form', NGG_PRO_SEARCH);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                $registry->add_adapter('I_Form', 'A_ProTagCloud_Form', NGG_PRO_TAGCLOUD);
            }
            /** @remove-for-nextgen-starter */
            if ('starter' !== \Imagely\NGGPro\Bootloader::$plugin_id) {
                foreach (\Imagely\NGGPro\Display\HoverCaptions::get_supported_display_types() as $display_type) {
                    $registry->add_adapter('I_Form', 'A_NextGen_Pro_Captions_Form', $display_type);
                }
            }
            /** @remove-for-nextgen-plus */
            /** @remove-for-nextgen-starter */
            if (!in_array(\Imagely\NGGPro\Bootloader::$plugin_id, ['plus', 'starter'], true)) {
                foreach (\Imagely\NGGPro\Proofing\Manager::$display_types as $display_type) {
                    $registry->add_adapter('I_Form', 'A_NextGen_Pro_Proofing_Form', $display_type);
                }
            }
            /**
             * Remove this feature when building Plus and Starter.
             *
             * @remove-for-nextgen-plus
             * @remove-for-nextgen-starter
             */
            if (!in_array(\Imagely\NGGPro\Bootloader::$plugin_id, ['plus', 'starter'], true)) {
                foreach (\Imagely\NGGPro\Display\Animations\Manager::get_supported_image_types() as $display_type) {
                    $registry->add_adapter('I_Form', 'A_NextGen_Pro_Animations_Form', $display_type);
                }
            }
        }
    }
    /**
     * Get the type list.
     *
     * @return array
     */
    public function get_type_list()
    {
        return ['A_Mosaic_Form' => 'adapter.mosaic.php', 'A_NextGen_Pro_Animations_Form' => 'adapter.animations.php', 'A_NextGen_Pro_Captions_Form' => 'adapter.hover_captions.php', 'A_NextGen_Pro_Film_Form' => 'adapter.film.php', 'A_NextGen_Pro_Proofing_Form' => 'adapter.proofing.php', 'A_NextGen_Pro_Sidescroll_Form' => 'adapter.sidescroll.php', 'A_Nextgen_Pro_Album_Form' => 'adapter.albums.php', 'A_Nextgen_Pro_Blog_Form' => 'adapter.blog_gallery.php', 'A_Nextgen_Pro_Grid_Album_Form' => 'adapter.grid_album.php', 'A_Nextgen_Pro_Horizontal_Filmstrip_Form' => 'adapter.horizontal_filmstrip.php', 'A_Nextgen_Pro_Imagebrowser_Form' => 'adapter.imagebrowser.php', 'A_Nextgen_Pro_List_Album_Form' => 'adapter.list_album.php', 'A_Nextgen_Pro_Masonry_Form' => 'adapter.masonry.php', 'A_Nextgen_Pro_Slideshow_Form' => 'adapter.slideshow.php', 'A_Nextgen_Pro_Thumbnail_Grid_Form' => 'adapter.thumbnail_grid.php', 'A_Nextgen_Pro_Tile_Form' => 'adapter.tile.php', 'A_Search_Form' => 'adapter.search.php'];
    }
}
new M_Pro_DisplayType_Admin();