var to_install = [],
    errors = [],
    et_globals = {},
    ET_Import_globals_functions;

;(function ($) {
    ET_Import_globals_functions = {
        get_part_type: function (part) {
            switch (part) {
                case 'options':
                    return 'options';
                case 'menu':
                    return 'menu';
                case 'home_page':
                    return 'home_page';
                case 'slider':
                    return 'slider';
                case 'widgets':
                    return 'widgets';
                case 'fonts':
                    return 'fonts';
                case 'variation_taxonomy':
                    return 'variation_taxonomy';
                case 'variations_trems':
                    return 'variations_trems';
                case 'variation_products':
                    return 'variation_products';
                case 'et_multiple_headers':
                    return 'et_multiple_headers';
                case 'et_multiple_conditions':
                    return 'et_multiple_conditions';
                case 'et_multiple_single_product':
                    return 'et_multiple_single_product';
                case 'et_multiple_single_product_conditions':
                    return 'et_multiple_single_product_conditions';
                case 'elementor_globals':
                    return 'elementor_globals';
                case 'version_info':
                    return 'version_info';
                case 'default_woocommerce_pages':
                    return 'default_woocommerce_pages';
                default:
                    return 'xml';
            }
        }
    }
})(jQuery);

jQuery(document).ready(function ($) {

    /* Promo banner in admin panel */

    jQuery('.et-extra-message .close-btn').on("click", function () {

        var confirmIt = confirm('Are you sure?');

        if (!confirmIt) return;

        var widgetBlock = jQuery(this).parent();

        var data = {
            'action': 'et_close_extra_notice',
            'close': widgetBlock.attr('data-etag')
        };

        widgetBlock.hide();

        jQuery.ajax({
            url: ajaxurl,
            data: data,
            success: function (response) {
                widgetBlock.remove();
            },
            error: function (data) {
                alert('Error while deleting');
                widgetBlock.show();
            }
        });
    });

    /* UNLIMITED SIDEBARS */

    var delSidebar = '<div class="delete-sidebar">delete</div>';

    jQuery('.sidebar-etheme_custom_sidebar').find('.handlediv').before(delSidebar);

    jQuery('.delete-sidebar').on("click", function () {

        var confirmIt = confirm('Are you sure?');

        if (!confirmIt) return;

        var widgetBlock = jQuery(this).closest('.sidebar-etheme_custom_sidebar');

        var data = {
            'action': 'etheme_delete_sidebar',
            'etheme_sidebar_name': jQuery(this).parent().find('h2').text()
        };

        widgetBlock.hide();

        jQuery.ajax({
            url: ajaxurl,
            data: data,
            success: function (response) {
                widgetBlock.remove();
            },
            error: function (data) {
                alert('Error while deleting sidebar');
                widgetBlock.show();
            }
        });
    });


    /* end sidebars */

    // ! New wp.media for widgets
    jQuery(document).ready(function ($) {
        $(document).on("click", ".etheme_upload-image", function (e) {
            e.preventDefault();
            var $button = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or upload image',
                library: { // remove these to show all
                    type: 'image' // specific mime
                },
                button: {
                    text: 'Select'
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                // We set multiple to false so only get one image from the uploader
                var attachment = file_frame.state().get('selection').first().toJSON();
                var parent = $button.parents('.media-widget-control');
                var thumb = '<img class="attachment-thumb" src="' + attachment.url + '">';

                parent.find('.placeholder.etheme_upload-image').addClass('hidden');
                parent.find('.attachment-thumb').remove();
                parent.find('.etheme_media-image').prepend(thumb);
                parent.find('input.widefat').attr('value', attachment.url);
                parent.find('input.widefat').change();
            });

            // Finally, open the modal
            file_frame.open();
        });
    });

    $(document).ready(function () {
        setTimeout(function () {
            $('.et-tab-label.vc_tta-section-append').removeClass('vc_tta-section-append').addClass('et-tab-append');
        }, 1000);
    });

    $(document).on('click', '#et_tabs', function (event) {
        setTimeout(function () {
            $('.et-tab-label.vc_tta-section-append').removeClass('vc_tta-section-append').addClass('et-tab-append');
        }, 1000);
    });

    $(document).on('click', '.et-tab-label.et-tab-append', function (event) {
        if (typeof vc == 'undefined') return;

        var newTabTitle = 'Tab',
            params,
            shortcode,
            modelId = $(this).parents('.wpb_et_tabs').data('model-id'),
            prepend = false;

        params = {
            shortcode: "et_tab",
            params: {
                title: newTabTitle
            },
            parent_id: modelId,
            order: _.isBoolean(prepend) && prepend ? vc.add_element_block_view.getFirstPositionIndex() : vc.shortcodes.getNextOrder(),
            prepend: prepend
        }

        shortcode = vc.shortcodes.create(params);

    });

    $('.et-button:not(.no-loader)').on('click', function () {
        $(this).addClass('loading');
    });

    // **********************************************************************//
    // ! Theme deactivating action
    // **********************************************************************//

    $('.et_theme-deactivator').on('click', function (event) {
        event.preventDefault();

        var confirmIt = confirm('Are you sure that you want to deactivate theme on this domain?');
        if (!confirmIt) return;

        var data = {
            'action': 'etheme_deactivate_theme',
        };

        var redirect = window.location.href;

        jQuery.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: ajaxurl,
            data: data,
            success: function (data) {
                var out = ''
                if (data == 'deleted') {
                    redirect = redirect.replace('_options&tab=1', 'xstore_activation_page');
                    redirect = redirect.replace('_options', 'xstore_activation_page');
                    window.location.href = redirect;
                } else {
                    $.each(data, function (e, t) {
                        $('#redux-header').prepend('<span class="et_deactivate-error">' + t + '</span>');
                    });
                }
            },
            error: function (data) {
                alert('Error while deactivating');
            },
        });
    });

    // ! Set major-update message
    if ($('.et_major-version').length > 0 && $('body').is('.themes-php')) {
        $.each($('.themes .theme'), function (i, t) {
            if ($(this).data('slug') == 'xstore') {
                $(this).find('.update-message').append('<p class="et_major-update">' + $('.et_major-version').data('message') + '</p>');
            }
        });

        // ! show it for multisites
        $.each($('.plugin-update-tr.active'), function (i, t) {
            if ($(this).is('#xstore-update')) {
                $(this).find('.update-message').append('<p class="et_major-update">' + $('.et_major-version').data('message') + '</p>');
            }
        });

    }
    ;

    if (et_variation_gallery_admin.menu_enabled) {
        // Functions for updating et_content in menu item

        function et_update_item() {

            var items = $("ul#menu-to-edit li.menu-item");
            // Go through all items and display link & thumb
            for (var i = 0; i < items.length; i++) {
                var id = $(items[i]).children("#nmi_item_id").val();

                var sibling = $("#edit-menu-item-attr-title-" + id).parent().parent();
                var image_div = $("li#menu-item-" + id + " .nmi-current-image");
                var link_div = $("li#menu-item-" + id + " .nmi-upload-link");
                var other_fields = $("li#menu-item-" + id + " .nmi-other-fields");

                if (image_div) {
                    sibling.after(image_div);
                    image_div.show();
                }
                if (link_div) {
                    sibling.after(link_div);
                    link_div.show();
                }
                if (other_fields) {
                    sibling.after(other_fields);
                    other_fields.show();
                }

            }

            // Save item ID on click on a link
            $(".nmi-upload-link").on("click", function () {
                window.clicked_item_id = $(this).parent().parent().children("#nmi_item_id").val();
            });

            // Display alert when not added as featured
            window.send_to_editor = function (html) {
                alert(nmi_vars.alert);
                tb_remove();
            };
        }

        function ajax_update_item_content() {
            $.ajax({
                url: window.location.href,
                success: function () {
                    if ($('.add-to-menu .spinner').hasClass('is-active')) {
                        ajax_update_item_content();
                    } else {
                        et_update_item();
                    }
                },
            });
            $('.et_item-popup').hide();
        };

        $('.submit-add-to-menu').on("click", function () {

            ajax_update_item_content();

        });

        // end et_content items

        var menu_id = $('#menu').val();

        // Visibility option
        $(document).on('change', '.field-item_visibility select', function () {
            var item = $(this).closest('.menu-item');
            var id = $(item).find('.menu-item-data-db-id').val();
            var el_vis = $(item).find('.field-item_visibility select').val();
            changed_settings = true;

            function et_refresh_item_visibility(id, el_vis) {
                if ($('ul#menu-to-edit').find('input.menu-item-data-parent-id[value="' + id + '"]').length > 0) {
                    var child = $('ul#menu-to-edit').find('input.menu-item-data-parent-id[value="' + id + '"]').closest('.menu-item');
                    var select = child.find('.field-item_visibility select');
                    var c_vis = select.val();
                    if (c_vis != el_vis) {
                        select.val(el_vis).change();
                        var id = child.find('.menu-item-data-db-id').val();
                        et_refresh_item_visibility(id, el_vis);
                    }
                }
            }

            et_refresh_item_visibility(id, el_vis);
        });

        // Open options

        $(document).on('click', '.item-type', function () {
            var parent = $(this).closest('.menu-item');
            parent.prepend('<div class="popup-back"></div>');
            var menu_setgs = $(parent).find('.menu-item-settings');
            var children = $(parent).find('.et_item-popup');
            $(children).addClass('popup-opened');
            $('body').addClass('et_modal-opened');
            if ($(parent).hasClass('menu-item-edit-inactive')) {
                $(parent).removeClass('menu-item-edit-inactive').addClass('menu-item-edit-active');
            }
            $(menu_setgs).css('display', 'block');
            $(children).show();
        });

        // Single item
        $(document).on('click', '.et-saveItem, .popup-back', function () {
            if ($('body').hasClass('et_modal-opened')) {

                var el = $(this).closest('.menu-item');
                var children = el.find('.et_item-popup');

                if ($(this).hasClass('et-close-modal')) {
                    if ($(children).hasClass('popup-opened')) {
                        $(children).removeClass('popup-opened').hide();
                        $('body').removeClass('et_modal-opened');
                        el.find('.popup-back').remove();
                    }
                    return;
                }

                $(children).addClass('et-saving');

                var db_id = anchor = design = dis_titles = column_width = column_height = columns = icon_type =
                    icon = item_label = background_repeat = background_position = background_position = use_img = open_by_click = sublist_width = '';

                db_id = el.find('.menu-item-data-db-id').val();


                anchor = el.find('.field-anchor input').val();
                design = el.find('.field-design select option:selected').val();
                design2 = el.find('.field-design2 select option:selected').val();
                dis_titles = el.find('.field-disable_titles input:checked').val() ? 1 : 0;
                column_width = el.find('.field-column_width input').val();
                column_height = el.find('.field-column_height input').val();
                sublist_width = el.find('.field-sublist_width input').val();
                columns = el.find('.field-columns select option:selected').val();
                icon_type = el.find('.field-icon_type select option:selected').val();
                icon = el.find('.field-icon input').val();
                item_label = el.find('.field-label select option:selected').val();
                background_repeat = el.find('.field-background_repeat select option:selected').val();
                background_position = el.find('.field-background_position select option:selected').val();
                widget_area = (el.hasClass('menu-item-depth-1') || el.hasClass('menu-item-depth-2')) ? el.find('.field-widget_area select option:selected').val() : '';
                static_block = el.find('.field-static_block select option:selected').val();
                use_img = el.find('.field-use_img select option:selected').val();
                // open_by_click = el.find('.field-open_by_click input:checked').val() ? 1 : 0;
                // visibility = el.find('.field-item_visibility select option:selected').val();

                item_menu = {
                    db_id: db_id,
                    anchor: anchor,
                    design: design,
                    design2: design2,
                    column_width: column_width,
                    column_height: column_height,
                    columns: columns,
                    icon_type: icon_type,
                    icon: icon,
                    item_label: item_label,
                    background_repeat: background_repeat,
                    background_position: background_position,
                    widget_area: widget_area,
                    static_block: static_block,
                    use_img: use_img,
                    sublist_width: sublist_width
                };

                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        'action': 'et_update_menu_ajax',
                        's_meta': 'item',
                        'item_menu': item_menu,
                        'menu_id': menu_id,
                    },
                    success: function (data) {
                        if ($(children).hasClass('popup-opened')) {
                            $(children).removeClass('et-saving').removeClass('popup-opened').hide();
                            $('body').removeClass('et_modal-opened');
                            el.find('.popup-back').remove();
                        }
                    },
                });
            }
        });

        // Remove item
        $("a.item-delete").addClass('custom-remove-item');
        $("a.custom-remove-item").removeClass('item-delete');

        $(document).on('click', '.custom-remove-item', function (e) {
            e.preventDefault();
            button = $(this);
            delid = button.attr('id');
            var itemID = parseInt(button.attr('id').replace('delete-', ''), 10);
            button.addClass('item-delete');
            ajaxdelurl = button.attr('href');
            $.ajax({
                type: 'GET',
                url: ajaxdelurl,
                beforeSend: function (xhr) {
                    button.text('Removing...');
                },
                success: function (data) {
                    button.text('Remove');
                    $("#" + delid).trigger("click");
                }
            });
            return false;
        });
    }

    /****************************************************/
    /* Admin panel Search */
    /****************************************************/
    admin_panel_search(
        '.etheme-versions-search',
        '.etheme-search',
        '.version-preview',
        '.version-title',
        function () {
            $('.version-preview').removeClass('et-hide').removeClass('et-show');
            $('.plugin-filter[data-filter="all"]').trigger('click');
        }
    );
    admin_panel_search(
        '.etheme-plugin-search',
        '.etheme-search',
        '.et_plugin',
        '.et_plugin-name',
        function () {
            $('.et_plugin').removeClass('et-hide').removeClass('et-show');
            $('.versions-filter[data-filter="all"]').trigger('click');
        }
    );

    function admin_panel_search(input, form, selector, find_in, on_clear) {
        $(input).on('keyup', function (e) {
            var value = $(this).val();
            $(form).find('.spinner').addClass('is-active');
            $(form).find('.et-zoom').addClass('et-invisible');
            if (value.length >= 2) {
                $(selector).removeClass('et-show').addClass('et-hide');
                $.each($(selector), function () {
                    var text = $(this).find(find_in).text();
                    if (text.toLowerCase().includes(value.toLowerCase())) {
                        $(this).removeClass('et-hide').addClass('et-show');
                    }
                });
            } else {
                on_clear();
            }
            setTimeout(function () {
                $(form).find('.spinner').removeClass('is-active');
                $(form).find('.et-zoom').removeClass('et-invisible');
            }, 500);

        });
    }

    /****************************************************/
    /* plugin filter  */
    /****************************************************/
    $('.plugin-filter').on('click', function (e) {
        var plugins_filter = $(this).attr('data-filter');

        $('.plugin-filter').removeClass('active');
        $(this).addClass('active');
        $.each($('.et_plugin'), function () {
            $(this).attr('data-active-filter', plugins_filter);
            if ($(this).attr('data-filter').indexOf(plugins_filter) != -1) {
                $(this).removeClass('et-hide').removeClass('et-show');
            } else {
                $(this).removeClass('et-show').addClass('et-hide');
            }
        });
        $(this).parent().removeClass('active');
    });


    /****************************************************/
    /* versions filter  */
    /****************************************************/
    $('.versions-filter').on('click', function (e) {
        var versions_filter = $(this).attr('data-filter');
        var engine_filter = $('.engine-filter.active').attr('data-filter');

        $('.versions-filter').removeClass('active');
        $(this).addClass('active');
        $.each($('.version-preview'), function () {
            $(this).attr('data-active-filter', versions_filter + ' ' + engine_filter);
            if ($(this).attr('data-filter').indexOf(versions_filter) != -1 && $(this).attr('data-filter').indexOf(engine_filter) != -1) {
                $(this).removeClass('et-hide').removeClass('et-show');
            } else {
                $(this).removeClass('et-show').addClass('et-hide');
            }
        });
        $(this).parent().removeClass('active');
    });

    $('.et-filter-toggle').on('click', function (e) {
        $(this).parent().find('ul').toggleClass('active');
    });

    jQuery(document).ready(function ($) {
        var engine_filter = $('.et-filter.engine-filter.active').attr('data-filter');
        var versions_filter = $('.versions-filter.active').attr('data-filter');
        $.each($('.version-preview'), function () {
            $(this).attr('data-active-filter', versions_filter + ' ' + engine_filter);
            if ($(this).attr('data-filter').indexOf(engine_filter) != -1 && $(this).attr('data-filter').indexOf(versions_filter) != -1) {
                $(this).removeClass('et-hide').removeClass('et-show');
            } else {
                $(this).removeClass('et-show').addClass('et-hide');
            }
        });
    });

    /****************************************************/
    /* engine filter  */
    /****************************************************/
    $('.engine-filter').on('click', function (e) {
        if ( $(this).hasClass('active')) return;
        var engine_filter = $(this).attr('data-filter');
        var versions_filter = $('.versions-filter.active').attr('data-filter');

        $('.engine-filter').removeClass('active');
        $(this).addClass('active');
        $.each($('.version-preview'), function () {
            let button = $(this).find('.button-preview');
            if ( button.attr('data-href')) {
                let href = button.attr('href');
                button.attr('href', button.attr('data-href')).attr('data-href', href);
            }
            $(this).attr('data-active-filter', versions_filter + ' ' + engine_filter);
            if ($(this).attr('data-filter').indexOf(engine_filter) != -1 && $(this).attr('data-filter').indexOf(versions_filter) != -1) {
                $(this).removeClass('et-hide').removeClass('et-show');
            } else {
                $(this).removeClass('et-show').addClass('et-hide');
            }
        });
    });

    $(document).on('change', 'input[name="engine"]', function (e) {
        var engine = $('input[name="engine"]:checked').val();

        var plugins = {
            elementor: ['elementor'],
            composer: ['js_composer', 'wwp-vc-gmaps', 'mpc-massive']
        }

        $('.et-mb-remove').removeClass('et-mb-remove');
        $('.engine-selector').removeClass('active');
        $('.engine-selector[for="' + engine + '"]').addClass('active');
        if (engine == 'elementor') {
            $.each(plugins.composer, function (i, t) {
                $('[data-slug="' + t + '"]').parents('li.et_popup-import-plugin').addClass('et-mb-remove');
            });
            $('[name="grid-builder"], [for="grid-builder"], .grid-builder-br').addClass('et-mb-remove');
        } else {
            $.each(plugins.elementor, function (i, t) {
                $('[data-slug="' + t + '"]').parents('li.et_popup-import-plugin').addClass('et-mb-remove');
            })
            $('[name="elementor_globals"], [for="elementor_globals"], .elementor_globals-br').addClass('et-mb-remove');
        }
    });

    $('.et-tabs-filters li').on('click', function () {
        let tab = $(this).data('tab');
        $(this).parent().find('li').removeClass('active');
        $(this).addClass('active');
        $(this).parent().nextAll('.et-tabs-content').removeClass('active');
        $(this).parent().nextAll('.et-tabs-content[data-tab-content="' + tab + '"]').addClass('active');
    });

    /****************************************************/
    /* Import XML data */
    /****************************************************/

    var importSection = $('.etheme-import-section'),
        loading = false,
        additionalSection = importSection.find('.import-additional-pages'),
        pagePreview = additionalSection.find('img').first(),
        pagesSelect = additionalSection.find('select'),
        pagesPreviewBtn = additionalSection.find('.preview-page-button'),
        importPageBtn = additionalSection.find('.et-button');

    pagesSelect.on( 'change', function () {
        var url = $(this).data('url'),
            version = $(this).find(":selected").val(),
            previewUrl = $(this).find(":selected").data('preview');

        pagePreview.attr('src', url + version + '/screenshot.jpg');
        importPageBtn.data('version', version);
        pagesPreviewBtn.attr('href', previewUrl);
    }).trigger('select');

    importSection.on('click', '.button-import-version', function (e) {
        e.preventDefault();

        var version = $(this).data('version');
        var engine = $(this).data('engine');
        var required = $(this).data('required');

        importVersion(version, engine, required);
    });

    $(document).on('click', '.et_close-popup:not(.et_close-code-popup)', function (e) {
        if ( $('body').hasClass('et_step-child_theme-step') && ! confirm('Your import process will be lost if you navigate away from this page.')){
            e.preventDefault();
            return;
        }

        $('.et_panel-popup').html('').removeClass('active auto-size');
        $('body').removeClass('et_panel-popup-on');
        $('body').removeClass('et_step-child_theme-step');
    });

    var importVersion = function (version, engine, required) {
        if (loading) return false;

        loading = true;
        importSection.addClass('import-process');

        importSection.find('.import-results').remove();

        var data = {
            action: 'et_ajax_panel_popup',
            helper: 'plugins',
            engine: engine,
            version: version,
            steps: ['reset', 'child_theme', 'engine', 'requirements', 'plugins', 'versions-compare', 'install', 'processing', 'final'],
            pageid: 0
        };

        if (required){
            data.steps = ['required'];
            data.navigation = false;
            data.header = false;
        }

        var popup = $('.et_panel-popup:not(.et_panel-popup-code-error)');

        $('body').addClass('et_panel-popup-on');

        var spinner = '<div class="et-loader ">\
                <svg class="loader-circular" viewBox="25 25 50 50">\
                <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>\
                </svg>\
            </div>';

        popup.html(spinner);

        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: data,
            success: function (data) {
                popup.html('');
                popup.addClass('loading');
                popup.prepend('<span class="et_close-popup et-button-cancel et-button"><i class="et-admin-icon et-delete"></i></span>');
                popup.addClass('panel-popup-import').append(data.head);
                popup.addClass('panel-popup-import').append(data.content);
                popup.addClass('active').removeClass('loading');
                EtImportActiond(popup);
                if  (engine && engine<2){
                    $('.et-mb-remove').removeClass('et-mb-remove');
                }
                if (required){
                    EtVersionRequired(required);
                }
            },
            complete: function () {
                importSection.removeClass('import-process');
                loading = false;
            }
        });
    };

    function EtVersionRequired(required){
        if(required.theme && required.plugin){
            $('.et_demo-required-theme').remove();
            $('.et_demo-required-plugin').remove();
        } else if(required.theme && ! required.plugin ){
            $('.et_demo-required-plugin').remove();
            $('.et_demo-required-theme-plugin').remove();
        } else if(required.plugin && ! required.theme){
            $('.et_demo-required-theme').remove();
            $('.et_demo-required-theme-plugin').remove();
        }

        if(required.theme){
            $('.min-theme-version').html($('.min-theme-version').html().replace('{{{}}}', required.theme));
        } else {
            $('.min-theme-version').remove();
        }
        if(required.plugin){
            $('.min-plugin-version').html($('.min-plugin-version').html().replace('{{{}}}', required.plugin));
        } else {
            $('.min-plugin-version').remove();
        }
    };

    /**
     * Versions Compare check
     *
     * @version 1.0.1
     * @since   7.1.3
     */
    function EtVersionsCompare(popup, step, step_next, step_notice){
        if (step_notice.hasClass('et_step-versions-compare') && ! step_notice.hasClass('required')) {
            step.removeClass('active').addClass('hidden');
            step_next.addClass('active').removeClass('hidden');
            popup.find('.et_navigate-next').addClass('hidden');
            popup.find('.et_navigate-install').removeClass('hidden');
        } else {
            var version_info = $('.et-theme-version-info');
            var version_info_html = version_info.html();
            version_info_html = version_info_html.replace('{{{version}}}', version_info.attr('data-theme-min-version'));
            version_info.html(version_info_html);
            $('body').removeClass('et_step-child_theme-step');

            step.removeClass('active').addClass('hidden');
            step_notice.addClass('active').removeClass('hidden');
            popup.find('.et_navigate-next').addClass('hidden');
        }
        return true;
    }

    function EtImportActiond(popup) {
        popup.find('.et_navigate-next').on('click', function (e) {
            var step = popup.find('.et_popup-step.active');

            if (step.hasClass('et_step-requirements') && !confirm('IMPORTANT: Your system does not meet the server requirements. Are you sure you want to continue?')) {
                return;
            }

            if (step.hasClass('et_step-plugins')) {
                if (
                    !$('.selected-to-install').length &&
                    !$('.et_plugin-installed').length &&
                    !confirm('IMPORTANT: This demo requires some plugins to be installed. Are you sure you want to continue?')
                ) {
                    return;
                }

                var to_install = [];
                $.each($('.selected-to-install'), function (i, t) {
                    var slug = $(t).find('.et_popup-import-plugin-btn:not(.et_plugin-installing, .et_plugin-installed)').attr('data-slug');
                    if (slug) {
                        to_install.push(slug);
                    }
                });
                $(this).addClass('hidden');
                install_all_plugins(to_install, step);
                return;
            }

            if (step.next().hasClass('et_step-plugins') || step.next().hasClass('et_step-type') || step.next().next().hasClass('et_step-type')) {
                $('.et-mb-remove').remove();
            }
            if (step.next().hasClass('et_step-plugins')) {
                if ($(this).attr('data-text-install')) {
                    $(this).find('.text-holder').text($(this).attr('data-text-install'));
                }

                $('.et-mb-remove').remove();
                if ($('.et_popup-import-plugin').length == $('.et-mb-remove').length || !$('.et_popup-import-plugin').length) {

                    if ( EtVersionsCompare(popup, step, step.next().next().next(), step.next().next()) ){
                        return;
                    } else {
                        step.removeClass('active').addClass('hidden');
                        step.next().next().addClass('active').removeClass('hidden');
                        popup.find('.et_navigate-next').addClass('hidden');
                        popup.find('.et_navigate-install').removeClass('hidden');
                        return;
                    }
                }
            }  else {
                if ($(this).attr('data-text-install')) {
                    $(this).find('.text-holder').text($(this).attr('data-text-next'));
                }
            }

            step.removeClass('active').addClass('hidden');

            if (step.next().hasClass('et_step-versions-compare') && EtVersionsCompare(popup, step, step.next().next(), step.next()) ){
                return;
            } else {
                step.next().addClass('active').removeClass('hidden');
            }

            if (step.next().hasClass('et_step-type')) {
                popup.find('.et_navigate-install').removeClass('hidden');
            }

            if (step.hasClass('et_step-child_theme')){
                $('body').addClass('et_step-child_theme-step');
            }

            if (step.next().hasClass('et_step-child_theme') || step.next().hasClass('et_step-type')) {
                popup.find('.et_navigate-next').addClass('hidden');
            } else {
                popup.find('.et_navigate-next').removeClass('hidden');
            }
        });

        popup.find('#et_all').on('change', function (e) {
            if (!$(this).prop('checked')) {
                popup.find('.et_manual-setup').addClass('active');
                popup.find('.et_manual-setup input').prop('checked', false);
            } else {
                popup.find('.et_manual-setup').removeClass('active');
                popup.find('.et_manual-setup input').prop('checked', true);
            }

            popup.find('#pages').trigger('change');
        });

        popup.find('#pages').on('change', function (e) {
            if (!$(this).prop('checked')) {
                popup.find('.et_manual-setup-page').addClass('hidden');
                popup.find('#widgets').prop('checked', false);
                popup.find('#home_page').prop('checked', false);
            } else {
                popup.find('.et_manual-setup-page').removeClass('hidden');
                popup.find('#widgets').prop('checked', true);
                popup.find('#home_page').prop('checked', true);
            }
        });

        popup.find('.et_manual-setup input').on('change', function (e) {
            if ($('.et_manual-setup input:checked').length) {
                popup.find('.et_navigate-install').removeClass('et-button-grey et-not-allowed');
            } else {
                popup.find('.et_navigate-install').addClass('et-button-grey et-not-allowed');
            }
        });

        popup.find('.all-plugins').on('change', function (e) {
            if ( !$(this).prop('checked') ) {
                $('.install-with-all').removeClass('install-with-all');
                $('.selected-to-install').removeClass('selected-to-install');
                popup.find('input.plugin-setup').prop('checked', false);
            } else {
                $('.et_popup-import-plugin').addClass('install-with-all');
                $('.et_popup-import-plugin').addClass('selected-to-install');
                popup.find('input.plugin-setup').prop('checked', true);
            }
        });

        popup.find('input.plugin-setup').on('change', function (e) {
            if (!$(this).prop('checked')) {
                $(this).parents('.et_popup-import-plugin').removeClass('selected-to-install');
            } else {
                $(this).parents('.et_popup-import-plugin').addClass('selected-to-install');
            }
        });

        popup.find('.et_navigate-install').on('click', function (e) {

            if ($('.et_step-type [type="checkbox"]:checked').length < 1) {
                return;
            }

            popup.find('.et_close-popup').addClass('hidden');

            to_install = popup.find('.et_install-demo-form').serializeArray();

            if (popup.find('#et_all').prop('checked')) {
                to_install.shift();
            }

            popup.find('.et_progress').attr('data-step', parseInt(100 / to_install.length));
            var _engine = $('input[name="engine"]:checked').val();
            var version =  $(this).attr('data-version');

            _engine = (_engine) ? _engine : $(this).attr('data-engine-default');
            _engine = (_engine) ? _engine : 'wpb';

            var data = {
                type: 'xml',
                action: 'etheme_import_ajax',
                version: version,
                engine: _engine,
            };

            popup.find('.et_popup-step.active').removeClass('active').addClass('hidden');
            popup.find('.et_step-processing').addClass('active').removeClass('hidden');

            install_part(popup, data, 0, false, '');
        });

        popup.find('#et_create-child_theme').on('click', function (e) {
            e.preventDefault();
            var _step = popup.find('.et_popup-step.active');
            _step.addClass('ajax-processing');
            var data = {
                action: 'et_create_child_theme',
                helper: 'child-theme',
                theme_name: popup.find('[name="theme_name"]').val(),
                theme_template: popup.find('[name="theme_template"]').val(),
            };

            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: data,
                dataType: 'json',
                success: function (response) {
                    _step.removeClass('ajax-processing');
                    if (response.type == 'success') {

                        _step.find('.step-description').addClass('hidden');
                        popup.find('#et_create-child_theme-form').addClass('hidden');
                        popup.find('.et_step-child_theme .et-success').removeClass('hidden');
                        popup.find('.new-theme-title').html(response.new_theme_title);
                        popup.find('.new-theme-path').html(response.new_theme_path);
                    } else {
                        popup.find('.et_step-child_theme .et-error').removeClass('hidden');
                    }
                    popup.find('.et_navigate-next').removeClass('hidden');
                },
                error: function (response) {
                    _step.removeClass('ajax-processing');
                    popup.find('.et_step-child_theme .et-error').removeClass('hidden');
                    popup.find('.et_navigate-next').removeClass('hidden');
                },
                complete: function (response) {
                    _step.removeClass('ajax-processing');
                    popup.find('.et_navigate-next').removeClass('hidden');
                }
            });
        });

        popup.find('#et_skip-child_theme').on('click', function (e) {
            e.preventDefault();
            popup.find('.et_navigate-next').trigger('click');
        });
    }

    /**
     * install all plugins
     *
     * @version 1.0.1
     * @since   6.4.4+
     * @log 1.0.1
     * ADDED: ET_CORE_THEME_MIN_VERSION
     */
    function install_all_plugins(to_install, step) {
        var popup = '';
        if ($(this).hasClass('et_core-plugin')) {
            popup = $('.etheme-registration');
        } else {
            popup = $('.et_panel-popup.panel-popup-import.active, .et_panel-popup.et_popup-import-single-page');
        }

        if (!popup.find('.et_close-popup').hasClass('hidden')) {
            popup.find('.et_close-popup').addClass('hidden');
        }

        if (!to_install.length) {
            popup.find('.et_close-popup').removeClass('hidden');
            popup.find('.et_popup-import-plugins').removeClass('ajax-processing');

            if ( EtVersionsCompare(popup, step, step.next().next(), step.next()) ){
                return;
            } else {
                step.removeClass('active').addClass('hidden');
                if (step.next().hasClass('et_step-type')) {
                    popup.find('.et_navigate-next').addClass('hidden');
                    popup.find('.et_navigate-install').removeClass('hidden');
                }
            }

            return;
        }

        var $el = $('[data-slug="' + to_install[0] + '"]'),
            li = $el.parents('li'),
            data = {
                action: 'envato_setup_plugins',
                helper:'plugins',
                slug: to_install[0],
                wpnonce: popup.find('.et_plugin-nonce').attr('data-plugin-nonce'),
            },
            current_item_hash = '';

        popup.find('.et_popup-import-plugins').addClass('ajax-processing');
        $el.addClass('et_plugin-installing');
        li.addClass('processing');

        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: data,
            success: function (response) {
                if (response.hash != current_item_hash) {
                    $.ajax({
                        method: "POST",
                        url: response.url,
                        data: response,
                        success: function (response) {
                            if ($el.hasClass('et_second-try') || to_install[0] == 'woocommerce') {
                                li.addClass('activated').removeClass('processing').removeClass('selected-to-install');
                                $el.removeClass('et_plugin-installing').addClass('et_plugin-installed green-color').text('Activated').attr('style', null);
                                $el.parents('.et_popup-import-plugin').find('.dashicons').addClass('dashicons-yes-alt hidden green-color').removeClass('dashicons-warning orange-color red-color');
                            }
                            if ($el.hasClass('et_core-plugin')) {
                                $('.etheme-page-nav .mtips').removeClass('inactive mtips');
                                window.location = $('.etheme-page-nav .et-nav-portfolio').attr('href');
                                $el.css('pointer-events', 'none');
                                $('.mt-mes').remove();
                            }
                        },
                        error: function () {
                            $el.removeClass('et_plugin-installing').addClass('et_plugin-installed red-color').text('Failed').attr('style', null);
                            $el.parents('.et_popup-import-plugin').find('.dashicons').addClass('red-color').removeClass('orange-color');
                        },
                        complete: function () {
                            if (to_install[0] == 'woocommerce' && !$el.hasClass('et_second-try') && $el.attr('data-type') == 'install') {
                                install_all_plugins(to_install, step);
                                $el.removeClass('et_plugin-installed').addClass('et_second-try');
                            } else if (!$el.hasClass('et_second-try') && to_install[0] != 'woocommerce') {
                                install_all_plugins(to_install, step);
                                $el.removeClass('et_plugin-installed').addClass('et_second-try');
                            } else if (to_install.length) {
                                to_install.shift();
                                install_all_plugins(to_install, step);
                            } else {

                                if ( EtVersionsCompare(popup, step, step.next().next(), step.next()) ){
                                    return;
                                } else {
                                    step.removeClass('active').addClass('hidden');
                                    step.next().addClass('active').removeClass('hidden');

                                    if (step.next().hasClass('et_step-type')) {
                                        popup.find('.et_navigate-next').addClass('hidden');
                                        popup.find('.et_navigate-install').removeClass('hidden');
                                    }
                                }
                            }
                        }
                    });
                    // ET_CORE_THEME_MIN_VERSION
                    if(response.slug == 'et-core-plugin'){
                        if (! response.ET_VERSION_COMPARE){
                        } else {
                            $('.et_step-versions-compare').addClass('required');
                            var version_info = $('.et-theme-version-info');
                            var version_info_html = version_info.html();
                            version_info_html = version_info_html.replace('{{{version}}}', response.ET_CORE_THEME_MIN_VERSION);
                            version_info.html(version_info_html);
                            $('body').removeClass('et_step-child_theme-step')
                        }
                    }

                } else {
                    $el.removeClass('et_plugin-installing').addClass('et_plugin-installed installing-error red-color').text('Failed').attr('style', null);
                }
            },
            error: function (response) {
                if (!et_globals['plugins_error']) {
                    et_globals['plugins_error'] = [];
                }
                et_globals['plugins_error'].push(to_install[0]);
                if (to_install.length && et_globals['plugins_error'] && et_globals['plugins_error'].length < 2) {
                    setTimeout(function () {
                        if (to_install[0] == 'woocommerce') {
                            to_install.shift();
                        }
                        install_all_plugins(to_install, step);
                    }, 5000);
                } else {
                    $el.removeClass('et_plugin-installing').addClass('et_plugin-installed installing-error red-color').text('Failed').attr('style', null);
                    li.removeClass('processing');
                    $el.removeClass('loading');
                    popup.find('.et_popup-import-plugins').removeClass('ajax-processing');
                    alert('error while install of' + to_install[0] + 'plugin');
                    popup.find('.et_close-popup').removeClass('hidden');
                }

            },
            complete: function (response) {
            }
        });
    }


    /**
     * After theme activate
     *
     * @version 1.0.0
     * @since   6.2.2
     */
    after_activate();

    function after_activate() {
        if (!$('.et_popup-activeted-content').length) {
            return;
        }

        $('.et_panel-popup').html($('.et_popup-activeted-content').clone());
        $('.et_panel-popup').find('.et_popup-activeted-content').removeClass('hidden');
        $('.et_popup-activeted-content.hidden').remove();
        $('body').addClass('et_panel-popup-on');
        $('.et_panel-popup').addClass('active');
        install_plugin();
    }

    /**
     * Install base plpugin after theme activate
     *
     * @version 1.0.0
     * @since   6.2.2
     */
    function install_plugin() {
        if (!$('.et_plugin-nonce').length) {
            return;
        }
        var data = {
                action: 'envato_setup_plugins',
                slug: 'et-core-plugin',
                helper:'plugins',
                wpnonce: $(document).find('.et_plugin-nonce').attr('data-plugin-nonce'),
            },
            current_item_hash = '',
            installing = $('.et_installing-base-plugin'),
            installed = $('.et_installed-base-plugin');

        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: data,
            success: function (response) {
                if (response.hash != current_item_hash) {
                    $.ajax({
                        method: "POST",
                        url: response.url,
                        data: response,
                        success: function (response) {
                            installing.addClass('et_installed hidden');
                            installed.removeClass('hidden');
                            $('.mtips').removeClass('inactive');
                            $('.mt-mes').remove();
                        },
                        error: function () {
                            installing.addClass('et_error');
                        },
                        complete: function () {
                        }
                    });
                } else {
                    installing.addClass('et_error');
                }
            },
            error: function (response) {
                installing.addClass('et_error');
            },
            complete: function (response) {
            }
        });
    }





    /**
     * Install plpugins
     *
     * @version 1.0.1
     * @since   6.2.2
     * @log 1.0.1
     * ADDED: ET_CORE_THEME_MIN_VERSION
     */
    install_plugins();

    function install_plugins() {
        $(document).on('click', '.et_popup-import-plugin-btn:not(.et_plugin-installing, .et_plugin-installed), .et_core-plugin', function (e) {
            e.preventDefault();
            var popup = '';
            if ($(this).hasClass('et_core-plugin')) {
                popup = $('.etheme-registration');
            } else {
                popup = $('.et_panel-popup.panel-popup-import.active, .et_panel-popup.et_popup-import-single-page');
            }
            var $el = $(this),
                li = $el.parents('li'),
                data = {
                    action: 'envato_setup_plugins',
                    helper:'plugins',
                    slug: $el.attr('data-slug'),
                    wpnonce: popup.find('.et_plugin-nonce').attr('data-plugin-nonce'),
                },
                current_item_hash = '';

            popup.find('.et_popup-import-plugins').addClass('ajax-processing');
            $el.addClass('et_plugin-installing');
            li.addClass('processing');

            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: data,
                success: function (response) {
                    if (response.hash != current_item_hash) {
                        $.ajax({
                            method: "POST",
                            url: response.url,
                            data: response,
                            success: function (response) {
                                li.addClass('activated');
                                $el.removeClass('et_plugin-installing').addClass('et_plugin-installed green-color').text('Activated').attr('style', null);
                                $el.parents('.et_popup-import-plugin').find('.dashicons').addClass('dashicons-yes-alt green-color hidden').removeClass('dashicons-warning orange-color red-color');
                                if ($el.hasClass('et_core-plugin')) {
                                    $('.etheme-page-nav .mtips').removeClass('inactive mtips');
                                    window.location = $('.etheme-page-nav .et-nav-portfolio').attr('href');
                                    $el.css('pointer-events', 'none');
                                    $('.mt-mes').remove();
                                }
                            },
                            error: function () {
                                //$el.removeClass('et_plugin-installing').addClass('et_plugin-installed red-color').text('Failed').attr('style', null);
                                //$el.parents('.et_popup-import-plugin').find('.dashicons').addClass('red-color').removeClass('orange-color');
                            },
                            complete: function () {
                                li.removeClass('processing');
                                $el.removeClass('loading');
                                popup.find('.et_popup-import-plugins').removeClass('ajax-processing');
                                // second chance for plugins
                                if (!$el.hasClass('et_second-try')) {
                                    $el.removeClass('et_plugin-installed').addClass('et_second-try');
                                    $el.trigger('click');
                                } else if (popup.hasClass('et_popup-import-single-page')) {
                                    popup.find('.et_install-page-content').removeClass('et-button-grey2').addClass('et-button-green').html(popup.find('.et_install-page-content').attr('data-text'));
                                }
                            }
                        });
                        if(response.slug == 'et-core-plugin'){
                            if (! response.ET_VERSION_COMPARE){
                            } else {
                                $('.et_step-versions-compare').addClass('required');
                                var version_info = $('.et-theme-version-info');
                                var version_info_html = version_info.html();
                                version_info_html = version_info_html.replace('{{{version}}}', response.ET_CORE_THEME_MIN_VERSION);
                                version_info.html(version_info_html);
                                $('body').removeClass('et_step-child_theme-step')
                            }
                        }
                    } else {
                        $el.removeClass('et_plugin-installing').addClass('et_plugin-installed installing-error red-color').text('Failed').attr('style', null);
                    }
                },
                error: function (response) {
                    $el.removeClass('et_plugin-installing').addClass('et_plugin-installed installing-error red-color').text('Failed').attr('style', null);
                    li.removeClass('processing');
                    $el.removeClass('loading');
                    popup.find('.et_popup-import-plugins').removeClass('ajax-processing');
                },
                complete: function (response) {
                }
            });
        });
    }

    /**
     * Install version part
     *
     * @version 1.0.3
     * @since   6.2.0
     * @param {object}         popup     - doom element that hould popup
     * @param {object}         data      - object vith ajax data
     * @param {integer}        iteration - iteration number
     * @param {string/boolean} error     - error part name
     */
    function install_part(popup, data, iteration, error) {

        if (iteration == 0) {
            data.install = to_install.shift();
        } else {
            iteration = iteration - 1;
        }

        data.type = ET_Import_globals_functions.get_part_type(data.install.value);
        data.errors = errors;

        popup.find('.et_progress-notice').html('Installing ' + $('[for="' + data.install.value + '"]').html());
        popup.find('.et_navigate-install').addClass('hidden');

        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: data,
            success: function (response) {
                if (response && response.status != 'installed') {
                    errors.push(data.install.name);
                }
                if (response.status == 'installed' && ! response.verified){
                    popup_code_error();
                }
                progress_setup(popup, data.install.name);
                if (to_install.length) {
                    install_part(popup, data, 0, false);
                } else {
                    finish_setup(popup);
                }
                importSection.removeClass('import-process');
                loading = false;
            },
            error: function () {
                if (to_install.length) {
                    // quick fix to prevent variations errors
                    if (data.type != 'variation_taxonomy' && data.type != 'variations_trems' && data.type != 'variation_products') {
                        if (data.type == 'xml' && data.install.name != 'menu') {
                            if (iteration == 0 && data.install.name != error) {
                                if (data.install.name == 'media'){
                                    install_part(popup, data, 5, false);
                                } else {
                                    install_part(popup, data, 2, false);
                                }
                            } else {
                                install_part(popup, data, iteration, data.install.name);

                                if (iteration == 0 && error && data.install.name != error) {
                                    errors.push(error);

                                    progress_setup(popup, error);
                                }
                            }
                        } else {
                            errors.push(data.install.name);
                            progress_setup(popup, data.install.name);
                            install_part(popup, data, 0, data.install.name);
                        }
                    } else {
                        install_part(popup, data, 0, data.install.name);
                    }
                } else {
                    finish_setup(popup);
                }

            },
            complete: function (response) {
            }
        });
    }

    /**
     * Installation progress
     *
     * @version 1.0.1
     * @since   6.2.1
     * @param {object} popup    - doom element that hould popup
     * @param {string} progress - text for progress
     */
    function progress_setup(popup, progress) {
        var part = parseInt(popup.find('.et_progress').val()) + parseInt(popup.find('.et_progress').attr('data-step'));
        popup.find('.et_progress').attr('value', part);
        popup.find('.et_progress-notice').html('Installed ' + $('[for="' + part + '"]').html());
    }

    /**
     * Finishvsetup
     *
     * @version 1.0.2
     * @since   6.2.1
     * @param {object} popup    - doom element that hould popup
     */
    function finish_setup(popup) {
        popup.find('.et_progress-notice').html('success install');
        popup.find('.et_progress').attr('value', 100);

        if (errors.length > 0) {
            $.each(errors, function (i, t) {
                popup.find('.et_errors-list').append('<li class="et_popup-import-plugin et-message et-warning"><p class="et_error-title"><b>' + t + ' not installed :(</b></p></li>');
            });
            popup.find('.et_with-errors').removeClass('hidden');
        } else {
            popup.find('.et_all-success').removeClass('hidden');
        }

        popup.find('.et_close-popup').removeClass('hidden');
        popup.find('.et_popup-step.active').removeClass('active').addClass('hidden');
        popup.find('.et_step-final').addClass('active').removeClass('hidden');
        $('body').removeClass('et_step-child_theme-step');
    }

    // show video installation xstore
    $('.et-open-installation-video').on('click', function () {
        $('body').addClass('et_panel-popup-on');
        var popup = $('.et_panel-popup:not(.et_panel-popup-code-error)');
        popup.addClass('auto-size').html('<iframe width="888" height="500" src="https://www.youtube.com/embed/4n1re0jKorc?controls=0&showinfo=0&controls=0&rel=0&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        popup.prepend('<span class="et_close-popup et-button-cancel et-button"><i class="et-admin-icon et-delete"></i></span>');
    });


    /****************************************************/
    /* Load YouTybe videos use youtube/v3 api*/
    /****************************************************/
    $('.et-button.more-videos').on('click', function (e) {
        e.preventDefault();

        var i = 0;
        $.each($('.etheme-video.hidden'), function (e) {
            i++;
            if (i <= 2) {
                $(this).removeClass('hidden');
            }
        });
        if ($('.etheme-video.hidden').length < 1) {
            $(this).remove();
        }
    });
    $(document).on('click', '.etheme-call-popup', function (e) {
        e.preventDefault();
        // if ( ! confirm( 'Are you sure' ) ) {
        // 	return;
        // }
        var _this = $(this);

        _this.addClass('et-no-events');


        var popup = $('.et_panel-popup');

        var data = {
            action: 'et_ajax_panel_popup',
            type: 'instagram',
            personal: _this.attr('data-personal'),
            business: _this.attr('data-business'),
        };

        popup.html('');

        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: data,
            success: function (data) {

                // popup-import-head
                popup.addClass('loading');


                $('body').addClass('et_panel-popup-on');

                popup.prepend('<span class="et_close-popup et-button-cancel et-button"><i class="et-admin-icon et-delete"></i></span>');
                popup.addClass('panel-popup-import').append(data.content);

                if (_this.hasClass('etheme-instagram-auto')) {
                    popup.addClass('panel-popup-etheme-instagram-auto');
                }

                popup.addClass('active').removeClass('loading');

                $('#personal').on('change', function () {
                    $('.et-get-token').attr('href', $(this).attr('data-url'));
                });
                $('#business').on('change', function () {
                    $('.et-get-token').attr('href', $(this).attr('data-url'));
                });

                $('#old').on('change', function () {
                    $('.et-get-token').attr('href', $(this).attr('data-url'));
                });

                $('.et-help').on('click', function () {
                    $(this).parent().find('.et-help-content:not(.tooltip)').toggleClass('hidden');
                    // $('.et-get-token').attr('href', $(this).attr('data-url'));
                });

            },
            complete: function () {
                _this.removeClass('et-no-events');
                importSection.removeClass('import-process');
                loading = false;
            }
        });
    });


    $(document).on('click', '[name="xstore-purchase-code"]:not(.active)', function (e) {
        e.preventDefault();
        $('label[for="form_agree"]').addClass('red-color');
    });

    $(document).on('change', '#form_agree', function (e) {
        if ( !$(this).prop('checked') ) {
            $('[name="xstore-purchase-code"]').removeClass('active');
            $('label[for="form_agree"]').addClass('red-color');
        } else {
            $('[name="xstore-purchase-code"]').addClass('active');
            $('label[for="form_agree"]').removeClass('red-color');
        }
    });

        $('.et-counter').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: parseInt($(this).text())
            }, {
                duration: 1500,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now) + '+');}
            });
        });

    $(
        '[href="https://wpml.org/?aid=46060&affiliate_key=YI8njhBqLYnp&dr"], ' +
        '[href="https://wpkraken.io/?ref=8theme"], ' +
        '[href="https://overflowcafe.com/am/aff/go/8theme"], ' +
        '[href="http://www.bluehost.com/track/8theme"], ' +
        '[href="https://yithemes.com/product-category/plugins/?refer_id=1028760'
    ).attr('target', '_blank');

    $('[href="themes.php?page=install-required-plugins"]').remove();

    // new widget name filter
    $('#etheme_sidebar_name').on('keyup', function (e) {
        let str = $('#etheme_sidebar_name').val();
        str = str.replace(/[^a-zA-Z0-9-_]/g, "");
        $('#etheme_sidebar_name').attr('value', str);
    });

    $(document).on('click', '.et_panel-popup-on.et_step-child_theme-step #wpadminbar a', function (e){
        if (! confirm('Your import process will be lost if you navigate away from this page.')){
            e.preventDefault();
        }
    });


    $(document).on('click', '.et-preview-website', function (e){
        $('.et_close-popup').trigger('click');
    });


    $(document).on('click', '.et_support-refresh', function (e){
        e.preventDefault();
        var _this = $(this),
            parent = _this.parents('.et_support-block'),
            status = parent.find('.et_support-status'),
            temp_msg = $('.temp-msg');

        if (parent.hasClass('processing')){
            return;
        }
        parent.addClass('processing');
        $.ajax({
            method: "POST",
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: 'et_support_refresh'
            },
            success: function (data) {
                if (data.status == 'success' && data.html){
                    status.after($(data.html).find('.et_support-status'));
                    status.remove();
                }
                temp_msg.html(data.msg);
            },
            complete: function(data) {
                parent.removeClass('processing');
                setTimeout(function () {
                    temp_msg.html('');
                }, 1000);
            },
            error: function (){
                parent.removeClass('processing');
                temp_msg.html('ajax error!');
            },
        });
    });

    // $(document).on('click', '.etheme-page-title-label', function (e){
    //     popup_code_error();
    // });

    $(document).on('click', '.et_close-popup.et_close-code-popup', function (e) {
        $('.et_panel-popup.et_panel-popup-code-error').html('').removeClass('active auto-size');
    });

    function popup_code_error(){
        var popup = $('.et_panel-popup.et_panel-popup-code-error');

        $('body').addClass('et_panel-popup-on');

        var spinner = '<div class="et-loader ">\
            <svg class="loader-circular" viewBox="25 25 50 50">\
            <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>\
            </svg>\
        </div>';

        popup.html(spinner);

        $.ajax({
            method: "POST",
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: 'et_ajax_panel_popup',
                type: 'code_error',
            },
            success: function (data) {
                popup.removeClass('hidden');
                popup.html('');
                popup.addClass('loading');
                popup.prepend('<span class="et_close-popup et_close-code-popup et-button-cancel et-button"><i class="et-admin-icon et-delete"></i></span>');
                popup.append(data.content);
                popup.addClass('active').removeClass('loading');
            },
            complete: function(data) {

            },
            error: function (){
                alert('ajax error')
            },
        });
    }
});
