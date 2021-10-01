jQuery(document).ready(function(){

    // Rozetka tab
    // AJAX  handler of '#mrkv_uamrkpl_collation_form' Form in Rozetka tab
    var protocol = jQuery(location).attr('protocol'); // http or https
    var host = jQuery(location).attr('host');         // example.com
    var siteTotalProductQty = mrkvuamp_script_vars.site_total_product_qty;
    if (location.search.indexOf('page=mrkv_ua_marketplaces_rozetka') !== -1) { // Only Rozetka tab
        jQuery( '#mrkv_uamrkpl_collation_form' ).on('submit', async function(event) {
            event.preventDefault();
            var jsStart = Number(new Date().getTime());
            localStorage.setItem('mrkvuamp_collation_submit','Event'); // Click 'Співставити' button event save in browser local starage

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
                // Get Local Storage variable for show/hide progress bar
                var mrkvuamp_collation_submit_e = localStorage.getItem('mrkvuamp_collation_submit');
                if (!mrkvuamp_collation_submit_e) {
                    jQuery( '.mrkvuamp_progress_bar' ).fadeOut(0); // Hide progress bar
                } else {
                    jQuery( '.mrkvuamp_progress_bar' ).fadeIn(500); // Show progress bar
                    var progBarCoef = (siteTotalProductQty < 100) ? 0.9 : 2;
                    progressBarXMLupload(Math.round(siteTotalProductQty * progBarCoef));
                }

                // Fire AJAX request to make collation categories and create xml
                var ajaxData = {
                    $formData,
                    action : 'mrkvuamp_collation_action',
                    nonce : 'mrkv_uamrkpl_collation_form_nonce'
                };
                var ajaxCollationRequest = jQuery.ajax({
                    url: ajaxurl,
                    headers: { 'Clear-Site-Data': "cache", 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: $formData,
                    cache: false,
                    ifModified: true,
                }); // jQuery.ajax

                ajaxCollationRequest.done(function(response) { // AJAX success!
                    var jsEnd = Number(new Date().getTime());
                    var jsTime = ((jsEnd - jsStart)/1000).toFixed(2);
                    SweetAlertResolve('XML-прайс створений!', jsTime, response.rozetka_xml_created_event);
                    hideSpinner();
                    jQuery('.mrkvuamp_progress_bar').fadeOut(500);
                });

                ajaxCollationRequest.fail(function(jqXHR, textStatus) { // AJAX error
                    jQuery('.mrkvuamp_progress_bar').fadeOut();
                    SweetAlert2Reject("Request failed: " + textStatus);
                    hideSpinner();
                });
            } // async function collateCategories($form, $formData)

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

            // Hide spinner beside 'Співставити' button
            async function hideSpinner() {
                jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').removeClass('mrkv_uamrkpl_collation_btn_desabled');
                jQuery('#mrkvuamp_loader').remove();
            }

            // Sweetalert2 success modal of begining xml creating
            async function SweetAlert2Resolve() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'XML-прайс створюється...',
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: true
                })
            }

            // Sweetalert2 final success modal with time script duration
            async function SweetAlertResolve(message, jsTime, phpTime) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: message,
                    html: '<p>Загальний час: ' + jsTime + ' сек.<br>Час PHP: ' + phpTime + ' сек.<br>Перезавантажте сторінку.</p>',
                    allowOutsideClick: true
                })
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
                error: function() { // file not exists or not clicked 'Співставити' button
                    jQuery('.mrkvuamp_progress_bar').removeClass('hidden');
                    if (!localStorage.getItem('mrkvuamp_collation_submit')) {
                        jQuery('.mrkvuamp_progress_bar').addClass('hidden');
                    }
                }
            });
        }

        // Progress bar handling
        var siteTotalProductQty = mrkvuamp_script_vars.site_total_product_qty;
        async function progressBarXMLupload(time){
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
            localStorage.removeItem('mrkvuamp_collation_submit');
        }

        function showProgressBarFinalMsg(elem) {
            elem.innerHTML = 'Ваш xml-прайс готовий. Перезавантажте сторінку і перейдіть за посиланням.';
            elem.classList.remove("blinking-message");
            elem.style.display = 'block';
        }

    } // Rozetka tab

}); // ready()
