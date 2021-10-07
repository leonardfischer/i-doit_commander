(function() {
    idoit.Translate.set('lfischer_commander.commander', '[{isys type="lang" ident="LC__MODULE__LFISCHER_COMMANDER__COMMANDER"}]');
    idoit.Translate.set('lfischer_commander.commander-for-example', '[{isys type="lang" ident="LC__MODULE__LFISCHER_COMMANDER__COMMANDER_FOR_EXAMPLE"}]');
    idoit.Translate.set('lfischer_commander.commander-examples', '[{isys type="lang" ident="LC__MODULE__LFISCHER_COMMANDER__COMMANDER_EXAMPLES"}]'.replace(/\n/g, '<br />'));
    
    idoit.Require
        .addModule('lfischer.Commander', window.www_dir + 'src/classes/modules/lfischer_commander/assets/js/commander.js')
        .require('lfischer.Commander', function () {
            var lastKeyDown;
            var keyCounter = 0;
            var Commander = new window.Commander();
            
            $(document).on('keydown', function(ev) {
                var now = new Date();
                
                // If the last 'Control' tap was less than 500ms ago.
                if (ev.key === 'Control' && !Commander.active) {
                    if ((now - lastKeyDown) <= 500) {
                        keyCounter ++
                        
                        if (keyCounter === 2) {
                            // Initialize!
                            Commander.show();
                        }
                    } else {
                        keyCounter = 0;
                    }
            
                    lastKeyDown = now;
                } else {
                    keyCounter = 0;
                }
            });
        });
})();
