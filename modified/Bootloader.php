<?php

/**
 * Bootloader for NextGEN Gallery Pro.
 *
 * @package NextGEN Gallery Pro
 */
namespace Imagely\NGGPro;

use Imagely\NGGPro\REST\LicenseActions;
/**
 * Bootloader for NextGEN Gallery Pro.
 */
class Bootloader
{
    /**
     * The minimum version of NextGEN Gallery required for this plugin.
     *
     * @var string
     */
    public static $minimum_ngg_version = '3.56';
    /**
     * The root directory of the plugin.
     *
     * @var string
     */
    public static $plugin_directory_root;
    /**
     * The filename of the plugin.
     *
     * @var string
     */
    public static $plugin_filename;
    /**
     * Plugin ID.
     *
     * @var string
     */
    public static $plugin_id;
    /**
     * Plugin version
     *
     * @var string
     */
    public static $plugin_version;
    /**
     * Script version
     *
     * @var string
     */
    public static $script_version;
    /**
     * Constructor.
     *
     * @param string $plugin_version Plugin version.
     * @param string $plugin_filename Plugin filename.
     * @param string $plugin_id Plugin ID.
     *
     * @return void
     */
    public function __construct($plugin_version, $plugin_filename, $plugin_id)
    {
        self::$plugin_version = $plugin_version;
        self::$plugin_filename = $plugin_filename;
        self::$plugin_id = $plugin_id;
        self::$plugin_directory_root = dirname(self::$plugin_filename) . DIRECTORY_SEPARATOR;
        self::$script_version = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? (string) time() : self::$plugin_version;
        // Another copy of this plugin or one of its siblings has already been loaded -- do not continue.
        // TODO: This is prevent end to end tests to load properly, this gets triggered by only installing pro, this needs to be checked
        // if ( defined( 'NGG_PRO_PLUGIN_VERSION' ) || defined( 'NGG_PLUS_PLUGIN_VERSION' ) || defined( 'NGG_STARTER_PLUGIN_VERSION' ) ) {
        // return;
        // }
        \add_filter('ngg_do_install_or_setup_process', function ($do_upgrade) {
            $settings = \Imagely\NGG\Settings\Settings::get_instance();
            $current_version_setting = $settings->get('ngg_' . self::$plugin_id . '_plugin_version', 0);
            if (!$current_version_setting || $current_version_setting !== self::$plugin_version) {
                $do_upgrade = true;
            }
            return $do_upgrade;
        });
        \add_action('ngg_did_install_or_setup_process', function () {
            // Record the current plugin version; changes to this and setting are how updates are triggered.
            $settings = \Imagely\NGG\Settings\Settings::get_instance();
            $settings->set('ngg_' . self::$plugin_id . '_plugin_version', self::$plugin_version);
            $settings->save();
            $over_time = get_option('nextgen_over_time', []);
            if (empty($over_time['installed_pro'])) {
                $over_time['installed_version'] = self::$plugin_version;
                $over_time['installed_date'] = wp_date('U');
                $over_time['installed_pro'] = $over_time['installed_date'];
                if (!isset($over_time['installed_lite'])) {
                    $over_time['installed_lite'] = false;
                }
                update_option('nextgen_over_time', $over_time);
            }
        });
        // Deactivates this plugin and displays a notification if NextGEN is missing or incompatible.
        \add_action('admin_notices', [$this, 'admin_notices']);
        // The base plugin constants must be defined now so that if NextGEN is activated before this plugin is, then.
        // these plugins' constants will be visible to NextGEN.
        $this->define_base_constants();
        // This plugin depends on NextGEN Gallery. If NextGEN is not yet active, this plugin delays initialization until.
        // NextGEN Gallery has initialized itself.
        if (defined('NGG_PLUGIN_VERSION')) {
            $this->load_plugin();
        } else {
            add_action('ngg_initialized', [$this, 'load_plugin']);
        }
        // Filter links on the plugin list page.
        add_filter('plugin_row_meta', [$this, 'ngg_filter_plugin_links'], 10, 2);
    }
    /**
     * Filter links on the plugin list page.
     *
     * @param array  $links links.
     * @param string $file file.
     *
     * @return array
     */
    public function ngg_filter_plugin_links($links, $file)
    {
        // Check if constants are defined before comparing their values.
        $is_ngg_pro_plugin = defined('NGG_PRO_PLUGIN_BASENAME') && NGG_PRO_PLUGIN_BASENAME === $file;
        $is_ngg_plus_plugin = defined('NGG_PLUS_PLUGIN_BASENAME') && NGG_PLUS_PLUGIN_BASENAME === $file;
        $is_ngg_starter_plugin = defined('NGG_STARTER_PLUGIN_BASENAME') && NGG_STARTER_PLUGIN_BASENAME === $file;
        if ($is_ngg_pro_plugin || $is_ngg_plus_plugin || $is_ngg_starter_plugin) {
            foreach ($links as $key => $link) {
                if (false !== strpos($link, 'Imagely') || false !== strpos($link, 'Visit plugin site')) {
                    $links[$key] = str_replace('<a ', '<a target="_blank" ', $link);
                }
            }
        }
        return $links;
    }
    /**
     * Get the plugin name.
     *
     * @throws \Exception If the plugin ID is not recognized.
     *
     * @return string
     */
    public static function get_plugin_name()
    {
        if ('pro' === self::$plugin_id) {
            return __('NextGEN Gallery Pro', 'nextgen-gallery-pro');
        } elseif ('plus' === self::$plugin_id) {
            return __('NextGEN Gallery Plus', 'nextgen-gallery-pro');
        } elseif ('starter' === self::$plugin_id) {
            return __('NextGEN Gallery Starter', 'nextgen-gallery-pro');
        }
    }
    /**
     * Load the plugin.
     *
     * @return void
     */
    public function load_plugin()
    {
        // Version mismatch: do not load any further.
        if (!defined('NGG_PLUGIN_VERSION') || -1 === version_compare(NGG_PLUGIN_VERSION, self::$minimum_ngg_version)) {
            return;
        }
        // Register our and Composer's autoloader.
        spl_autoload_register([$this, 'autoloader']);
        require_once self::$plugin_directory_root . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        // This file contains empty wrappers to the new namespaced classes and methods to maintain compat for custom templates.
        include_once 'CompatibilityStims.php';
        // Begin actual initialization.
        $this->register_hooks();
        if (is_admin()) {
            $this->load_pope_product();
        }
    }
    /**
     * Define the base constants for the current product.
     *
     * @return void
     */
    public function define_base_constants()
    {
        /**
         * Plus and Starter products are not yet available.
         *
         * @remove-for-nextgen-plus
         * @remove-for-nextgen-starter
         */
        if ('pro' === self::$plugin_id) {
            $this->define_pro_constants();
        }
        $this->define_constants();
    }
    /**
     * Define the constants for the current product.
     *
     * @return void
     */
    public function define_constants()
    {
        define('NGG_PRO_PLUGIN_DIR', plugin_dir_path(self::$plugin_filename));
        // Used by NextGEN itself to determine what features to load, offer up, disable, or patch.
        define('NGG_PRO_API_VERSION', 4.0);
        // Display type ID.
        define('NGG_PRO_ALBUMS', 'photocrati-nextgen_pro_albums');
        define('NGG_PRO_BLOG_GALLERY', 'photocrati-nextgen_pro_blog_gallery');
        define('NGG_PRO_FILM', 'photocrati-nextgen_pro_film');
        define('NGG_PRO_GALLERIA', 'photocrati-galleria');
        define('NGG_PRO_GRID_ALBUM', 'photocrati-nextgen_pro_grid_album');
        define('NGG_PRO_HORIZONTAL_FILMSTRIP', 'photocrati-nextgen_pro_horizontal_filmstrip');
        define('NGG_PRO_IMAGEBROWSER', 'photocrati-nextgen_pro_imagebrowser');
        define('NGG_PRO_LIST_ALBUM', 'photocrati-nextgen_pro_list_album');
        define('NGG_PRO_MASONRY', 'photocrati-nextgen_pro_masonry');
        define('NGG_PRO_MOSAIC', 'photocrati-nextgen_pro_mosaic');
        define('NGG_PRO_SEARCH', 'imagely-pro-search');
        define('NGG_PRO_TAGCLOUD', 'nextgen-pro-tagcloud');
        define('NGG_PRO_SIDESCROLL', 'photocrati-nextgen_pro_sidescroll');
        define('NGG_PRO_SLIDESHOW', 'photocrati-nextgen_pro_slideshow');
        define('NGG_PRO_THUMBNAIL_GRID', 'photocrati-nextgen_pro_thumbnail_grid');
        define('NGG_PRO_TILE', 'photocrati-nextgen_pro_tile');
        // Lightbox constants.
        define('NGG_PRO_LIGHTBOX', 'photocrati-nextgen_pro_lightbox');
        define('NGG_PRO_LIGHTBOX_TRIGGER', NGG_PRO_LIGHTBOX);
        define('NGG_PRO_LIGHTBOX_COMMENT_TRIGGER', 'photocrati-nextgen_pro_lightbox_comments');
        define('NGG_PRO_LIGHTBOX_VERSION', '3.99.0');
    }
    /**
     * Define the constants for the Pro product.
     *
     * @remove-for-nextgen-plus
     * @remove-for-nextgen-starter
     *
     * @return void
     */
    public function define_pro_constants()
    {
        if ('pro' !== self::$plugin_id) {
            return;
        }
        define('NGG_PRO_PLUGIN_BASENAME', plugin_basename(self::$plugin_filename));
        define('NGG_PRO_PLUGIN_VERSION', self::$plugin_version);
        // TODO: this is used outside of the POPE modules and should be removed.
        define('NGG_PRO_ECOMMERCE_MODULE_VERSION', '3.99.0');
        // Pricelist sources.
        define('NGG_PRO_DIGITAL_DOWNLOADS_SOURCE', 'ngg_digital_downloads');
        define('NGG_PRO_MANUAL_PRICELIST_SOURCE', 'ngg_manual_pricelist');
        define('NGG_PRO_WHCC_PRICELIST_SOURCE', 'ngg_whcc_pricelist');
        // Pricelist categories. The values of these constants are the 'ngg_id' attribute in the WHCC catalog source data.
        define('NGG_PRO_ECOMMERCE_CATEGORY_ACRYLIC_PRINTS', 'acrylic_prints');
        define('NGG_PRO_ECOMMERCE_CATEGORY_BAMBOO_PANELS', 'bamboo_panels');
        define('NGG_PRO_ECOMMERCE_CATEGORY_CANVAS', 'ngg_category_canvas');
        define('NGG_PRO_ECOMMERCE_CATEGORY_DIGITAL_DOWNLOADS', 'ngg_category_digital_downloads');
        define('NGG_PRO_ECOMMERCE_CATEGORY_METAL_PRINTS', 'metal_prints');
        define('NGG_PRO_ECOMMERCE_CATEGORY_MOUNTED_PRINTS', 'ngg_category_mounted_prints');
        define('NGG_PRO_ECOMMERCE_CATEGORY_PRINTS', 'ngg_category_prints');
        define('NGG_PRO_ECOMMERCE_CATEGORY_WOOD_PRINTS', 'wood_prints');
        // Commerce shipping methods.
        define('NGG_PRO_ECOMMERCE_SHIPPING_METHOD_CANADA', 'ngg_canada_shipping');
        define('NGG_PRO_ECOMMERCE_SHIPPING_METHOD_ECONOMY', 'ngg_economy_shipping');
        define('NGG_PRO_ECOMMERCE_SHIPPING_METHOD_EXPEDITED', 'ngg_expedited_shipping');
        define('NGG_PRO_ECOMMERCE_SHIPPING_METHOD_FREE', 'ngg_free_shipping');
        define('NGG_PRO_ECOMMERCE_SHIPPING_METHOD_INTERNATIONAL', 'ngg_international_shipping');
        define('NGG_PRO_ECOMMERCE_SHIPPING_METHOD_MANUAL', 'ngg_manual_shipping');
        define('NGG_PRO_ECOMMERCE_SHIPPING_METHOD_PRIORITY', 'ngg_priority_shipping');
        define('NGG_PRO_ECOMMERCE_SHIPPING_METHOD_STANDARD', 'ngg_standard_shipping');
        if (!defined('NGG_PRO_ECOMMERCE_DEFAULT_MARKUP')) {
            define('NGG_PRO_ECOMMERCE_DEFAULT_MARKUP', 300);
        }
        if (!defined('NGG_PRO_WHCC_CATALOG_TTL')) {
            define('NGG_PRO_WHCC_CATALOG_TTL', 86400);
            // 24 hours.
        }
    }
    /**
     * Registers the autoloader for the plugin.
     *
     * @param string $class_name The class to load.
     *
     * @return void
     */
    public function autoloader($class_name)
    {
        $prefix = 'Imagely\\NGGPro\\';
        $len = strlen($prefix);
        if (0 !== strncmp($prefix, $class_name, $len)) {
            return;
        }
        $relative_class = substr($class_name, $len);
        $file = self::$plugin_directory_root . 'src' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
    /**
     * Loads the POPE product which provides admin pages and functions
     *
     * @return void
     */
    public function load_pope_product()
    {
        $registry = \C_Component_Registry::get_instance();
        $registry->add_module_path(self::$plugin_directory_root . 'modules' . DIRECTORY_SEPARATOR, 2);
        $registry->load_product('photocrati-nextgen-pro');
        $registry->initialize_all_modules();
    }
    /**
     * Registers the hooks for the plugin.
     *
     * @return void
     */
    public function register_hooks()
    {
        add_action('init', [$this, 'load_i18n'], 2);
        \Imagely\NGGPro\DisplayType\Manager::register();
        /**
         * Plus and Starter products are not yet available.
         *
         * @remove-for-nextgen-plus
         * @remove-for-nextgen-starter
         */
        if ('pro' === self::$plugin_id) {
            $this->register_pro_only_hooks();
        }
        /**
         * Plus product is not yet available.
         *
         * @remove-for-nextgen-starter
         */
        if ('starter' !== self::$plugin_id) {
            $this->register_pro_and_plus_hooks();
        }
        // When first downloaded from imagely.com or members.photocrati.com the plugin zip will contain a license.php.
        // file in the root of the plugin directory. This lets the License Manager know where to find it.
        add_filter('photocrati_license_path_list', function ($path_list, $product) {
            $path_list[] = self::$plugin_directory_root;
            return $path_list;
        }, 10, 2);
        \Imagely\NGGPro\License\Manager::register_hooks();
        \Imagely\NGGPro\Display\HiDPI::register_hooks();
        (new \Imagely\NGGPro\Install\UsageTracking())->hooks();
        $license_actions = new LicenseActions();
        $license_actions->hooks();
    }
    /**
     * Registers hooks that are only available to the Pro product.
     *
     * @remove-for-nextgen-plus
     * @remove-for-nextgen-starter
     *
     * @return void
     */
    public function register_pro_only_hooks()
    {
        if ('pro' !== self::$plugin_id) {
            return;
        }
        // Substitute the original datamapper classes with our extensions that provide the pricelist_id attribute.
        add_filter('ngg_datamapper_client_image', function ($class_name) {
            return '\\Imagely\\NGGPro\\Commerce\\DataMappers\\Image';
        });
        add_filter('ngg_datamapper_client_gallery', function ($class_name) {
            return '\\Imagely\\NGGPro\\Commerce\\DataMappers\\Gallery';
        });
        \Imagely\NGGPro\Commerce\Manager::register_hooks();
        \Imagely\NGGPro\Proofing\Manager::get_instance()->register_hooks();
        \Imagely\NGGPro\Dribbble\Manager::register_hooks();
        if (defined('WP_CLI') && \WP_CLI) {
            \Imagely\NGGPro\WPCLI\Manager::register();
        }
        // The following is a hack: we cannot move some XHR requests to the REST API because the URL are registered.
        // with 3rd party sources like Stripe or PayPal or the way the request is made would require updating our.
        // AWS/Lambda endpoints. To avoid dependencies on the deprecated ajax POPE module we only add the 'init' hook.
        // here if the URL matches.
        $legacy_ajax_actions = ['get_print_lab_order', 'get_print_lab_order_action', 'paypal_checkout_webhook_listener', 'stripe_webhook_handler', 'stripe_webhook_handler_action'];
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        if (!empty($_REQUEST['photocrati_ajax']) && !empty($_REQUEST['action']) && '1' === (string) sanitize_text_field(wp_unslash($_REQUEST['photocrati_ajax'])) && in_array($_REQUEST['action'], $legacy_ajax_actions, true)) {
            // phpcs:enable WordPress.Security.NonceVerification.Recommended
            // IT IS IMPORTANT that this run with a priority of 8.
            add_action('init', ['\\Imagely\\NGGPro\\Commerce\\REST\\LegacyAjaxHandler', 'run'], 8);
        }
    }
    /**
     * Registers hooks that are available to both the Pro and Plus products.
     *
     * @remove-for-nextgen-starter
     *
     * @return void
     */
    public function register_pro_and_plus_hooks()
    {
        if ('starter' === self::$plugin_id) {
            return;
        }
        // Provides default settings for commerce, proofing, and captions.
        add_filter('ngg_datamapper_client_display_type', function ($class_name) {
            return '\\Imagely\\NGGPro\\DataMappers\\DisplayType';
        });
        add_action('rest_api_init', ['\\Imagely\\NGGPro\\REST\\Manager', 'rest_api_init']);
        \Imagely\NGGPro\DisplayType\Manager::register_hooks();
        \Imagely\NGGPro\Display\HoverCaptions::register_hooks();
        \Imagely\NGGPro\Display\ImageProtection\Manager::register_hooks();
        \Imagely\NGGPro\Lightbox\CommentsManager::register_hooks();
        \Imagely\NGGPro\Lightbox\Manager::register_hooks();
        \Imagely\NGGPro\Display\Animations\Manager::register_hooks();
        \Imagely\NGGPro\TikTok\Manager::register_hooks();
    }
    /**
     * Registers the plugins textdomain for gettext translations.
     *
     * @return void
     */
    public function load_i18n()
    {
        $dir = basename(self::$plugin_directory_root) . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'I18N';
        load_plugin_textdomain('nextgen-gallery-pro', false, $dir);
    }
    /**
     * Displays a notice if NextGEN Gallery is missing or incompatible.
     *
     * @throws \Exception If the plugin ID is not recognized.
     *
     * @return void
     */
    public function admin_notices()
    {
        $nextgen_version = defined('NGG_PLUGIN_VERSION') ? NGG_PLUGIN_VERSION : null;
        // Plugin level.
        $plugin_level = self::$plugin_id;
        // License status.
        $license_status = get_option('ngg_license_status_' . $plugin_level);
        if (class_exists('\\Imagely\\NGGPro\\License\\Manager')) {
            // Get the license key value.
            $licensing = new \Imagely\NGGPro\License\Manager();
            $licensing->is_valid_license();
            $license_key = esc_html($licensing->get_license($licensing::get_current_product()));
            if (empty($license_key)) {
                $message = sprintf(
                    // translators: %1$s and %3$s are <strong> tags, %2$s is plugin level, %4$s and %5$s are <a> tags.
                    esc_html__('To activate your Imagely %1$s%2$s%3$s license, please enter your license key here, Imagely > Settings > License. %4$sSettings Page%5$s', 'nextgen-gallery-pro'),
                    '<strong>',
                    ucfirst($plugin_level),
                    '</strong>',
                    '<a href="' . esc_url(admin_url('admin.php?page=imagely-settings&tab=license')) . '">',
                    '</a>'
                );
                echo wp_kses_post('<div class="error"><p>' . $message . '</p></div>');
            }
        }
        if (!empty($license_status)) {
            if ('expired' === $license_status) {
                $message = sprintf(
                    // translators: %1$s is plugin level and %2$s is plugin status, %3$s and %4$s are <a> tags. %5$s and %6$s are <strong> tags.
                    __('%5$sYour license key for NextGEN Gallery %1$s has %2$s.%6$s Please renew your license at %3$simagely.com%4$s.', 'nextgen-gallery-pro'),
                    ucfirst($plugin_level),
                    $license_status,
                    '<a target="_blank" href="http://www.imagely.com">',
                    '</a>',
                    '<strong>',
                    '</strong>'
                );
                echo wp_kses_post('<div class="error"><p>' . $message . '</p></div>');
            }
            if ('disabled' === $license_status || 'revoked' === $license_status) {
                $message = sprintf(
                    // translators: %1$s is plugin level and %2$s is plugin status. %3$s and %4$s are <strong> tags.
                    __('%3$sYour license key for NextGEN Gallery %1$s has %2$s.%4$s Please use a different key to continue receiving automatic updates.', 'nextgen-gallery-pro'),
                    ucfirst($plugin_level),
                    $license_status,
                    '<strong>',
                    '</strong>'
                );
                echo wp_kses_post('<div class="error"><p>' . $message . '</p></div>');
            }
            if ('invalid' === $license_status) {
                $message = sprintf(
                    // translators: %1$s is plugin level and %2$s is plugin status. %3$s and %4$s are <strong> tags.
                    __('%3$sYour license key for NextGEN Gallery %1$s is %2$s.%4$s The key no longer exists or the user associated with the key has been deleted. Please use a different key to continue receiving automatic updates', 'nextgen-gallery-pro'),
                    ucfirst($plugin_level),
                    $license_status,
                    '<strong>',
                    '</strong>'
                );
                echo wp_kses_post('<div class="error"><p>' . $message . '</p></div>');
            }
        }
        if (!$nextgen_version) {
            $message = sprintf(
                // translators: %s is the name of the plugin.
                __('Please install &amp; activate <a href="http://wordpress.org/plugins/nextgen-gallery/" target="_blank">NextGEN Gallery</a> to allow %s to work.', 'nextgen-gallery-pro'),
                self::get_plugin_name()
            );
            echo wp_kses_post('<div class="updated"><p>' . $message . '</p></div>');
        } elseif (-1 === version_compare($nextgen_version, self::$minimum_ngg_version)) {
            $upgrade_url = \admin_url('/plugin-install.php?tab=plugin-information&plugin=nextgen-gallery&section=changelog&TB_iframe=true&width=640&height=250');
            $message = sprintf(__("NextGEN Gallery %1\$s is incompatible with %2\$s %3\$s. Please update <a class='thickbox' href='%4\$s'>NextGEN Gallery</a> to version %5\$s or higher. %6\$s has been deactivated.", 'nextgen-gallery-pro'), $nextgen_version, self::get_plugin_name(), self::$plugin_version, $upgrade_url, self::$minimum_ngg_version, self::get_plugin_name());
            echo wp_kses_post('<div class="updated"><p>' . $message . '</p></div>');
            \deactivate_plugins(\plugin_basename(self::$plugin_filename));
        } elseif (\delete_option('photocrati_pro_recently_activated')) {
            $message = __('To activate the Imagely Pro Lightbox please go to Imagely > Settings > Lightbox Effects.', 'nextgen-gallery-pro');
            echo wp_kses_post('<div class="updated"><p>' . $message . '</p></div>');
        }
    }
}