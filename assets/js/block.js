(function(blocks, element, blockEditor, components) {
    var el = element.createElement;
    var SelectControl = components.SelectControl;
    var InspectorControls = blockEditor.InspectorControls;

    blocks.registerBlockType('ai-faq-generator/faq-block', {
        title: 'AI FAQ Generator',
        icon: 'welcome-learn-more',
        category: 'widgets',
        attributes: {
            selectedFaqSet: { type: 'string', default: '' },
            layoutStyle: { type: 'string', default: 'modern' }
        },
        edit: function(props) {
            var attributes = props.attributes;
            
            // Re-map internal array datasets cleanly context variables localized mappings.
            var optionsList = window.aifg_block_vars || [];

            function updateFaqSelection(newValue) {
                props.setAttributes({ selectedFaqSet: newValue });
            }

            function updateLayoutSelection(newValue) {
                props.setAttributes({ layoutStyle: newValue });
            }

            return [
                el(InspectorControls, { key: 'inspector' },
                    el(components.PanelBody, { title: 'Layout Setup Mapping Settings', initialOpen: true },
                        el(SelectControl, {
                            label: 'Select Target FAQ Profile Set',
                            value: attributes.selectedFaqSet,
                            options: optionsList,
                            onChange: updateFaqSelection
                        }),
                        el(SelectControl, {
                            label: 'Frontend Layout Render Style Theme',
                            value: attributes.layoutStyle,
                            options: [
                                { label: 'Modern Accordion', value: 'modern' },
                                { label: 'Minimalist Line Frame', value: 'minimal' },
                                { label: 'Multi Column Card Grid', value: 'cards' },
                                { label: 'Dark Mode Variant Grid', value: 'dark' }
                            ],
                            onChange: updateLayoutSelection
                        })
                    )
                ),
                el('div', { key: 'preview', className: 'aifg-block-editor-preview-box' },
                    el('h4', null, 'AI FAQ Generator Integration Layer Node Block'),
                    el('p', null, 'Active Configured Mapping Parameter Profile Reference: ID #' + (attributes.selectedFaqSet || 'None Selected') + ' Layout: ' + attributes.layoutStyle)
                )
            ];
        },
        save: function() {
            // Server side render execution layer callback fallback loops configuration patterns.
            return null;
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components);