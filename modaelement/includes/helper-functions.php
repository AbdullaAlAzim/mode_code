<?php

// Exit if accessed directly
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH'))
    exit;

function get_builder_image($url = '', $class = '')
{
    if ($url) {
        return '<a class="' . $class . '" href="' . esc_url(home_url('/')) . '"><img src="' . esc_url($url) . '" alt="' . get_bloginfo('name') . '"></a>';
    }

}


function moda_options($opt)
{
    $options = get_option('_modatheme');
    if (isset($options[$opt])) {
        return $options[$opt];
    }
}

function moda_meta($opt)
{
    $options = get_post_meta(get_the_ID(), '_modameta', true);
    if (isset($options[$opt])) {
        return $options[$opt];
    }
}

function moda_nav_menu_objects($items, $args)
{
    foreach ($items as &$item) {

        $icon = get_post_meta($item->ID, 'icon', true);
        // alternative: $icon = $item->icon;

        if (!empty($icon)) {
            $item->title = '<span><i class="' . $icon . '"></i>' . $item->title . '</span>';
        }
    }
    return $items;

}

add_filter('wp_nav_menu_objects', 'moda_nav_menu_objects', 10, 2);
// Override Elementor Section Advance Tab

add_action('elementor/element/after_section_end', function ($element, $section_id, $args) {
    /** @var Element_Base $element */
    if ('section' === $element->get_name() && 'section_custom_css_pro' === $section_id) {

        $element->start_controls_section(
            'sticky_section',
            [
                'tab' => Controls_Manager::TAB_ADVANCED,
                'label' => __('Moda Settings', 'moda'),
            ]
        );

        $element->add_control(
            'header_sticky',
            [
                'label' => __('Sticky Section', 'moda'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'moda'),
                'label_off' => __('No', 'moda'),
                'return_value' => 'moda-sticky-header',
                'default' => '',
                'prefix_class' => '',
            ]
        );
        $element->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'sticky_bg',
                'label' => __('Main BG', 'moda'),
                'types' => ['classic', 'gradient'],
                'show_label' => true,
                'condition' => [
                    'header_sticky' => 'moda-sticky-header'
                ],
                'selector' => '{{WRAPPER}}.moda-sticky-header.sticky-on',
            ]
        );
        $element->add_responsive_control(
            'sticky_padding',
            [
                'label' => esc_html__('Padding', 'moda'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'condition' => [
                    'header_sticky' => 'moda-sticky-header'
                ],
                'selectors' => [
                    '{{WRAPPER}}.moda-sticky-header.sticky-on' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $element->end_controls_section();
        $element->start_controls_section(
            '_section_position',
            [
                'label' => __('Positioning', 'moda'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_responsive_control(
            '_element_width',
            [
                'label' => __('Width', 'moda'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default', 'moda'),
                    'inherit' => __('Full Width', 'moda') . ' (100%)',
                    'auto' => __('Inline', 'moda') . ' (auto)',
                    'initial' => __('Custom', 'moda'),
                ],
                'selectors_dictionary' => [
                    'inherit' => '100%',
                ],
                'prefix_class' => 'elementor-widget%s__width-',
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{VALUE}}; max-width: {{VALUE}}',
                ],
            ]
        );

        $element->add_responsive_control(
            '_element_custom_width',
            [
                'label' => __('Custom Width', 'moda'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    '_element_width' => 'initial',
                ],
                'device_args' => [
                    Controls_Stack::RESPONSIVE_TABLET => [
                        'condition' => [
                            '_element_width_tablet' => ['initial'],
                        ],
                    ],
                    Controls_Stack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            '_element_width_mobile' => ['initial'],
                        ],
                    ],
                ],
                'size_units' => ['px', '%', 'vw'],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $element->add_responsive_control(
            '_element_vertical_align',
            [
                'label' => __('Vertical Align', 'moda'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'moda'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center', 'moda'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'moda'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    '_element_width!' => '',
                    '_position' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'align-self: {{VALUE}}',
                ],
            ]
        );

        $element->add_control(
            '_position_description',
            [
                'raw' => '<strong>' . __('Please note!', 'moda') . '</strong> ' . __('Custom positioning is not considered best practice for responsive web design and should not be used too frequently.', 'moda'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $element->add_control(
            '_position',
            [
                'label' => __('Position', 'moda'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default', 'moda'),
                    'absolute' => __('Absolute', 'moda'),
                    'fixed' => __('Fixed', 'moda'),
                ],
                'prefix_class' => 'elementor-',
                'frontend_available' => true,
            ]
        );

        $start = is_rtl() ? __('Right', 'moda') : __('Left', 'moda');
        $end = !is_rtl() ? __('Right', 'moda') : __('Left', 'moda');

        $element->add_control(
            '_offset_orientation_h',
            [
                'label' => __('Horizontal Orientation', 'moda'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $element->add_responsive_control(
            '_offset_x',
            [
                'label' => __('Offset', 'moda'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%', 'vw', 'vh'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_h!' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $element->add_responsive_control(
            '_offset_x_end',
            [
                'label' => __('Offset', 'moda'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%', 'vw', 'vh'],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_h' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $element->add_control(
            '_offset_orientation_v',
            [
                'label' => __('Vertical Orientation', 'moda'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => __('Top', 'moda'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => __('Bottom', 'moda'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $element->add_responsive_control(
            '_offset_y',
            [
                'label' => __('Offset', 'moda'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_v!' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $element->add_responsive_control(
            '_offset_y_end',
            [
                'label' => __('Offset', 'moda'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_v' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $element->end_controls_section();
    }
}, 10, 3);

// Override Elementor Heading Elements

add_action('elementor/element/heading/section_title_style/before_section_start', function ($element, $args) {
    /** @var Element_Base $element */
    $element->start_controls_section(
        'text_style',
        [
            'tab' => Controls_Manager::TAB_STYLE,
            'label' => __('Moda Settings', 'moda'),
        ]
    );
    $element->add_control(
        'text_gra',
        [
            'label' => __('Text Gradient', 'moda'),
            'type' => Controls_Manager::HEADING,
        ]
    );
    $element->add_control(
        'text_transparent',
        [
            'label' => __('Transparent', 'moda'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'moda'),
            'label_off' => __('No', 'moda'),
            'return_value' => 'moda-text-gradient',
            'default' => 'false',
            'prefix_class' => '',
        ]
    );
    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'text_gradient',
            'label' => __('Text Gradient', 'moda'),
            'types' => ['gradient'],
            'show_label' => true,
            'separator' => 'after',
            'condition' => [
                'text_transparent' => 'moda-text-gradient'
            ],
            'selector' => '{{WRAPPER}}.moda-text-gradient .elementor-heading-title',
        ]
    );
    $element->add_control(
        'text_sr',
        [
            'label' => __('Text Stroke', 'moda'),
            'type' => Controls_Manager::HEADING,
        ]
    );
    $element->add_control(
        'text_str',
        [
            'label' => __('Turn On Stroke', 'moda'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'moda'),
            'label_off' => __('No', 'moda'),
            'return_value' => 'moda-text-stroke',
            'default' => 'false',
            'prefix_class' => '',
        ]
    );
    $element->add_control(
        's_fill_color',
        [
            'label' => __('Fill Color', 'moda'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'text_str' => 'moda-text-stroke'
            ],
            'selectors' => [
                '{{WRAPPER}}.moda-text-stroke .elementor-heading-title' => '-webkit-text-fill-color: {{VALUE}}',
            ],
        ]
    );
    $element->add_responsive_control(
        's_width',
        [
            'label' => __('Stroke Width', 'appilo'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => .5,
                    'max' => 100,
                    'step' => .5,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.moda-text-stroke .elementor-heading-title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'text_str' => 'moda-text-stroke'
            ],
        ]
    );
    $element->add_control(
        's_text_color',
        [
            'label' => __('Stroke Color', 'moda'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'text_str' => 'moda-text-stroke'
            ],
            'selectors' => [
                '{{WRAPPER}}.moda-text-stroke .elementor-heading-title' => '-webkit-text-stroke-color: {{VALUE}}',
            ],
        ]
    );
    $element->end_controls_section();
}, 10, 2);

// Override Elementor Icon Elements

add_action('elementor/element/button/section_style/before_section_start', function ($element, $args) {
    /** @var Element_Base $element */
    $element->start_controls_section(
        'aatext_style',
        [
            'tab' => Controls_Manager::TAB_STYLE,
            'label' => __('Moda Settings', 'appilo'),
        ]
    );
    $element->add_control(
        'button_before',
        [
            'label' => __('Turn On Before', 'moda'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'moda'),
            'label_off' => __('No', 'moda'),
            'return_value' => 'moda-before',
            'default' => 'moda-before-none',
            'prefix_class' => '',
        ]
    );
    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'before_bg',
            'label' => __('Button Background', 'moda'),
            'types' => ['classic', 'gradient'],
            'show_label' => true,
            'separator' => 'after',
            'condition' => [
                'button_before' => ['moda-before']
            ],
            'selector' => '{{WRAPPER}}.moda-before .elementor-button:before',
        ]
    );
    $element->add_responsive_control(
        'bra_btn',
        [
            'label' => __('Border Radius', 'moda'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'condition' => [
                'button_before' => ['moda-before']
            ],
            'selectors' => [
                '{{WRAPPER}}.moda-before .elementor-button:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $element->start_controls_tabs('button_style');
    $element->start_controls_tab(
        'button_normal',
        [
            'label' => __('Normal', 'appilo'),
        ]
    );
    $element->add_control(
        'atext_gra',
        [
            'label' => __('Button Background', 'appilo'),
            'type' => Controls_Manager::HEADING,
        ]
    );
    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'btn_bg',
            'label' => __('Button Background', 'appilo'),
            'types' => ['gradient'],
            'show_label' => true,
            'separator' => 'after',
            'selector' => '{{WRAPPER}} .elementor-button',
        ]
    );
    $element->end_controls_tab();

    $element->start_controls_tab(
        'button_hover',
        [
            'label' => __('Hover', 'appilo'),
        ]
    );

    $element->add_control(
        'afatext_gra',
        [
            'label' => __('Hover Background', 'appilo'),
            'type' => Controls_Manager::HEADING,
        ]
    );
    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'btnh_bg',
            'label' => __('Button Background', 'appilo'),
            'types' => ['gradient'],
            'show_label' => true,
            'separator' => 'after',
            'selector' => '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus',
        ]
    );
    $element->end_controls_tab();

    $element->end_controls_tabs();

    $element->end_controls_section();
}, 10, 2);

// Override Elementor Icon Elements

add_action('elementor/element/icon/section_style_icon/before_section_start', function ($element, $args) {
    /** @var Element_Base $element */
    $element->start_controls_section(
        'aatext_style',
        [
            'tab' => Controls_Manager::TAB_STYLE,
            'label' => __('Moda Settings', 'appilo'),
        ]
    );
    $element->add_control(
        'icon_light_box',
        [
            'label' => __('Turn On LightBox', 'moda'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'moda'),
            'label_off' => __('No', 'moda'),
            'return_value' => 'moda-icon-lightbox',
            'default' => 'false',
            'prefix_class' => '',
        ]
    );
    $element->add_control(
        'icon_hotspot',
        [
            'label' => __('Turn On Hotspot', 'moda'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'moda'),
            'label_off' => __('No', 'moda'),
            'return_value' => 'moda-icon-hotspot',
            'default' => 'false',
            'prefix_class' => '',
        ]
    );
    $element->add_control(
        'icon_gradient',
        [
            'label' => __('Turn On Gradient', 'moda'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'moda'),
            'label_off' => __('No', 'moda'),
            'return_value' => 'moda-icon-gradient',
            'default' => 'false',
            'prefix_class' => '',
        ]
    );
    $element->start_controls_tabs('button_style');

    $element->start_controls_tab(
        'button_normal',
        [
            'label' => __('Normal', 'appilo'),
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
        ]
    );
    $element->add_control(
        'atext_grai',
        [
            'label' => __('Color', 'appilo'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
        ]
    );
    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'btn_bgi',
            'label' => __('Icon Color', 'appilo'),
            'types' => ['gradient'],
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
            'show_label' => true,
            'separator' => 'after',
            'selector' => '{{WRAPPER}}.moda-icon-gradient .elementor-icon i',
        ]
    );
    $element->add_control(
        'atext_gra',
        [
            'label' => __('Background', 'appilo'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
        ]
    );
    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'btn_bg',
            'label' => __('Background', 'appilo'),
            'types' => ['gradient'],
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
            'show_label' => true,
            'separator' => 'after',
            'selector' => '{{WRAPPER}}.moda-icon-gradient .elementor-icon',
        ]
    );
    $element->end_controls_tab();

    $element->start_controls_tab(
        'button_hover',
        [
            'label' => __('Hover', 'appilo'),
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
        ]
    );
    $element->add_control(
        'atext_graih',
        [
            'label' => __('Color', 'appilo'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
        ]
    );
    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'btn_bgih',
            'label' => __('Icon Color', 'appilo'),
            'types' => ['gradient'],
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
            'show_label' => true,
            'separator' => 'after',
            'selector' => '{{WRAPPER}}.moda-icon-gradient .elementor-icon:hover i',
        ]
    );
    $element->add_control(
        'afatext_gra',
        [
            'label' => __('Background', 'appilo'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
        ]
    );
    $element->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'btnh_bg',
            'label' => __('Button Background', 'appilo'),
            'types' => ['gradient'],
            'show_label' => true,
            'condition' => [
                'icon_gradient' => 'moda-icon-gradient'
            ],
            'separator' => 'after',
            'selector' => '{{WRAPPER}}.moda-icon-gradient .elementor-icon:hover',
        ]
    );
    $element->end_controls_tab();

    $element->end_controls_tabs();

    $element->end_controls_section();
}, 10, 2);


function ae_drop_taxolist()
{

    $args = array(
        'public' => true,
        '_builtin' => false

    );
    $output = 'names'; // or objects
    $operator = 'or'; // 'and' or 'or'

    $taxonomies = get_taxonomies($args, $output, $operator);
    return $taxonomies;

}

function moda_list_control($settings, $icon, $tag = "li")
{
    if (!empty($settings)) {
        $content_decode = json_decode($settings, true);
        foreach ($content_decode as $value) {
            echo "<$tag>" . $icon . " " . $value['content_list'] . "</$tag>";
        }
    }
}

function ae_check_odd_even($data)
{
    if ($data % 2 == 0) {
        $data = "Even";
    } else {
        $data = "Odd";
    }

    return $data;
}

function client_ratings($count)
{
    $out = '';
    for ($i = 0; $i < $count; $i++) {
        $out .= '<i class="fas fa-star"></i>';
    }
    return $out;
}

function get_that_link($link)
{

    $url = $link['url'] ? 'href=' . esc_url($link['url']) . '' : '';
    $ext = $link['is_external'] ? 'target= _blank' : '';
    $nofollow = $link['nofollow'] ? 'rel="nofollow"' : '';
    $link = $url . ' ' . $ext . ' ' . $nofollow;
    return $link;
}

function get_that_image($source, $class = 'image')
{
    if ($source) {
        $image = '<img class="' . $class . '" src="' . esc_url($source['url']) . '" alt="' . get_bloginfo('name') . '">';
    }
    return $image;
}

function get_wp_image($source)
{
    if (isset($source)) {
        $image = wp_get_attachment_image($source['id'], 'full');
    }
    return $image;

}

function king_buildermeta_to_string($items)
{
    if (!is_array($items) || empty($items)) {
        return;
    }
    foreach ($items as $item) {
        $metaf[] = $item['meta'];
    }
    return implode(',', $metaf);
}

function king_menu_select_choices()
{
    $menus = wp_get_nav_menus();
    $items = array();
    $i = 0;
    foreach ($menus as $menu) {
        if ($i == 0) {
            $default = $menu->slug;
            $i++;
        }
        $items[$menu->slug] = $menu->name;
    }

    return $items;
}


function ae_image_size_choose()
{
    $image_sizes = get_intermediate_image_sizes();

    $addsizes = array(
        "full" => 'Full size'
    );
    $newsizes = array_merge($image_sizes, $addsizes);

    return array_combine($newsizes, $newsizes);
}

/* Menu columns */

function fw_grid_col($column_size = 3)
{

    $style_class = 'col-lg-3';

    $column_styles = array(
        1 => 'col-lg-12',
        2 => 'col-lg-6',
        3 => 'col-lg-4',
        4 => 'col-lg-3',
        5 => 'col-lg-15',
    );

    if (array_key_exists($column_size, $column_styles) && !empty($column_styles[$column_size])) {
        $style_class = $column_styles[$column_size];
    }

    return $style_class;
}

function modaelement_template_select()
{

    global $post;
    $args = array('numberposts' => -1, 'post_type' => 'elementor_library',);
    $posts = get_posts($args);

    foreach ($posts as $pn_cat) {
        $categories[$pn_cat->ID] = get_the_title($pn_cat->ID);
    }
    return $categories;

}

/*Meta Fields*/
function modaelement_animations()
{
    return array(
        '' => esc_html__('No animation', 'modaelement'),
        'reveal-top' => esc_html__('Animate from top', 'modaelement'),
        'reveal-bottom' => esc_html__('Animate from bottom', 'modaelement'),
        'reveal-left' => esc_html__('Animate from left', 'modaelement'),
        'reveal-right' => esc_html__('Animate from right', 'modaelement'),
    );
}


/* Return the css class name to help achieve the number of columns specified */

function ae_get_column_class($column_size = 3, $no_margin = false)
{

    $style_class = 'col-three';
    $column_styles = array(
        1 => 'col-one',
        2 => 'col-two',
        3 => 'col-three',
        4 => 'col-four',
        5 => 'col-five'
    );

    if (array_key_exists($column_size, $column_styles) && !empty($column_styles[$column_size])) {
        $style_class = $column_styles[$column_size];
    }

    $style_class = $no_margin ? ($style_class . ' has-padding') : $style_class;

    return $style_class;
}

/* Return the css class name to help achieve the number of columns specified */

function modaelement_gird_column($column_size = '3')
{

    $style_class = 'col-md-3';

    $column_styles = array(
        1 => 'col-md-12',
        2 => 'col-md-6 col-sm-6 col-xs-12',
        3 => 'col-md-4 col-sm-6 col-xs-12',
        4 => 'col-md-3 col-sm-6 col-xs-12',
        5 => 'col-md-15',
    );
    if (array_key_exists($column_size, $column_styles) && !empty($column_styles[$column_size])) {
        $style_class = $column_styles[$column_size];
    }

    return $style_class;
}

/* Return gallery grid */

function modaelement_gallery_column($column_size = 3)
{

    $style_class = 'gallery-columns-1';

    $column_styles = array(
        2 => 'gallery-columns-2',
        3 => 'gallery-columns-3',
        4 => 'gallery-columns-4',
    );

    if (array_key_exists($column_size, $column_styles) && !empty($column_styles[$column_size])) {
        $style_class = $column_styles[$column_size];
    }

    return $style_class;
}

function ae_bg_images($thumbnail = 'full')
{

    global $post;
    $post_id = $post->ID;
    $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $thumbnail);
    if (!$featured_image) {
        return;
    }
    $image_url = $featured_image[0];
    $lazy = 'data-bg=' . $image_url . '';

    $bg_image = 'background-image:url(' . $image_url . ');';
    $out = ($bg_image) ? 'style=' . $bg_image . '' : '';

    echo $out;

}

function ap_limited_excerpt($num)
{
    $limit = $num + 1;
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    array_pop($excerpt);
    $excerpt = implode(" ", $excerpt) . " ";
    return $excerpt;
}

function get_hansel_and_gretel_breadcrumbs()
{
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = ' | '; // delimiter between crumbs
    $home = 'Home'; // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb

    global $post;
    $homeLink = get_bloginfo('url');
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
        }
    } else {
        echo '<div id="crumbs"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
            }
            echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
        } elseif (is_search()) {
            echo $before . 'Search results for "' . get_search_query() . '"' . $after;
        } elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) {
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                if ($showCurrent == 1) {
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                }
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');
                if ($showCurrent == 0) {
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                echo $cats;
                if ($showCurrent == 1) {
                    echo $before . get_the_title() . $after;
                }
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) {
                echo $before . get_the_title() . $after;
            }
        } elseif (is_page() && $post->post_parent) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs) - 1) {
                    echo ' ' . $delimiter . ' ';
                }
            }
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_tag()) {
            echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . 'Articles posted by ' . $userdata->display_name . $after;
        } elseif (is_404()) {
            echo $before . 'Error 404' . $after;
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ' (';
            }
            echo __('Page') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ')';
            }
        }
        echo '</div>';
    }
}

function moda_page_title($arg)
{

    if (is_category()) {
        /* translators: Category archive title. 1: Category name */
        $title = single_cat_title($arg['cat'], false);
    } elseif (is_tag()) {
        /* translators: Tag archive title. 1: Tag name */
        $title = single_cat_title($arg['tag'], false);
    } elseif (is_author()) {
        $title = sprintf($arg['author'] . '%s', '<span>' . get_the_author() . '</span>');
        //$title = get_the_author( 'Author: ', true );
    } elseif (is_year()) {
        /* translators: Yearly archive title. 1: Year */
        $title = sprintf($arg['yarchive'], '<span>' . get_the_date('F Y', 'yearly archives date format') . '</span>');
    } elseif (is_month()) {
        /* translators: Monthly archive title. 1: Month name and year */
        $title = sprintf($arg['marchive'], '<span>' . get_the_date('F Y', 'monthly archives date format') . '</span>');
    } elseif (is_404()) {
        /* translators: Daily archive title. 1: Date */
        $title = $arg['notfound'];
    } elseif (is_post_type_archive()) {
        /* translators: Post type archive title. 1: Post type name */
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $tax = get_taxonomy(get_queried_object()->taxonomy);
        /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
        $title = single_term_title('', false);
    } elseif (is_search()) {
        $title = sprintf($arg['search'] . '%s', '<span>' . get_search_query() . '</span>');
    } elseif (is_home() && is_front_page()) {
        $title = esc_html__('Frontpage', 'moda');
    } elseif (is_singular()) {
        $title = get_the_title();
    } else {
        $title = esc_html__('Archives', 'moda');
    }

    return $title;

}
