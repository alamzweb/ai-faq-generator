import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ai-faq-generator/faq-block', {
    edit: function ( { attributes, setAttributes } ) {
        const blockProps = useBlockProps({
            className: 'aifg-gutenberg-editor-canvas-card'
        });

        // Pull the active dataset options populated dynamically from our core PHP hook
        const faqSetsOptions = window.aifg_block_vars || [
            { label: '-- No FAQ Sets Found --', value: '' }
        ];

        return (
            <div { ...blockProps }>
                <div className="aifg-block-inline-editor-header">
                    <span className="dashicons dashicons-welcome-learn-more"></span>
                    <h3>{ __( 'AI FAQ Generator — Block Configuration', 'ai-faq-generator' ) }</h3>
                </div>
                
                <div className="aifg-block-inline-editor-body">
                    <div className="aifg-control-row">
                        <label>{ __( 'Select FAQ Set Target:', 'ai-faq-generator' ) }</label>
                        <SelectControl
                            value={ attributes.selectedFaqSet }
                            options={ faqSetsOptions }
                            onChange={ ( value ) => setAttributes( { selectedFaqSet: value } ) }
                        />
                    </div>

                    <div className="aifg-control-row">
                        <label>{ __( 'Choose Design Style Theme Layout:', 'ai-faq-generator' ) }</label>
                        <SelectControl
                            value={ attributes.layoutStyle }
                            options={ [
                                { label: __( 'Modern Accordion Layout', 'ai-faq-generator' ), value: 'modern' },
                                { label: __( 'Minimalist Line Frame', 'ai-faq-generator' ), value: 'minimal' },
                                { label: __( 'Multi Column Card Grid', 'ai-faq-generator' ), value: 'cards' },
                                { label: __( 'Dark Mode Variant Grid', 'ai-faq-generator' ), value: 'dark' }
                            ] }
                            onChange={ ( value ) => setAttributes( { layoutStyle: value } ) }
                        />
                    </div>
                </div>

                <div className="aifg-block-inline-editor-footer">
                    <p>
                        { attributes.selectedFaqSet 
                            ? `⚡ Live Target Mapping Connection Status: Live Link Active (ID: #${attributes.selectedFaqSet})`
                            : '⚠️ Setup Required: Please assign an active FAQ Profile target using the dropdown selection fields above.'
                        }
                    </p>
                </div>
            </div>
        );
    },
    save: function () {
        return null; // Keep dynamic delivery active via the register_block_type fallback render callback
    }
});