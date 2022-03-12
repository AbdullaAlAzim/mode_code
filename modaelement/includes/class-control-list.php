<?php

use Elementor\Base_Data_Control;

/**
 * Elementor
 *
 * @since 1.0.0
 */
class Moda_Lists_Control extends Base_Data_Control
{

    /**
     * Get emoji one area control type.
     *
     * Retrieve the control type, in this case `ct_lists_pricing`.
     *
     * @return string Control type.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_type()
    {
        return 'moda_lists_control';
    }

    /**
     * Enqueue emoji one area control scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by the emoji one
     * area control.
     *
     * @since 1.0.0
     * @access public
     */


    public function enqueue()
    {
        wp_register_script('moda-list-control', MODA_PLUG_URL . 'modaelement/assets/js/controls/listscontrol.js', array(), '1.0.0');
        wp_enqueue_script('moda-list-control');
    }

    /**
     * Render emoji one area control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     * @access public
     */
    public function content_template()
    {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
            <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-content">{{{ data.label
                }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper">
                <textarea id="<?php echo esc_attr($control_uid); ?>" class="elementor-control-tag-area"
                          data-setting="{{ data.name }}" style="display: none;"></textarea>
                <#
                var value = data.controlValue;
                #>
                <div class="moda-group">
                    <#
                    var template = '
                    <div class="moda-group-item"
                         style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; position: relative"><a
                                class="moda-group-delete" href="#"
                                style="position: absolute; z-index: 9; right: 10px; top: 8px;">&times;</a>
                        <div class="elementor-control elementor-label-block">
                            <div class="elementor-control-content">
                                <div class="elementor-control-field"><label
                                            class="elementor-control-content"><?php esc_html_e('Content', 'moda') ?></label>
                                    <div class="elementor-control-input-wrapper"><input type="text"
                                                                                        class="elementor-control-tag-area elementor-input moda-list-content"/>';
                                        template += '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                    #>
                    <textarea class="moda-template" style="display: none;">{{{ template }}}</textarea>
                    <#
                    if(data.controlValue){
                    var values = JSON.parse(data.controlValue);
                    _.each( values, function( item, index ) {
                    var content_list_val = item.content_list;
                    #>
                    <div class="moda-group-item"
                         style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; position: relative;">
                        <a class="moda-group-delete" href="#"
                           style="position: absolute; z-index: 9; right: 10px; top: 8px;">&times;</a>
                        <div class="elementor-control elementor-label-block">
                            <div class="elementor-control-content">
                                <div class="elementor-control-field">
                                    <label class="elementor-control-content">{{{ data.label }}}</label>
                                    <div class="elementor-control-input-wrapper">
                                        <input id="<?php echo esc_attr($control_uid); ?>" type="text"
                                               class="elementor-control-tag-area elementor-input moda-list-content"
                                               value="{{ content_list_val }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <#
                    } );
                    }
                    #>
                </div>
                <div class="moda-group-actions" style="text-align: center;">
                    <button class="elementor-button elementor-button-default moda-group-add" type="button">
                        <i class="eicon-plus" aria-hidden="true"></i>
                        <span>{{{ data.label }}}</span>
                    </button>
                </div>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Get emoji one area control default settings.
     *
     * Retrieve the default settings of the emoji one area control. Used to return
     * the default settings while initializing the emoji one area control.
     *
     * @return array Control default settings.
     * @since 1.0.0
     * @access protected
     *
     */
    protected function get_default_settings()
    {
        return [
            'label_block' => true,
        ];
    }

}