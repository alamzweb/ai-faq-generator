document.addEventListener('DOMContentLoaded', function() {
    // Isolated Vanilla Engine structural event binding queries matching layout attributes.
    const accordionTriggers = document.querySelectorAll('.aifg-accordion-trigger');
    
    accordionTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            const parent = this.parentElement;
            const isCurrentlyActive = parent.classList.contains('active');
            
            // Close sister dynamic items inside matching wrapper containers profiles.
            const containerItems = parent.parentElement.querySelectorAll('.aifg-accordion-item');
            containerItems.forEach(function(item) {
                item.classList.remove('active');
            });
            
            if (!isCurrentlyActive) {
                parent.classList.add('active');
            }
        });
    });
});