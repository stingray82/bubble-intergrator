<?php
/**
 * Plugin Name: Bubble Integrator
 * Description: A custom block to integrate Bubble iframe.
 * Version: 1.0
 * Author: STINGRAY82
 */
function bubble_integrator_block_init() {
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        (function(wp) {
            if (!wp || !wp.element || !wp.blocks) {
                return;
            }

            // Unique variable names for Bubble block
            var elBubble = wp.element.createElement;
            var InspectorControlsBubble = wp.blockEditor.InspectorControls;
            var TextControlBubble = wp.components.TextControl;
            var PanelBodyBubble = wp.components.PanelBody;
            var ToggleControlBubble = wp.components.ToggleControl;
            var SelectControlBubble = wp.components.SelectControl;
            var RangeControlBubble = wp.components.RangeControl;
            var useBlockPropsBubble = wp.blockEditor.useBlockProps;

            // Define a unique SVG icon for the Bubble Integrator block
            var blockIconBubble = elBubble('svg', { 
                xmlns: "http://www.w3.org/2000/svg", 
                viewBox: "0 0 256 256", 
                fill: "none"
            },
                elBubble('rect', { width: "256", height: "256", rx: "64", fill: "white" }),
                elBubble('circle', { cx: "128", cy: "128", r: "97.1852", stroke: "#4557F6", strokeWidth: "23.7037" }),
                elBubble('path', { d: "M129.188 182.517C119.358 182.517 110.786 180.146 101.103 174.56C92.5882 169.648 85.5159 162.582 80.5957 154.072", stroke: "#4557F6", strokeWidth: "23.7037", strokeLinecap: "round", strokeLinejoin: "round" })
            );

            wp.blocks.registerBlockType('custom/bubble-integrator', {
                title: 'Bubble Integrator',
                icon: blockIconBubble, // Use the unique icon variable here
                category: 'embed',
                attributes: {
                    embedID: {
                        type: 'string',
                        default: '',
                    },
                    sizePreset: {
                        type: 'string',
                        default: 'medium',
                    },
                    width: {
                        type: 'number',
                        default: 100,
                    },
                    height: {
                        type: 'number',
                        default: 56.25,
                    },
                    margin: {
                        type: 'number',
                        default: 0,
                    },
                    border: {
                        type: 'string',
                        default: '0',
                    },
                    allowFullscreen: {
                        type: 'boolean',
                        default: true,
                    },
                },
                edit: function(props) {
                    var embedID = props.attributes.embedID;
                    var sizePreset = props.attributes.sizePreset;

                    function onChangeEmbedIDBubble(newID) {
                        if (newID.startsWith('http')) {
                            var extractedID = newID.split('/')[3];  
                            props.setAttributes({ embedID: extractedID });
                        } else {
                            props.setAttributes({ embedID: newID });
                        }
                    }

                    function getSizeStylesBubble(preset, width, height) {
                        switch (preset) {
                            case 'small':
                                return { width: '40%', paddingBottom: '30%' };
                            case 'medium':
                                return { width: '60%', paddingBottom: '33.75%' };
                            case 'large':
                                return { width: '80%', paddingBottom: '38.1%' };
                            case 'manual':
                                return { width: width + '%', paddingBottom: height + '%' };
                            default:
                                return { width: '60%', paddingBottom: '33.75%' };
                        }
                    }

                    var sizeStylesBubble = getSizeStylesBubble(sizePreset, props.attributes.width, props.attributes.height);

                    var iframeURLBubble = embedID 
                        ? `https://app.usebubbles.com/embed/${embedID}`
                        : '';
                    
                    var blockPropsBubble = useBlockPropsBubble({
                        className: 'bubble-integrator-block',
                        style: {
                            margin: props.attributes.margin + 'px auto',
                            border: props.attributes.border + 'px solid black',
                            position: 'relative',
                            width: sizeStylesBubble.width,
                            height: 0,
                            paddingBottom: sizeStylesBubble.paddingBottom,
                            overflow: 'hidden',
                        }
                    });

                    return elBubble('div', blockPropsBubble,
                        elBubble(InspectorControlsBubble, {},
                            elBubble(PanelBodyBubble, { title: 'Bubble Integrator Settings', initialOpen: true },
                                elBubble(TextControlBubble, {
                                    label: 'Embed ID or URL',
                                    value: embedID,
                                    onChange: onChangeEmbedIDBubble,
                                    placeholder: 'Enter the unique ID or full URL here',
                                }),
                                elBubble(SelectControlBubble, {
                                    label: 'Size Preset',
                                    value: sizePreset,
                                    options: [
                                        { label: 'Small', value: 'small' },
                                        { label: 'Medium', value: 'medium' },
                                        { label: 'Large', value: 'large' },
                                        { label: 'Manual', value: 'manual' }
                                    ],
                                    onChange: function(newPreset) {
                                        props.setAttributes({ sizePreset: newPreset });
                                    }
                                }),
                                sizePreset === 'manual' && elBubble(RangeControlBubble, {
                                    label: 'Width (%)',
                                    value: props.attributes.width,
                                    onChange: function(newWidth) {
                                        props.setAttributes({ width: newWidth });
                                    },
                                    min: 10,
                                    max: 100,
                                }),
                                sizePreset === 'manual' && elBubble(RangeControlBubble, {
                                    label: 'Height (%)',
                                    value: props.attributes.height,
                                    onChange: function(newHeight) {
                                        props.setAttributes({ height: newHeight });
                                    },
                                    min: 10,
                                    max: 100,
                                }),
                                elBubble(RangeControlBubble, {
                                    label: 'Margin (px)',
                                    value: props.attributes.margin,
                                    onChange: function(newMargin) {
                                        props.setAttributes({ margin: newMargin });
                                    },
                                    min: 0,
                                    max: 100,
                                }),
                                elBubble(TextControlBubble, {
                                    label: 'Border (px)',
                                    value: props.attributes.border,
                                    onChange: function(newBorder) {
                                        props.setAttributes({ border: newBorder });
                                    },
                                    placeholder: 'Enter border width (e.g., 2)',
                                }),
                                elBubble(ToggleControlBubble, {
                                    label: 'Allow Fullscreen',
                                    checked: props.attributes.allowFullscreen,
                                    onChange: function(newValue) {
                                        props.setAttributes({ allowFullscreen: newValue });
                                    }
                                })
                            )
                        ),
                        embedID 
                        ? elBubble('iframe', {
                                src: iframeURLBubble,
                                style: {
                                    border: 0,
                                    position: 'absolute',
                                    inset: 0,
                                    width: '100%',
                                    height: '100%',
                                },
                                allowFullscreen: props.attributes.allowFullscreen ? 'allowfullscreen' : '',
                            })
                        : elBubble('p', {}, 'Please enter a valid Embed ID or URL in the settings panel.')
                    );
                },
                save: function(props) {
                    var embedID = props.attributes.embedID;
                    var sizePreset = props.attributes.sizePreset;
                    var sizeStylesBubble = getSizeStylesBubble(sizePreset, props.attributes.width, props.attributes.height);

                    var iframeURLBubble = embedID 
                        ? `https://app.usebubbles.com/embed/${embedID}`
                        : '';
                    var styleBubble = {
                        margin: props.attributes.margin + 'px auto',
                        border: props.attributes.border + 'px solid black',
                        position: 'relative',
                        width: sizeStylesBubble.width,
                        height: 0,
                        paddingBottom: sizeStylesBubble.paddingBottom,
                        overflow: 'hidden',
                    };

                    return embedID 
                        ? elBubble('div', { className: 'bubble-integrator-block', style: styleBubble },
                            elBubble('iframe', {
                                src: iframeURLBubble,
                                style: {
                                    border: 0,
                                    position: 'absolute',
                                    inset: 0,
                                    width: '100%',
                                    height: '100%',
                                },
                                allowFullscreen: props.attributes.allowFullscreen ? 'allowfullscreen' : '',
                            })
                          )
                        : null;
                }
            });

            function getSizeStylesBubble(preset, width, height) {
                switch (preset) {
                    case 'small':
                        return { width: '40%', paddingBottom: '30%' };
                    case 'medium':
                        return { width: '60%', paddingBottom: '33.75%' };
                    case 'large':
                        return { width: '80%', paddingBottom: '38.1%' };
                    case 'manual':
                        return { width: width + '%', paddingBottom: height + '%' };
                    default:
                        return { width: '60%', paddingBottom: '33.75%' };
                }
            }
        })(window.wp);
    });
    </script>
    <?php
}

add_action('admin_footer', 'bubble_integrator_block_init');

function bubble_integrator_enqueue_block_styles() {
    wp_enqueue_style(
        'bubble-integrator-block-styles',
        plugins_url('css/bubble-integrator.css', __FILE__), 
        array(), 
        '1.0.0' 
    );
}

add_action('wp_enqueue_scripts', 'bubble_integrator_enqueue_block_styles');
