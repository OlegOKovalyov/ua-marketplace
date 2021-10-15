if (location.search.indexOf('page=mrkv_ua_marketplaces') !== -1) { // Only Dashboard tab
    jQuery(document).ready(function(){

        // Copy xml content as string to browser Clipboard with 'Скопіювати' button on Dashboard tab
        jQuery( '.mrkvuamp_xml_link_copy' ).on('click', function(){

            var refFile = mrkvuamp_script_vars.rozetka_xml_path; // path to rozetka xml file
            var fileContent;

            jQuery.get(refFile, function(response) {
                fileContent = response;
                xmlString = (new XMLSerializer()).serializeToString(fileContent); // convert xml Object to String
                var promise = navigator.clipboard.writeText(xmlString); // set promise to write in Clipboard
                var selval = jQuery("input[name=mrkvuamp_clipboard]").val(xmlString).select(); // write to hidden input element

                document.execCommand("copy");
                // Sweetalert2 modal
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Скопійовано!',
                    showConfirmButton: false,
                    timer: 1000
                });
            })
        });

        // Remove checked checkbox fields after its activation on Dashboard tab
        var marketplaces_checked_count = 0; // how many marketplaces was clicked (selected)
        var checkboxes_qty = jQuery('#mrkvuamp-dashboard-form .mrkvuamp_chekbox').length; // total marketplaces quantity

        jQuery('input[type=checkbox].mrkvuamp_chekbox:checked').each(function () { // all checkboxes loop
            var status = (this.checked ? jQuery(this).val() : "");
            var id = jQuery(this).attr("id");
            // create css-class e.g. 'rozetka_activation_class' name from checkbox name id 'mrkvuamp_rozetka_activation'
            var prefix = 'mrkvuamp_';
            var checkboxBlock = id.slice(prefix.length) + '_class';

            if (status) { // not show clicked (selected) marketplace
                ++marketplaces_checked_count;
                jQuery('.' + checkboxBlock).css("display", "none");
            }
        });

        // Remove Dashboard subtitle and 'Зберегти зміни' button when all checkboxes checked
        if (marketplaces_checked_count == checkboxes_qty) {
            jQuery('.dashboard-subtitle').css("display", "none");
            jQuery('#mrkvuamp_dashboard_submit').css("display", "none");
        }

        // Remove Dashboard notice with 'Dismiss' button click
        jQuery('#mrkvuamp_dashboard_dismiss').on("click", function () {
            jQuery('#mrkvuamp_dashboard_notice').fadeOut(500);
            document.cookie = "mrkvuamp_dashboard_notice=dismissed";
        })

    }); // ready()

} // Dashboard tab
