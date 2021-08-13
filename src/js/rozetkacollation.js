jQuery(document).ready(function(){

    // Rozetka tab
    // AJAX  handler of '#mrkv_uamrkpl_collation_form' Form in Rozetka tab
    var protocol = jQuery(location).attr('protocol'); // http or https
    var host = jQuery(location).attr('host'); // example.com
    if (location.search.indexOf('page=mrkv_ua_marketplaces_rozetka') !== -1) { // Only Rozetka tab
        jQuery( '#mrkv_uamrkpl_collation_form' ).on('submit', async function(event) {
            // Click 'Співставити' button event save in browser local starage
            localStorage.setItem('mrkvuamp_collation_submit','Event');
            collateAndCreateXml(); // Collate and create XML

            async function collateAndCreateXml() {
                var $form = jQuery( '#mrkv_uamrkpl_collation_form' );
                var $formData = $form.serialize();
                try {
                    let a = await collateCategories($form, $formData);
                    let b = await SweetAlert2Resolve();
                    let c = await showSpinner();
                    let cc = await removeHiddenLink();
                } catch(err) {
                    let d = await SweetAlert2Reject(err);
                }
            }

            // Collate WC categories with marketplace categories
            async function collateCategories($form, $formData) {
                jQuery.ajax({
                    url: ajaxurl,
                    headers: { 'Clear-Site-Data': "cache" },
                    data: $formData,
                    cache: false,
                    ifModified: true
                });
            }

            // Sweetalert2 success modal
            async function SweetAlert2Resolve() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'XML-прайс створюється...',
                    showConfirmButton: false,
                    timer: 5000,
                    allowOutsideClick: false
                })
            }

            // Show spinner beside 'Співставити' button
            async function showSpinner() {
                var protocol = jQuery(location).attr('protocol'); // http or https
                var host = jQuery(location).attr('host'); // example.com
                // Get spinner gif-file data
                var loaderUrl = protocol + '\/\/' + host + '/wp-content/plugins/ua-marketplace/assets/images/spinner.gif';
                var image = new Image();
                image.src = loaderUrl;
                // Activate spinner and make 'Співставити' button disabled
                jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').css({"margin-right":"10px"});
                jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').addClass('mrkv_uamrkpl_collation_btn_desabled');
                jQuery('#mrkvuamp_loader').append(image);
            }

            // Sweetalert2 error modal
            async function SweetAlert2Reject(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err,
                    timer: 5000
                })
            }

        }); // on('submit', ...)

        // Remove xml link on 'Rozetka' tab when xml-file is not exists yet
        removeHiddenLink();
        async function removeHiddenLink() {
            var protocol = jQuery(location).attr('protocol'); // http or https
            var host = jQuery(location).attr('host');         // example.com
            jQuery.ajax({
                url: mrkvuamp_script_vars.rozetka_xml_path, // path to rozetka xml file,
                headers: { 'Clear-Site-Data': "cache" },
                type:'HEAD',
                cache: false,
                error: function() { //file not exists
                    jQuery('.mrkvuamp_collation_xml_link').addClass('hidden');
                },
                success: function() { //file exists
                    jQuery('.mrkvuamp_collation_xml_link').removeClass('hidden');
                }
            });
        }

        // Progress bar handling
        var siteTotalProductQty = mrkvuamp_script_vars.site_total_product_qty;
        async function progressBarXMLupload(time){
            localStorage.removeItem('mrkvuamp_collation_submit');
            var start = 0;
            var progressElement = document.getElementById('mrkvuamp-progress-xml-upload');
            var progBarHiddenMsg = document.getElementById('mrkvuamp_progbar_hidden_msg');
            var intervalId = setInterval(function(){
                if ( time < start ) {
                    clearInterval(intervalId);
                    showProgressBarFinalMsg(progBarHiddenMsg);
                } else {
                    progressElement.value = start;
                    showProcessingMsg(progBarHiddenMsg);
                }
                start++;
            }, 1000);
        }

        function showProcessingMsg(elem) {
            var progressElementMax = document.getElementById('mrkvuamp-progress-xml-upload').getAttribute('max');
            var progressElementValue = document.getElementById('mrkvuamp-progress-xml-upload').value;
            elem.innerHTML = "Не перезавантажуйте сторінку " + Number(progressElementMax-progressElementValue) + " сек." + ": processing...";
            elem.classList.add("blinking-message");
            elem.style.display = 'block';
        }

        function showProgressBarFinalMsg(elem) {
            elem.innerHTML = 'Ваш xml-прайс готовий. Перезавантажте сторінку і перейдіть за посиланням.';
            elem.classList.remove("blinking-message");
            elem.style.display = 'block';
        }

        var mrkvuamp_collation_submit_e = localStorage.getItem('mrkvuamp_collation_submit');
        if (!mrkvuamp_collation_submit_e) {
            jQuery( '#mrkvuamp-progress-xml-upload' ).fadeOut(0);
        } else {
            jQuery( '#mrkvuamp-progress-xml-upload' ).fadeIn(500);
            var progBarCoef = (siteTotalProductQty < 100) ? 1 : 2.3;
            // progressBarXMLupload(Math.round(siteTotalProductQty * 2.3));
            progressBarXMLupload(Math.round(siteTotalProductQty * progBarCoef));
        }

    } // Rozetka tab

}); // ready()
