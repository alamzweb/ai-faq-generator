jQuery(document).ready(function($) {
    let generatedFaqsCache = [];

    // Form Event Handler Execution context operations layer loops
    $('#aifg_trigger_generation').on('click', function(e) {
        e.preventDefault();
        const postId = $('#aifg_source_post').val();
        
        if (!postId) {
            alert('Please select a target post/page location node.');
            return;
        }

        const $btn = $(this);
        $btn.text('Analyzing Content Framework...').prop('disabled', true);

        $.post(aifg_vars.ajax_url, {
            action: 'aifg_trigger_generation_action',
            nonce: aifg_vars.nonce,
            post_id: postId
        }, function(response) {
            $btn.text('Generate FAQs').prop('disabled', false);
            if (response.success && response.data.faqs.length > 0) {
                generatedFaqsCache = response.data.faqs;
                renderInteractivePreview(response.data.faqs);
            } else {
                alert('Heuristic models could not isolate enough sentence content groupings from this post.');
            }
        }).fail(function() {
            $btn.text('Generate FAQs').prop('disabled', false);
            alert('A critical internal script routing connection error occured.');
        });
    });

    function renderInteractivePreview(faqs) {
        $container = $('#preview_interactive_container');
        $container.empty().removeClass('hidden');
        $('#preview_empty_state_container').addClass('hidden');
        
        // Toggle the visual validation element.
        $('#schema_badge_indicator').text('✓ FAQ Schema Ready').removeClass('error').addClass('ready');
        $('#save_actions_container').removeClass('hidden');

        faqs.forEach(function(faq, index) {
            const itemHtml = `
                <div class="aifg-preview-item" data-index="${index}">
                    <div class="form-group">
                        <label>Question Node String</label>
                        <input type="text" class="aifg-edit-q" value="${faq.question}">
                    </div>
                    <div class="form-group">
                        <label>Answer Output Matrix</label>
                        <textarea class="aifg-edit-a" rows="3">${faq.answer}</textarea>
                    </div>
                </div>
            `;
            $container.append(itemHtml);
        });
    }

    // Save action implementation workflow processing routine logic parameters.
    $('#aifg_save_generated_set').on('click', function() {
        const title = $('#aifg_set_title').val();
        if (!title) {
            alert('Please key in an identification layout reference title.');
            return;
        }

        const structuralFaqs = [];
        $('.aifg-preview-item').each(function() {
            structuralFaqs.push({
                question: $(this).find('.aifg-edit-q').val(),
                answer: $(this).find('.aifg-edit-a').val()
            });
        });

        const $btn = $(this);
        $btn.text('Saving...').prop('disabled', true);

        $.post(aifg_vars.ajax_url, {
            action: 'aifg_save_faq_set_action',
            nonce: aifg_vars.nonce,
            title: title,
            faqs: structuralFaqs
        }, function(response) {
            $btn.text('Save Changes').prop('disabled', false);
            if (response.success) {
                alert('FAQ Set successfully stored under active post parameters listings!');
                window.location.href = 'edit.php?post_type=aifg_faq_set';
            } else {
                alert(response.data.message);
            }
        });
    });
});