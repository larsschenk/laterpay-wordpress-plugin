(function($) {$(function() {

    function laterPayDebugger() {
        var $o = {
                menuItem            : $('#wp-admin-bar-lp_js_toggleDebuggerVisibility'),
                debugger            : $('#lp_js_debugger'),
                debuggerHeader      : $('#lp_js_toggleDebuggerVisibility'),
                tabs                : $('.lp_js_debuggerTabItem'),
                content             : $('.lp_js_debuggerContent'),
                logMessage          : '.lp_js_debuggerContentTable',
                logMessageHeader    : $('.lp_js_debuggerContentTableTitle'),
                logDetails          : '.lp_js_logEntryDetails',

                hidden              : 'lp_is-hidden',
                selected            : 'lp_is-selected',

                hiddenAttr          : {
                                        'aria-hidden'   : true,
                                        'aria-visible'  : false
                                      },
                visibleAttr         : {
                                        'aria-hidden'   : false,
                                        'aria-visible'  : true
                                      }
            },

            bindEvents = function() {
                // toggle visibility of debugger pane
                $o.menuItem
                .mousedown(function() {
                    toggleDebuggerVisibility(this);
                })
                .click(function(e) {e.preventDefault();})
                .attr({'role': 'button'});

                // toggle visibility of debugger pane
                $($o.debuggerHeader, $o.debugger)
                .mousedown(function() {
                    toggleDebuggerVisibility(this);
                })
                .click(function(e) {e.preventDefault();});

                // switch tab
                $('a', $o.tabs)
                .mousedown(function() {
                    switchTab($(this));
                })
                .click(function(e) {e.preventDefault();});

                // toggle log message details
                $o.logMessageHeader
                .mousedown(function() {
                    toggleMessageDetails($(this));
                })
                .click(function(e) {e.preventDefault();});
            },

            toggleDebuggerVisibility = function() {
                if ($o.debugger.hasClass($o.hidden)) {
                    $o.debugger.removeClass($o.hidden).attr($o.visibleAttr);
                } else {
                    $o.debugger.addClass($o.hidden).attr($o.hiddenAttr);
                }
            },

            switchTab = function($trigger) {
                var currentTab  = $trigger.parent('li'),
                    tabIndex    = $o.tabs.index(currentTab);

                $o.tabs.removeClass($o.selected);
                currentTab.addClass($o.selected);

                // show / hide tab content
                $o.content.addClass($o.hidden);
                $o.content.eq(tabIndex).removeClass($o.hidden);
            },

            toggleMessageDetails = function($trigger) {
                var $messageBody = $trigger.parents($o.logMessage).find($o.logDetails);
                if ($messageBody.is(':hidden')) {
                    // hide all open log details
                    $($o.logDetails, $o.content).hide(0);
                    // show current log details
                    $messageBody.show(0);
                } else {
                    $($o.logDetails, $o.content).hide(0);
                }
            },

            initialize = function() {
                $o.debugger.addClass($o.hidden).attr($o.hiddenAttr);

                bindEvents();
            };

        initialize();
    }

    // initialize page
    laterPayDebugger();

});})(jQuery);
