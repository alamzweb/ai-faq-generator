import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ai-faq-generator/faq-block', {
    edit: function ( { attributes, setAttributes } ) {
        const blockProps = useBlockProps({
            className: 'aifg-gutenberg-editor-block-wrapper'
        });

        // Pull the global variable populated dynamically by our PHP loader class
        const faqSetsOptions = window.aifg_block_vars || [
            { label: '-- No FAQ Sets Found --', value: '' }
        ];

        return (
            <>
                <InspectorControls>
                    <PanelBody title={ __( 'FAQ Layout Configuration', 'ai-faq-generator' ) } initialOpen={ true }>
                        <SelectControl
                            label={ __( 'Select FAQ Profile Set', 'ai-faq-generator' ) }
                            value={ attributes.selectedFaqSet }
                            options={ faqSetsOptions }
                            onChange={ ( value ) => setAttributes( { selectedFaqSet: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Frontend Theme Style Layout', 'ai-faq-generator' ) }
                            value={ attributes.layoutStyle }
                            options={ [
                                { label: __( 'Modern Accordion', 'ai-faq-generator' ), value: 'modern' },
                                { label: __( 'Minimalist Line Frame', 'ai-faq-generator' ), value: 'minimal' },
                                { label: __( 'Multi Column Card Grid', 'ai-faq-generator' ), value: 'cards' },
                                { label: __( 'Dark Mode Variant Grid', 'ai-faq-generator' ), value: 'dark' }
                            ] }
                            onChange={ ( value ) => setAttributes( { layoutStyle: value } ) }
                        />
                    </PanelBody>
                </InspectorControls>

                <div { ...blockProps }>
                    <div className="aifg-editor-preview-inner-canvas">
                        <span className="aifg-editor-block-icon dashicons dashicons-welcome-learn-more"></span>
                        <div className="aifg-editor-block-info-text">
                            <h4>{ __( 'AI FAQ Generator Integration Layer Node Block', 'ai-faq-generator' ) }</h4>
                            <p>
                                { attributes.selectedFaqSet 
                                    ? `${ __( 'Active Reference Set ID:', 'ai-faq-generator' ) } #${ attributes.selectedFaqSet } [Theme: ${ attributes.layoutStyle }]`
                                    : __( 'Please configure your FAQ Target Profile mapping in the sidebar options.', 'ai-faq-generator' ) 
                                }
                            </p>
                        </div>
                    </div>
                </div>
            </>
        );
    },
    save: function () {
        // Return null to delegate rendering output directly to our PHP dynamic callback method
        return null;
    }
});