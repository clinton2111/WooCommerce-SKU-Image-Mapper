declare var jQuery, ProgressBar: any;

(function ($) {

    window.onload = (e) => {
        const domain: string = location.origin
        const subDomain: string = '/wordpress'

        const bar = new ProgressBar.Line('#progressContainer', {
            strokeWidth: 2,
            duration: 500,
            easing: 'easeInOut',
            trailColor: '#2a2a2a',
            trailWidth: 0.1,
            svgStyle: {width: '100%', height: '100%'},
            text: {
                style: {
                    // Text color.
                    // Default: same as stroke color (options.color)
                    color: '#2a2a2a',
                },
                autoStyleContainer: false
            },
            from: {color: '#FFEA82'},
            to: {color: '#ED6A5A'},
            step: (state, bar) => {
                bar.path.setAttribute('stroke', state.color);
                bar.setText(Math.round(bar.value() * 100) + ' %');
            }
        });


        let getProgress = (): void => {
            const ajax_url: string = domain + subDomain + '/wp-json/cm_img_map/api/mapping_progress'
            $.ajax({
                url: ajax_url,
                type: 'GET',
                success: (data) => {
                    console.log(data.progress)
                    bar.animate(data.progress);
                }
            })
        }

        $('#beginMapping').on('click', (event) => {
            let checked = $('#iUnderstand').is(":checked")
            if (!checked) {
                alert('Please check the check box')
                return false;
            } else {

                const ajax_url: string = domain + subDomain + '/wp-json/cm_img_map/api/begin_mapping'
                bar.animate(0.0);
                $.ajax({
                    url: ajax_url,
                    type: 'POST',
                    success: (data) => {
                        clearInterval(progressTimeout);
                        bar.animate(1);
                        if (data.success) {
                            $('#wpbody-content').prepend('<div class="notice notice-success is-dismissible"><p>' + data.data + '</p></div>');
                        } else {
                            $('#wpbody-content').prepend('<div class="notice notice-error"><p>' + data.data + '</p></div>');
                        }

                    }
                })

                const progressTimeout = setInterval(getProgress, 1500);
            }

        })

    }

})(jQuery);