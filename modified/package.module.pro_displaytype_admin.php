<?php
/**
 * Provides form fields for album settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Album_Form
 */
class A_NextGen_Pro_Album_Form extends Mixin_Display_Type_Form
{
    /**
     * Enqueues static resources required by this form
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script('nextgen_pro_albums_settings_script', $this->object->get_static_url('imagely-pro_displaytype_admin#album_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, true);
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['thumbnail_override_settings', 'nextgen_pro_albums_display_type', 'nextgen_pro_albums_enable_breadcrumbs', 'nextgen_pro_albums_caption_color', 'nextgen_pro_albums_caption_size', 'nextgen_pro_albums_border_color', 'nextgen_pro_albums_border_size', 'nextgen_pro_albums_background_color', 'nextgen_pro_albums_padding', 'nextgen_pro_albums_spacing', 'nextgen_pro_albums_child_descriptions', 'display_type_view'];
    }
    /**
     * Renders the child descriptions field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_child_descriptions_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'enable_descriptions', __('Display album and gallery descriptions', 'nextgen-gallery-pro'), $display_type->settings['enable_descriptions']);
    }
    /**
     * Let users choose which display type galleries inside albums use
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_display_type_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $mapper = \Imagely\NGGPro\DataMappers\DisplayType::get_instance();
        $types = [];
        foreach ($mapper->find_by_entity_type('image') as $dt) {
            $types[$dt->name] = $dt->title;
        }
        return $this->_render_select_field($display_type, 'gallery_display_type', __('Display galleries as', 'nextgen-gallery-pro'), $types, $display_type->settings['gallery_display_type'], __('How would you like galleries to be displayed?', 'nextgen-gallery-pro'));
    }
    /**
     * Render the enable breadcrumbs field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_enable_breadcrumbs_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'enable_breadcrumbs', __('Enable breadcrumbs', 'nextgen-gallery-pro'), isset($display_type->settings['enable_breadcrumbs']) ? $display_type->settings['enable_breadcrumbs'] : false);
    }
    /**
     * Renders the caption color settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_caption_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_color_field($display_type, 'caption_color', __('Caption color', 'nextgen-gallery-pro'), $display_type->settings['caption_color']);
    }
    /**
     * Renders the caption size settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_caption_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'caption_size', __('Caption size', 'nextgen-gallery-pro'), $display_type->settings['caption_size'], '', false, '', 0);
    }
    /**
     * Renders the border color settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_border_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_color_field($display_type, 'border_color', __('Border color', 'nextgen-gallery-pro'), $display_type->settings['border_color']);
    }
    /**
     * Renders the border size settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_border_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'border_size', __('Border size', 'nextgen-gallery-pro'), $display_type->settings['border_size'], '', false, '', 0);
    }
    /**
     * Renders the background color settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_background_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_color_field($display_type, 'background_color', __('Background color', 'nextgen-gallery-pro'), $display_type->settings['background_color']);
    }
    /**
     * Renders the padding settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_padding_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'padding', __('Padding', 'nextgen-gallery-pro'), $display_type->settings['padding'], '', false, '', 0);
    }
    /**
     * Renders the spacing settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_albums_spacing_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'spacing', __('Spacing', 'nextgen-gallery-pro'), $display_type->settings['spacing'], '', false, '', 0);
    }
}
/**
 * Adds animations related settings to supporting display types.
 *
 * @package NextGEN Pro
 */
/**
 * Remove this class when building Plus and Starter.
 *
 * @remove-for-nextgen-plus
 * @remove-for-nextgen-starter
 * @property C_Form $object
 */
class A_NextGen_Pro_Animations_Form extends Mixin
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $fields = $this->call_parent('_get_field_names');
        $fields[] = 'nextgen_pro_image_animation_enable';
        $fields[] = 'nextgen_pro_image_animation_style';
        $fields[] = 'nextgen_pro_image_animation_duration';
        $fields[] = 'nextgen_pro_image_animation_delay';
        $fields[] = 'nextgen_pro_pagination_animation_enable';
        $fields[] = 'nextgen_pro_pagination_animation_style';
        $fields[] = 'nextgen_pro_pagination_animation_duration';
        $fields[] = 'nextgen_pro_pagination_animation_delay';
        return $fields;
    }
    /**
     * Enqueues javascript resources used by this form.
     *
     * @throws Exception If the parent method is not callable.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script('nextgen_pro_animations_settings_style', $this->object->get_static_url('imagely-pro_displaytype_admin#animation.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, true);
        wp_localize_script('nextgen_pro_animations_settings_style', 'nextgen_pro_animations_display_types_images', \Imagely\NGGPro\Display\Animations\Manager::get_supported_image_types());
        wp_localize_script('nextgen_pro_animations_settings_style', 'nextgen_pro_animations_display_types_pagination', \Imagely\NGGPro\Display\Animations\Manager::get_supported_pagination_display_types());
    }
    /**
     * Returns the rendered HTML of the 'enable image animations' field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type this setting applies to.
     * @return string
     */
    public function _render_nextgen_pro_image_animation_enable_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'animate_images_enable', __('Animate images', 'nextgen-gallery-pro'), $display_type->settings['animate_images_enable'] ?? false);
    }
    /**
     * Returns the rendered HTML of the 'animation style' field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type this setting applies to.
     * @return string
     */
    public function _render_nextgen_pro_image_animation_style_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_select_field($display_type, 'animate_images_style', __('Animation style', 'nextgen-gallery-pro'), \Imagely\NGGPro\Display\Animations\Manager::get_styles(), $display_type->settings['animate_images_style'] ?? 'wobble', '', !($display_type->settings['animate_images_enable'] ?? false));
    }
    /**
     * Returns the rendered HTML of the 'animation duration' field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type this setting applies to.
     * @return string
     */
    public function _render_nextgen_pro_image_animation_duration_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'animate_images_duration', __('Animation duration', 'nextgen-gallery-pro'), $display_type->settings['animate_images_duration'] ?? 1500, __('Measured in milliseconds', 'nextgen-gallery-pro'), !($display_type->settings['animate_images_enable'] ?? false), '', 0);
    }
    /**
     * Returns the rendered HTML of the 'animation delay' field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type this setting applies to.
     * @return string
     */
    public function _render_nextgen_pro_image_animation_delay_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'animate_images_delay', __('Animation delay', 'nextgen-gallery-pro'), $display_type->settings['animate_images_delay'] ?? 250, __('Measured in milliseconds', 'nextgen-gallery-pro'), !($display_type->settings['animate_images_enable'] ?? false), '', 0);
    }
    /**
     * Returns the rendered HTML of the 'enable pagination animations' field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type this setting applies to.
     * @return string
     */
    public function _render_nextgen_pro_pagination_animation_enable_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        if (!in_array($display_type->name, \Imagely\NGGPro\Display\Animations\Manager::get_supported_pagination_display_types(), true)) {
            return '';
        }
        return $this->object->_render_radio_field($display_type, 'animate_pagination_enable', __('Animate pagination', 'nextgen-gallery-pro'), $display_type->settings['animate_pagination_enable'] ?? false);
    }
    /**
     * Returns the rendered HTML of the 'animation style' field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type this setting applies to.
     * @return string
     */
    public function _render_nextgen_pro_pagination_animation_style_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        if (!in_array($display_type->name, \Imagely\NGGPro\Display\Animations\Manager::get_supported_pagination_display_types(), true)) {
            return '';
        }
        return $this->object->_render_select_field($display_type, 'animate_pagination_style', __('Animation style', 'nextgen-gallery-pro'), \Imagely\NGGPro\Display\Animations\Manager::get_styles(), $display_type->settings['animate_pagination_style'] ?? 'flipInX', '', !($display_type->settings['animate_pagination_enable'] ?? false));
    }
    /**
     * Returns the rendered HTML of the 'animation duration' field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type this setting applies to.
     * @return string
     */
    public function _render_nextgen_pro_pagination_animation_duration_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        if (!in_array($display_type->name, \Imagely\NGGPro\Display\Animations\Manager::get_supported_pagination_display_types(), true)) {
            return '';
        }
        return $this->object->_render_number_field($display_type, 'animate_pagination_duration', __('Animation duration', 'nextgen-gallery-pro'), $display_type->settings['animate_pagination_duration'] ?? 1500, __('Measured in milliseconds', 'nextgen-gallery-pro'), !($display_type->settings['animate_pagination_enable'] ?? false), '', 0);
    }
    /**
     * Returns the rendered HTML of the 'animation delay' field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type this setting applies to.
     * @return string
     */
    public function _render_nextgen_pro_pagination_animation_delay_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        if (!in_array($display_type->name, \Imagely\NGGPro\Display\Animations\Manager::get_supported_pagination_display_types(), true)) {
            return '';
        }
        return $this->object->_render_number_field($display_type, 'animate_pagination_delay', __('Animation delay', 'nextgen-gallery-pro'), $display_type->settings['animate_pagination_delay'] ?? 250, __('Measured in milliseconds', 'nextgen-gallery-pro'), !($display_type->settings['animate_pagination_enable'] ?? false), '', 0);
    }
}
/**
 * Provides form fields for blog gallery settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Blog_Form
 */
class A_NextGen_Pro_Blog_Form extends Mixin_Display_Type_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_BLOG_GALLERY;
    }
    /**
     * Enqueue static resources required by this form
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script($this->object->get_display_type_name() . '-js', $this->get_static_url('imagely-pro_displaytype_admin#blog_gallery_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, true);
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['image_override_settings', 'nextgen_pro_blog_gallery_image_display_size', 'nextgen_pro_blog_gallery_image_max_height', 'nextgen_pro_blog_gallery_spacing', 'nextgen_pro_blog_gallery_border_size', 'nextgen_pro_blog_gallery_border_color', 'nextgen_pro_blog_gallery_display_captions', 'nextgen_pro_blog_gallery_caption_location', 'display_type_view'];
    }
    /**
     * Renders the gallery border size field
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_blog_gallery_border_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'border_size', __('Border size', 'nextgen-gallery-pro'), $display_type->settings['border_size'], '', false, '', 0);
    }
    /**
     * Renders the gallery border color field
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_blog_gallery_border_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_color_field($display_type, 'border_color', __('Border color', 'nextgen-gallery-pro'), $display_type->settings['border_color']);
    }
    /**
     * Renders the image display size field
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_blog_gallery_image_display_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'image_display_size', __('Image display size', 'nextgen-gallery-pro'), $display_type->settings['image_display_size'], __('Measured in pixels', 'nextgen-gallery-pro'), false, __('image width', 'nextgen-gallery-pro'), 0);
    }
    /**
     * Renders the image max height field
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_blog_gallery_image_max_height_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'image_max_height', __('Image maximum height', 'nextgen-gallery-pro'), $display_type->settings['image_max_height'], __('Measured in pixels. Empty or 0 will not impose a limit.', 'nextgen-gallery-pro'), false, '', 0);
    }
    /**
     * Renders the image spacing field
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_blog_gallery_spacing_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'spacing', __('Image spacing', 'nextgen-gallery-pro'), $display_type->settings['spacing'], __('Measured in pixels', 'nextgen-gallery-pro'), false, '', 0);
    }
    /**
     * Renders the display captions field
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_blog_gallery_display_captions_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'display_captions', __('Display captions', 'nextgen-gallery-pro'), $display_type->settings['display_captions']);
    }
    /**
     * Renders the caption location field
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_blog_gallery_caption_location_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_select_field($display_type, 'caption_location', __('Caption location', 'nextgen-gallery-pro'), ['above' => __('Above', 'nextgen-gallery-pro'), 'below' => __('Below', 'nextgen-gallery-pro')], $display_type->settings['caption_location'], '', !empty($display_type->settings['display_captions']) ? false : true);
    }
}
/**
 * Provides form fields for film settings.
 *
 * @package NextGEN Pro
 */
/**
 * Class A_NextGen_Pro_Film_Form
 *
 * @mixin C_Form
 * @property C_Form|Mixin_Display_Type_Form $object The object.
 */
class A_NextGen_Pro_Film_Form extends Mixin_Display_Type_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_FILM;
    }
    /**
     * Enqueue static resources.
     *
     * @throws Exception If the parent method does not exist.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        $name = $this->object->get_display_type_name() . '-js';
        wp_enqueue_script($name, $this->object->get_static_url('imagely-pro_displaytype_admin#film_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, true);
    }
    /**
     * Get field names.
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['thumbnail_override_settings', 'nextgen_pro_film_images_per_page', 'nextgen_pro_film_image_spacing', 'nextgen_pro_film_border_size', 'nextgen_pro_film_frame_size', 'nextgen_pro_film_border_color', 'nextgen_pro_film_frame_color', 'nextgen_pro_film_alttext_display', 'nextgen_pro_film_alttext_font_color', 'nextgen_pro_film_alttext_font_size', 'nextgen_pro_film_description_display', 'nextgen_pro_film_description_font_color', 'nextgen_pro_film_description_font_size', 'display_type_view'];
    }
    /**
     * Render the alttext display field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_alttext_display_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'alttext_display', __('Display image title', 'nextgen-gallery-pro'), $display_type->settings['alttext_display']);
    }
    /**
     * Render the alttext font color field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_alttext_font_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_color_field($display_type, 'alttext_font_color', __('Title font color', 'nextgen-gallery-pro'), $display_type->settings['alttext_font_color'], __('An empty color setting will use your theme colors', 'nextgen-gallery-pro'), empty($display_type->settings['alttext_display']) ? true : false);
    }
    /**
     * Render the alttext font size field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_alttext_font_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'alttext_font_size', __('Title font size', 'nextgen-gallery-pro'), $display_type->settings['alttext_font_size'], __('Measured in pixels. An empty or zero setting will use your theme font size', 'nextgen-gallery-pro'), empty($display_type->settings['alttext_display']) ? true : false, __('# of pixels', 'nextgen-gallery-pro'), 0);
    }
    /**
     * Render the description display field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_description_display_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'description_display', __('Display image description', 'nextgen-gallery-pro'), $display_type->settings['description_display']);
    }
    /**
     * Render the description font color field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_description_font_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_color_field($display_type, 'description_font_color', __('Description font color', 'nextgen-gallery-pro'), $display_type->settings['description_font_color'], __('An empty color setting will use your theme colors', 'nextgen-gallery-pro'), empty($display_type->settings['description_display']) ? true : false);
    }
    /**
     * Render the description font size field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_description_font_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'description_font_size', __('Description font size', 'nextgen-gallery-pro'), $display_type->settings['description_font_size'], __('Measured in pixels. An empty or zero setting will use your theme font size', 'nextgen-gallery-pro'), empty($display_type->settings['description_display']) ? true : false, __('# of pixels', 'nextgen-gallery-pro'), 0);
    }
    /**
     * Render the images per page field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_images_per_page_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'images_per_page', __('Images per page', 'nextgen-gallery-pro'), $display_type->settings['images_per_page'], __('"0" will display all images at once', 'nextgen-gallery-pro'), false, __('# of images', 'nextgen-gallery-pro'), 0);
    }
    /**
     * Render the border size field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_border_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'border_size', __('Border size', 'nextgen-gallery-pro'), $display_type->settings['border_size'], '', false, '', 0);
    }
    /**
     * Render the border color field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_border_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_color_field($display_type, 'border_color', __('Border color', 'nextgen-gallery-pro'), $display_type->settings['border_color']);
    }
    /**
     * Render the frame size field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_frame_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'frame_size', __('Frame size', 'nextgen-gallery-pro'), $display_type->settings['frame_size'], '', false, '', 0);
    }
    /**
     * Render the frame color field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_frame_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_color_field($display_type, 'frame_color', __('Frame color', 'nextgen-gallery-pro'), $display_type->settings['frame_color']);
    }
    /**
     * Render the image spacing field.
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_pro_film_image_spacing_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'image_spacing', __('Image spacing', 'nextgen-gallery-pro'), $display_type->settings['image_spacing'], '', false, '', 0);
    }
}
/**
 * Provides form fields for the grid album settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Grid_Album_Form.
 *
 * @property C_Form $object instance.
 */
class A_NextGen_Pro_Grid_Album_Form extends A_NextGen_Pro_Album_Form
{
    /**
     * Returns the title of this form.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_GRID_ALBUM;
    }
}
/**
 * Provides form fields for slideshow settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Slideshow_Form
 */
class A_NextGen_Pro_Slideshow_Form extends Mixin_Display_Type_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_SLIDESHOW;
    }
    /**
     * Enqueue static resources.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script($this->get_display_type_name() . '-js', $this->get_static_url('imagely-pro_displaytype_admin#slideshow_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, false);
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['nextgen_pro_slideshow_image_crop', 'nextgen_pro_slideshow_image_pan', 'nextgen_pro_slideshow_show_playback_controls', 'nextgen_pro_slideshow_show_captions', 'nextgen_pro_slideshow_caption_class', 'nextgen_pro_slideshow_caption_height', 'nextgen_pro_slideshow_aspect_ratio', 'nextgen_pro_slideshow_width_and_unit', 'nextgen_pro_slideshow_transition', 'nextgen_pro_slideshow_transition_speed', 'nextgen_pro_slideshow_slideshow_speed', 'nextgen_pro_slideshow_border_size', 'nextgen_pro_slideshow_border_color'];
    }
    /**
     * A similiar function is available in photocrati-nextgen_admin but has an inappropriate tooltip
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_width_and_unit_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->render_partial('photocrati-nextgen_admin#field_generator/nextgen_settings_field_width_and_unit', ['display_type_name' => $display_type->name, 'name' => 'width', 'label' => __('Gallery width', 'nextgen-gallery-pro'), 'value' => $display_type->settings['width'], 'text' => '', 'placeholder' => '', 'unit_name' => 'width_unit', 'unit_value' => $display_type->settings['width_unit'], 'options' => ['px' => __('Pixels', 'nextgen-gallery-pro'), '%' => __('Percent', 'nextgen-gallery-pro')]], true);
    }
    /**
     * Render the image crop field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_image_crop_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'image_crop', __('Crop images', 'nextgen-gallery-pro'), $display_type->settings['image_crop']);
    }
    /**
     * Render the image pan field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_image_pan_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'image_pan', __('Pan images', 'nextgen-gallery-pro'), $display_type->settings['image_pan'], '', empty($display_type->settings['image_crop']) ? true : false);
    }
    /**
     * Render the show captions field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_show_captions_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'show_captions', __('Show captions', 'nextgen-gallery-pro'), $display_type->settings['show_captions']);
    }
    /**
     * Render the caption class field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_caption_class_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_select_field($display_type, 'caption_class', __('Caption location', 'nextgen-gallery-pro'), ['caption_above_stage' => __('Top', 'nextgen-gallery-pro'), 'caption_below_stage' => __('Bottom', 'nextgen-gallery-pro'), 'caption_overlay_top' => __('Top (Overlay)', 'nextgen-gallery-pro'), 'caption_overlay_bottom' => __('Bottom (Overlay)', 'nextgen-gallery-pro')], $display_type->settings['caption_class'], '', empty($display_type->settings['show_captions']) ? true : false);
    }
    /**
     * Render the caption height field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_caption_height_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'caption_height', __('Caption height', 'nextgen-gallery-pro'), $display_type->settings['caption_height'], __('Measured in pixels', 'nextgen-gallery-pro'), empty($display_type->settings['show_captions']) ? true : false, __('pixels', 'nextgen-gallery-pro'), 1);
    }
    /**
     * Render the slideshow speed field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_slideshow_speed_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'slideshow_speed', __('Slideshow speed', 'nextgen-gallery-pro'), $display_type->settings['slideshow_speed'], __('Measured in seconds', 'nextgen-gallery-pro'), false, __('seconds', 'nextgen-gallery-pro'), 0);
    }
    /**
     * Render the transition field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_transition_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_select_field($display_type, 'transition', __('Transition effect', 'nextgen-gallery-pro'), ['fade' => __('Crossfade between images', 'nextgen-gallery-pro'), 'flash' => __('Fades into background color between images', 'nextgen-gallery-pro'), 'pulse' => __('Quickly move the image into the background color, then fade into the next image', 'nextgen-gallery-pro'), 'slide' => __('Slide images depending on image position', 'nextgen-gallery-pro'), 'fadeslide' => __('Fade between images and slide slightly at the same time', 'nextgen-gallery-pro')], $display_type->settings['transition'], '', false);
    }
    /**
     * Render the transition speed field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_transition_speed_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'transition_speed', __('Transition speed', 'nextgen-gallery-pro'), $display_type->settings['transition_speed'], __('Measured in seconds', 'nextgen-gallery-pro'), false, __('seconds', 'nextgen-gallery-pro'), 0);
    }
    /**
     * Render the border size field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_border_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'border_size', __('Border size', 'nextgen-gallery-pro'), $display_type->settings['border_size'], __('Borders will not be applied if "Crop Images" is enabled', 'nextgen-gallery-pro'), !empty($display_type->settings['image_crop']) ? true : false, '', 0);
    }
    /**
     * Render the border color field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_border_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_color_field($display_type, 'border_color', __('Border color', 'nextgen-gallery-pro'), $display_type->settings['border_color'], '', !empty($display_type->settings['image_crop']) ? true : false);
    }
    /**
     * Get the aspect ratio options.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_slideshow_aspect_ratio_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_select_field($display_type, 'aspect_ratio', __('Stage aspect ratio', 'nextgen-gallery-pro'), $this->_get_aspect_ratio_options(), $display_type->settings['aspect_ratio'], '', false);
    }
    /**
     * Get the playback control options.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return array
     */
    public function _render_nextgen_pro_slideshow_show_playback_controls_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'show_playback_controls', __('Show play controls', 'nextgen-gallery-pro'), $display_type->settings['show_playback_controls']);
    }
}
/**
 * Provides form fields for hover captions settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Captions_Form
 */
class A_NextGen_Pro_Captions_Form extends Mixin
{
    /**
     * Get field names.
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $fields = $this->call_parent('_get_field_names');
        $fields[] = 'nextgen_pro_captions_enabled';
        $fields[] = 'nextgen_pro_captions_display_sharing';
        $fields[] = 'nextgen_pro_captions_display_title';
        $fields[] = 'nextgen_pro_captions_display_description';
        $fields[] = 'nextgen_pro_captions_animation';
        return $fields;
    }
    /**
     * Enqueue static resources.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script('photocrati-nextgen_pro_captions_settings-js', $this->get_static_url('imagely-pro_displaytype_admin#hover_captions_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, true);
    }
    /**
     * Render the captions enabled field.
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_captions_enabled_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'captions_enabled', __('Enable caption overlay', 'nextgen-gallery-pro'), isset($display_type->settings['captions_enabled']) ? $display_type->settings['captions_enabled'] : false);
    }
    /**
     * Render the captions display sharing field.
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_captions_display_sharing_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'captions_display_sharing', __('Display share icons', 'nextgen-gallery-pro'), isset($display_type->settings['captions_display_sharing']) ? $display_type->settings['captions_display_sharing'] : true, '', empty($display_type->settings['captions_enabled']) ? true : false);
    }
    /**
     * Render the captions display title field.
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_captions_display_title_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'captions_display_title', __('Display image title', 'nextgen-gallery-pro'), isset($display_type->settings['captions_display_title']) ? $display_type->settings['captions_display_title'] : true, '', empty($display_type->settings['captions_enabled']) ? true : false);
    }
    /**
     * Render the captions display description field.
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_captions_display_description_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'captions_display_description', __('Display image description', 'nextgen-gallery-pro'), isset($display_type->settings['captions_display_description']) ? $display_type->settings['captions_display_description'] : true, '', empty($display_type->settings['captions_enabled']) ? true : false);
    }
    /**
     * Render the captions animation field.
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_captions_animation_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_select_field($display_type, 'captions_animation', __('Animation type', 'nextgen-gallery-pro'), ['fade' => __('Fade in', 'nextgen-gallery-pro'), 'slideup' => __('Slide up', 'nextgen-gallery-pro'), 'slidedown' => __('Slide down', 'nextgen-gallery-pro'), 'slideleft' => __('Slide left', 'nextgen-gallery-pro'), 'titlebar' => __('Titlebar', 'nextgen-gallery-pro'), 'plain' => __('Plain', 'nextgen-gallery-pro')], isset($display_type->settings['captions_animation']) ? $display_type->settings['captions_animation'] : 'slideup', '', empty($display_type->settings['captions_enabled']) ? true : false);
    }
}
/**
 * Provides form fields for image browser settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_ImageBrowser_Form
 */
class A_NextGen_Pro_ImageBrowser_Form extends Mixin_Display_Type_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_IMAGEBROWSER;
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['ajax_pagination', 'display_type_view'];
    }
}
/**
 * Provides form fields for list album settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_List_Album_Form
 */
class A_NextGen_Pro_List_Album_Form extends A_NextGen_Pro_Album_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_LIST_ALBUM;
    }
    /**
     * Get the form fields for the list album display type.
     *
     * Adds pro-list-album specific fields to the defaults provided in A_NextGen_Pro_ALbums_Form
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $fields = parent::_get_field_names();
        $fields[] = 'nextgen_pro_list_album_description_color';
        $fields[] = 'nextgen_pro_list_album_description_size';
        return $fields;
    }
    /**
     * Renders the description color field.
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_list_album_description_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_color_field($display_type, 'description_color', 'Description color', $display_type->settings['description_color']);
    }
    /**
     * Renders the description size field.
     *
     * @param C_Display_Type $display_type The display type.
     *
     * @return string
     */
    public function _render_nextgen_pro_list_album_description_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'description_size', 'Description size', $display_type->settings['description_size'], '', false, '', 0);
    }
}
/**
 * Provides form fields for masonry settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Masonry_Form
 */
class A_NextGen_Pro_Masonry_Form extends Mixin_Display_Type_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_MASONRY;
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['nextgen_pro_masonry_size', 'nextgen_pro_masonry_padding', 'nextgen_pro_masonry_center_gallery', 'display_type_view'];
    }
    /**
     * Renders the size settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_masonry_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'size', __('Maximum image width', 'nextgen-gallery-pro'), $display_type->settings['size'], __('Measured in pixels', 'nextgen-gallery-pro'));
    }
    /**
     * Renders the padding settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_masonry_padding_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'padding', __('Image padding', 'nextgen-gallery-pro'), $display_type->settings['padding'], __('Measured in pixels', 'nextgen-gallery-pro'));
    }
    /**
     * Renders the center gallery settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_masonry_center_gallery_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'center_gallery', __('Center the gallery', 'nextgen-gallery-pro'), $display_type->settings['center_gallery']);
    }
}
/**
 * Provides form fields for mosaic settings.
 *
 * @package NextGEN Pro
 */
/**
 * Class A_Mosaic_Form
 *
 * @mixin C_Form
 * @adapts I_Form using "photocrati-nextgen_pro_mosaic" context
 */
class A_Mosaic_Form extends Mixin_Display_Type_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_MOSAIC;
    }
    /**
     * Enqueue static resources.
     *
     * @throws Exception If the parent method does not exist.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script(NGG_PRO_MOSAIC . '_admin_settings_js', $this->object->get_static_url('imagely-pro_displaytype_admin#mosaic_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, true);
    }
    /**
     * Get field names.
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['display_type_view', 'mosaic_last_row', 'mosaic_lazy_load_batch', 'mosaic_lazy_load_enable', 'mosaic_lazy_load_initial', 'mosaic_margins', 'mosaic_row_height'];
    }
    /**
     * Renders the last row settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_mosaic_row_height_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $settings = $display_type->settings;
        return $this->_render_number_field($display_type, 'row_height', __('Row height', 'nextgen-gallery-pro'), $settings['row_height'], '', false, '', 6);
    }
    /**
     * Renders the margins settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_mosaic_margins_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $settings = $display_type->settings;
        return $this->_render_number_field($display_type, 'margins', __('Margins', 'nextgen-gallery-pro'), $settings['margins'], '', false, '', 0);
    }
    /**
     * Renders the last row settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_mosaic_last_row_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $settings = $display_type->settings;
        return $this->_render_select_field($display_type, 'last_row', __('Justify last row', 'nextgen-gallery-pro'), ['justify' => __('Justify', 'nextgen-gallery-pro'), 'nojustify' => __('Do not justify', 'nextgen-gallery-pro'), 'hide' => __('Hide', 'nextgen-gallery-pro')], $settings['last_row'], __('When aligning the last row some images may appear cropped. Select "Do not justify" to allow the last row to appear flush but "unfinished". "Hide" will omit any images that can not be justified without cropping.', 'nextgen-gallery-pro'));
    }
    /**
     * Renders the lazy load settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_mosaic_lazy_load_enable_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $settings = $display_type->settings;
        return $this->_render_radio_field($display_type, 'lazy_load_enable', __('Enable "lazy" image loading', 'nextgen-gallery-pro'), $settings['lazy_load_enable']);
    }
    /**
     * Renders the lazy load initial settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_mosaic_lazy_load_initial_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $settings = $display_type->settings;
        return $this->_render_number_field($display_type, 'lazy_load_initial', __('Images to display at start', 'nextgen-gallery-pro'), $settings['lazy_load_initial'], '', empty($settings['lazy_load_enable']) ? true : false);
    }
    /**
     * Renders the lazy load batch settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     *
     * @return string
     */
    public function _render_mosaic_lazy_load_batch_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $settings = $display_type->settings;
        return $this->_render_number_field($display_type, 'lazy_load_batch', __('Images to load when scrolling', 'nextgen-gallery-pro'), $settings['lazy_load_batch'], '', empty($settings['lazy_load_enable']) ? true : false);
    }
}
/**
 * Provides form fields for proofing settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-plus
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Proofing_Form
 */
class A_NextGen_Pro_Proofing_Form extends Mixin
{
    /**
     * Get field names.
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $fields = $this->call_parent('_get_field_names');
        $fields[] = 'nextgen_pro_proofing_display';
        return $fields;
    }
    /**
     * Render the proofing display field.
     *
     * @param object $display_type Display type object.
     *
     * @return string
     */
    public function _render_nextgen_pro_proofing_display_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'ngg_proofing_display', __('Enable proofing?', 'nextgen-gallery-pro'), isset($display_type->settings['ngg_proofing_display']) ? $display_type->settings['ngg_proofing_display'] : false, __('Trigger buttons or hover captions need to be enabled for proofing to work', 'nextgen-gallery-pro'));
    }
}
/**
 * Provides form fields for search settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_Search_Form
 */
class A_Search_Form extends Mixin_Display_Type_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_SEARCH;
    }
    /**
     * Enqueues static resources required by this form
     *
     * @throws Exception If the parent method is not callable.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        $this->object->enqueue_script('nextgen_image_search_admin_form_js', $this->object->get_static_url('imagely-pro_displaytype_admin#search_settings.js'));
    }
    /**
     * Get the field names.
     *
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['nextgen_frontend_search_gallery_display_type', 'nextgen_frontend_search_enable_tag_filter', 'nextgen_frontend_search_search_alttext', 'nextgen_frontend_search_search_description', 'nextgen_frontend_search_search_tags', 'nextgen_frontend_search_search_mode', 'nextgen_frontend_search_minimum_relevance', 'nextgen_frontend_search_limit', 'nextgen_frontend_search_order_by_relevance', 'nextgen_frontend_search_order_by', 'nextgen_frontend_search_order_direction'];
    }
    /**
     * Renders the search enable tag filter field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_enable_tag_filter_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'enable_tag_filter', __('Enable filtering results by tag', 'nextgen-gallery-pro'), $display_type->settings['enable_tag_filter']);
    }
    /**
     * Renders the search alttext field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_search_alttext_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'search_alttext', __('Search image alttext', 'nextgen-gallery-pro'), $display_type->settings['search_alttext']);
    }
    /**
     * Search description field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_search_description_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'search_description', __('Search image description', 'nextgen-gallery-pro'), $display_type->settings['search_description']);
    }
    /**
     * Field for search tags
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_search_tags_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'search_tags', __('Search image tags', 'nextgen-gallery-pro'), $display_type->settings['search_tags']);
    }
    /**
     * Search mode field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_search_mode_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $options = ['natural' => __('Natural language', 'nextgen-gallery-pro'), 'boolean' => __('Boolean', 'nextgen-gallery-pro')];
        return $this->object->_render_select_field($display_type, 'search_mode', __('Database search mode', 'nextgen-gallery-pro'), $options, $display_type->settings['search_mode'], __('A natural language search treats the requested string as a phrase in text without any operators except for quotation marks. A boolean search uses special rules and operators such as the plus and minus symbols.', 'nextgen-gallery-pro'));
    }
    /**
     * Search minimum relevance field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_minimum_relevance_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'minimum_relevance', __('Minimum relevance', 'nextgen-gallery-pro'), $display_type->settings['minimum_relevance'], __('The database server assigns a relevance score to each possible image based on a number of factors with zero being not at all relevant. Users with smaller databases or images whose alttext or description only holds a few words will need a lower number here; possibly as low as 0.05. It is unlikely many users will need to raise this beyond one.', 'nextgen-gallery-pro'), false, '', 0);
    }
    /**
     * Search limit field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_limit_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field($display_type, 'limit', __('Limit search results', 'nextgen-gallery-pro'), $display_type->settings['limit'], __('Limit search results to this amount. A setting of zero means no limitations are applied', 'nextgen-gallery-pro'), false, '', 0);
    }
    /**
     * Search order by relevance field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_order_by_relevance_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_radio_field($display_type, 'order_by_relevance', __('Order by relevance first', 'nextgen-gallery-pro'), $display_type->settings['order_by_relevance'], __('When enabled search results will be ordered by their relevance first, then by the secondary order setting', 'nextgen-gallery-pro'));
    }
    /**
     * Render the order by field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_order_by_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $options = ['pid' => __('Image ID', 'nextgen-gallery-pro'), 'galleryid' => __('Gallery ID', 'nextgen-gallery-pro'), 'filename' => __('Image filename', 'nextgen-gallery-pro'), 'imagedate' => __('Image date (EXIF or time of upload)', 'nextgen-gallery-pro')];
        return $this->object->_render_select_field($display_type, 'order_by', __('Order search results by', 'nextgen-gallery-pro'), $options, $display_type->settings['order_by']);
    }
    /**
     * Render the order direction field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_order_direction_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $options = ['ASC' => __('Ascending', 'nextgen-gallery-pro'), 'DESC' => __('Descending', 'nextgen-gallery-pro')];
        return $this->object->_render_select_field($display_type, 'order_direction', __('Order direction of search results', 'nextgen-gallery-pro'), $options, $display_type->settings['order_direction']);
    }
    /**
     * Gallery display type field
     *
     * @param C_Display_Type $display_type The display type.
     * @return string
     */
    public function _render_nextgen_frontend_search_gallery_display_type_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $options = [];
        $types = \Imagely\NGGPro\DataMappers\DisplayType::get_instance()->find_by_entity_type('image');
        foreach ($types as $type) {
            if (!empty($type->hidden_from_ui) && $type->hidden_from_ui) {
                continue;
            }
            if (NGG_PRO_SEARCH === $type->name) {
                continue;
            }
            $options[$type->name] = $type->title;
        }
        return $this->object->_render_select_field($display_type, 'gallery_display_type', __('Display results as', 'nextgen-gallery-pro'), $options, $display_type->settings['gallery_display_type']);
    }
}
/**
 * Provides form fields for the sidescroll settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Sidescroll_Form.
 */
class A_NextGen_Pro_Sidescroll_Form extends Mixin_Display_Type_Form
{
    /**
     * Get display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_SIDESCROLL;
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['nextgen_pro_sidescroll_height', 'display_type_view'];
    }
    /**
     * Renders the images_per_page settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type The display type object.
     * @return string
     */
    public function _render_nextgen_pro_sidescroll_height_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'height', __('Gallery Height', 'nextgen-gallery-pro'), $display_type->settings['height'], __('Provide desired gallery height in pixels.', 'nextgen-gallery-pro'), false, '', 0);
    }
}
/**
 * Provides form fields for horizontal filmstrip settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Horizontal_Filmstrip_Form
 */
class A_NextGen_Pro_Horizontal_Filmstrip_Form extends A_NextGen_Pro_Slideshow_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_HORIZONTAL_FILMSTRIP;
    }
    /**
     * Enqueue static resources.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script($this->object->get_display_type_name() . '-js', $this->get_static_url('imagely-pro_displaytype_admin#horizontal_filmstrip_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, true);
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $fields = parent::_get_field_names();
        $fields[] = 'thumbnail_override_settings';
        return $fields;
    }
}
/**
 * Provides form fields for thumbnail grid settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Thumbnail_Grid_Form
 */
class A_NextGen_Pro_Thumbnail_Grid_Form extends Mixin_Display_Type_Form
{
    /**
     * Get the display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_THUMBNAIL_GRID;
    }
    /**
     * Enqueue static resources.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script($this->object->get_display_type_name() . '-js', $this->object->get_static_url('imagely-pro_displaytype_admin#thumbnail_grid_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, false);
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['thumbnail_override_settings', 'nextgen_pro_thumbnail_grid_images_per_page', 'nextgen_pro_thumbnail_grid_border_size', 'nextgen_pro_thumbnail_grid_border_color', 'nextgen_pro_thumbnail_grid_spacing', 'nextgen_pro_thumbnail_grid_number_of_columns', 'display_type_view'];
    }
    /**
     * Renders the images_per_page settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     * @return string
     */
    public function _render_nextgen_pro_thumbnail_grid_images_per_page_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'images_per_page', __('Images per page', 'nextgen-gallery-pro'), $display_type->settings['images_per_page'], __('"0" will display all images at once', 'nextgen-gallery-pro'), false, __('# of images', 'nextgen-gallery-pro'), 0);
    }
    /**
     * Renders the border size settings field
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     * @return string
     */
    public function _render_nextgen_pro_thumbnail_grid_border_size_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'border_size', __('Border size', 'nextgen-gallery-pro'), $display_type->settings['border_size'], '', false, '', 0);
    }
    /**
     * Render the thumbnail grid spacing field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     * @return string
     */
    public function _render_nextgen_pro_thumbnail_grid_spacing_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'spacing', __('Spacing', 'nextgen-gallery-pro'), $display_type->settings['spacing']);
    }
    /**
     * Render the thumbnail grid number of columns field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     * @return string
     */
    public function _render_nextgen_pro_thumbnail_grid_number_of_columns_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'number_of_columns', __('Number of columns to display', 'nextgen-gallery-pro'), $display_type->settings['number_of_columns'], __('An empty or zero in this field will use a responsive layout', 'nextgen-gallery-pro'));
    }
    /**
     * Render the thumbnail grid border color field.
     *
     * @param \Imagely\NGG\DataTypes\DisplayType $display_type Display type object.
     * @return string
     */
    public function _render_nextgen_pro_thumbnail_grid_border_color_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_color_field($display_type, 'border_color', __('Border color', 'nextgen-gallery-pro'), $display_type->settings['border_color']);
    }
}
/**
 * Provides form fields for the tile settings.
 *
 * @package NextGEN Pro
 * @remove-for-nextgen-starter
 */
/**
 * Class A_NextGen_Pro_Tile_Form.
 */
class A_NextGen_Pro_Tile_Form extends Mixin_Display_Type_Form
{
    /**
     * Get display type name.
     *
     * @return string
     */
    public function get_display_type_name()
    {
        return NGG_PRO_TILE;
    }
    /**
     * Enqueue static resources.
     *
     * @return void
     */
    public function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script($this->get_display_type_name() . '-js', $this->get_static_url('imagely-pro_displaytype_admin#tile_settings.js'), ['jquery.nextgen_radio_toggle'], \Imagely\NGGPro\Bootloader::$plugin_version, false);
    }
    /**
     * Returns a list of fields to render on the settings page
     *
     * @return array
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return ['nextgen_pro_tile_override_maximum_width', 'nextgen_pro_tile_maximum_width'];
    }
    /**
     * Render the override maximum width field.
     *
     * @param C_Display_Type $display_type display type.
     * @return string Rendered HTML
     */
    public function _render_nextgen_pro_tile_override_maximum_width_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_radio_field($display_type, 'override_maximum_width', __('Override maximum gallery width', 'nextgen-gallery-pro'), $display_type->settings['override_maximum_width'], __("Gallery width is set to your theme's content width but this can be overridden to create smaller galleries. If your theme does not provide the \$content_width feature the default will fallback to 2000px.", 'nextgen-gallery-pro'));
    }
    /**
     * Render the maximum width field.
     *
     * @param C_Display_Type $display_type display type.
     * @return string Rendered HTML
     */
    public function _render_nextgen_pro_tile_maximum_width_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'maximum_width', __('Maximum gallery width', 'nextgen-gallery-pro'), $display_type->settings['maximum_width'], __('Measured in pixels', 'nextgen-gallery-pro'), empty($display_type->settings['override_maximum_width']) ? true : false, '', 100);
    }
    /**
     * Render the margin field.
     *
     * @param C_Display_Type $display_type display type.
     * @return string Rendered HTML
     */
    public function _render_nextgen_pro_tile_margin_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->_render_number_field($display_type, 'margin', __('Margin between blocks of images', 'nextgen-gallery-pro'), $display_type->settings['margin'], __('Measured in pixels', 'nextgen-gallery-pro'), false, 0, 0);
    }
}


/**
 * Admin settings form for the NextGEN Pro TagCloud display type.
 *
 * Extends A_Search_Form so all 11 proven render methods are inherited unchanged.
 * We override only:
 *   - get_display_type_name()  to target NGG_PRO_TAGCLOUD
 *   - _get_field_names()       to prepend the 3 TagCloud-specific fields
 *   - _render_nextgen_frontend_search_gallery_display_type_field()
 *                              to exclude TagCloud from its own "Display results as" list
 *
 * The TagCloud-specific render methods use raw HTML for the text input (no
 * _render_text_field helper exists in the Pope framework) and
 * $this->object->_render_number_field for the two count fields.
 */
class A_ProTagCloud_Form extends A_Search_Form
{
    /**
     * Bind this form to the TagCloud display type.
     */
    public function get_display_type_name()
    {
        return NGG_PRO_TAGCLOUD;
    }

    /**
     * Prepend the 3 TagCloud-specific fields before the inherited Search fields.
     */
    public function _get_field_names()
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return array_merge(
            [
                'ngg_pro_tagcloud_required_container_ids',
                'ngg_pro_tagcloud_related_tag_maxcount',
                'ngg_pro_tagcloud_related_tag_maxcount_mobile',
            ],
            parent::_get_field_names()
        );
    }

    /**
     * Required tag names – rendered as a plain text input.
     * No _render_text_field helper exists in the Pope framework, so we return
     * raw HTML wrapped in the same markup pattern the other helpers produce.
     *
     * @param C_Display_Type $display_type
     * @return string
     */
    public function _render_ngg_pro_tagcloud_required_container_ids_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $field_id    = esc_attr(NGG_PRO_TAGCLOUD . '_required_container_ids');
        $field_name  = esc_attr(NGG_PRO_TAGCLOUD . '[required_container_ids]');
        $field_value = esc_attr($display_type->settings['required_container_ids'] ?? '');
        $label       = esc_html__('Required tag names', 'nextgen-gallery-pro');
        $description = esc_html__('Comma-separated tag names. Images must have ALL listed tags to appear by default. Multi-word tags are supported, e.g. "blue ocean,coastal scenery". Leave blank to start empty.', 'nextgen-gallery-pro');

        return '<div class="ngg_setting_container">
                    <div class="ngg_setting_label">
                        <label for="' . $field_id . '">' . $label . '</label>
                    </div>
                    <div class="ngg_setting_field">
                        <input type="text"
                               id="' . $field_id . '"
                               name="' . $field_name . '"
                               value="' . $field_value . '"
                               class="ngg_setting_text_field"
                               style="width:100%"/>
                        <p class="ngg_setting_description">' . $description . '</p>
                    </div>
                </div>';
    }

    /**
     * Max related-tag buttons shown on desktop.
     *
     * @param C_Display_Type $display_type
     * @return string
     */
    public function _render_ngg_pro_tagcloud_related_tag_maxcount_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field(
            $display_type,
            'related_tag_maxcount',
            __('Max tag buttons – desktop', 'nextgen-gallery-pro'),
            $display_type->settings['related_tag_maxcount'] ?? 20,
            __('Top N tags by usage count, displayed A–Z. 0 = no limit.', 'nextgen-gallery-pro'),
            false, '', 0
        );
    }

    /**
     * Max related-tag buttons shown on mobile.
     *
     * @param C_Display_Type $display_type
     * @return string
     */
    public function _render_ngg_pro_tagcloud_related_tag_maxcount_mobile_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        return $this->object->_render_number_field(
            $display_type,
            'related_tag_maxcount_mobile',
            __('Max tag buttons – mobile', 'nextgen-gallery-pro'),
            $display_type->settings['related_tag_maxcount_mobile'] ?? 10,
            __('Applied when wp_is_mobile() returns true. 0 = no limit.', 'nextgen-gallery-pro'),
            false, '', 0
        );
    }

    /**
     * Override the "Display results as" dropdown to exclude the TagCloud itself
     * (can't nest TagCloud inside itself) and the Search display type.
     *
     * @param C_Display_Type $display_type
     * @return string
     */
    public function _render_nextgen_frontend_search_gallery_display_type_field($display_type)
    {
        // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $options = [];
        $types   = \Imagely\NGGPro\DataMappers\DisplayType::get_instance()->find_by_entity_type('image');
        foreach ($types as $type) {
            if (!empty($type->hidden_from_ui) && $type->hidden_from_ui) {
                continue;
            }
            if (in_array($type->name, [NGG_PRO_SEARCH, NGG_PRO_TAGCLOUD], true)) {
                continue;
            }
            $options[$type->name] = $type->title;
        }
        return $this->object->_render_select_field(
            $display_type,
            'gallery_display_type',
            __('Display results as', 'nextgen-gallery-pro'),
            $options,
            $display_type->settings['gallery_display_type'] ?? NGG_PRO_MOSAIC
        );
    }
}
