/*
 * Reorder popup editor
 */
(function ($) { "use strict";

    /*
     * Constructor
     */
    var ReorderPopup = function (el) {
        var self = this;
        this.$el = $(el);

        this.$el.unbind().on('click', function() {
            self.popup();
        });
    }

    /*
     * Popup handler
     */
    ReorderPopup.prototype.popup = function() {
        // Popup opened event
        this.$el.one('show.oc.popup', function(e) {
            var $popup  = $(e.relatedTarget),
                $form   = $popup.find('form'),
                $list   = $form.find('[data-control="sortable"]');

            $list.sortable();

            // Attach a save handler to the "apply" button
            $('button[data-control="apply-btn"]', $popup).on('click', function() {
                var $loadingIndicator = $popup.find('.loading-indicator'),
                    $modalFooter = $popup.find('.modal-footer');

                $modalFooter.addClass('in-progress');
                $loadingIndicator.show();

                // Make ajax save request
                $form.request('onReorderSlides', {
                    complete: function(data) {
                        $popup.trigger('close.oc.popup');
                        return false;
                    },
                })

                return false;
            });
        });

        // Create the popup
        this.$el.popup({
            handler: 'onLoadPopup'
        });
    }

    /*
     * Attach object to the toolbar button
     */
    $.fn.ReorderPopup = function () {
        return new ReorderPopup(this);
    }

    $(document).on('render', function() {
        $('[data-control="reorder-popup"]').ReorderPopup();
    });

})(window.jQuery);
