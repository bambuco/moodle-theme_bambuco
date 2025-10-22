// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * TODO describe module controls
 *
 * @module     theme_bambuco/controls
 * @copyright  2025 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import Modal from 'core/modal';
import ModalEvents from 'core/modal_events';

/**
 * Initialize the component.
 *
 */
export const init = async() => {

    // Open resources into modal.
    $('.bbco-openinmodal').each(function() {
        var $this = $(this);

        if ($this.parents('[data-fieldtype="editor"]') && $this.parents('[data-fieldtype="editor"]').length > 0) {
            return;
        }

        $this.find('a').each( function() {
            this.removeAttribute('onclick');
        });

        $this.find('a').on('click', function(event) {
            event.preventDefault();

            var $link = $(this);

            var dialogue = $link.data('dialogue');

            if (!dialogue) {

                var w = $this.attr('data-property-width');
                var h = $this.attr('data-property-height');

                var url = $link.attr('href') + '&inpopup=true';
                var $iframe = $('<iframe class="bbco-openinmodal-container"></iframe>');
                $iframe.attr('src', url);
                $iframe.on('load', function() {
                    $iframe.contents().find('a:not([target])').attr('target', '_top');
                });

                var el = $.fn['hide'];
                $.fn['hide'] = function () {
                    this.trigger('hide');
                    return el.apply(this, arguments);
                };

                var $floatwindow = $('<div></div>');

                $floatwindow.append($iframe);

                var properties = {
                    width: '95vw',
                    height: '95vh',
                };

                if (w) {
                    if (w.indexOf('%') >= 0) {
                        var window_w = $(window).width();
                        var tmp_w = Number(w.replace('%', ''));
                        if (!isNaN(tmp_w) && tmp_w > 0) {
                            w = tmp_w * window_w / 100;
                        }
                    }

                    if (!isNaN(w)) {
                        w += 'px';
                    }

                    properties.width = w;
                }

                if (h) {
                    if (h.indexOf('%') >= 0) {
                        var window_h = $(window).height();
                        var tmp_h = Number(h.replace('%', ''));
                        if (!isNaN(tmp_h) && tmp_h > 0) {
                            h = tmp_h * window_h / 100;
                        }
                    }

                    if (!isNaN(h)) {
                        h += 'px';
                    }

                    properties.height = h;
                }

                Modal.create({
                    body: $iframe,
                    title: $link.attr('title') || $link.text(),
                })
                .then(function(modal) {

                    // When the dialog is closed, pause video and audio.
                    modal.getRoot().on(ModalEvents.hidden, function() {
                        $iframe.contents().find('video, audio').each(function(){
                            this.pause();
                        });
                    });

                    modal.getRoot().find('> .modal-dialog').attr('style', 'width: ' + properties.width +
                                                                    '; height: ' + properties.height + ';')
                                                                    .addClass('bbco-modal-dialog');
                    modal.show();
                    $link.data('dialogue', modal);

                    return modal;
                });

            } else {
                dialogue.show();
            }

        });

    });
};
