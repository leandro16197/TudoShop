(function (window, $) {

    window.appCustom = {
        smallBox: function (type, msg, title = null, timeout = 3000) {

            // colores según tipo
            let bgClass = 'bg-secondary';

            if (type === 'ok') bgClass = 'bg-success';
            if (type === 'nok') bgClass = 'bg-danger';
            if (type === 'info') bgClass = 'bg-info';
            if (type === 'warning') bgClass = 'bg-warning';

            // contenedor
            let $box = $(`
                <div class="app-smallbox alert ${bgClass} text-white shadow"
                     style="
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        z-index: 9999;
                        min-width: 250px;
                        display: none;
                     ">
                    ${title ? `<strong>${title}</strong><br>` : ''}
                    ${msg}
                </div>
            `);

            $('body').append($box);

            $box.fadeIn(200);

            if (timeout !== 'NO_TIME_OUT') {
                setTimeout(function () {
                    $box.fadeOut(300, function () {
                        $(this).remove();
                    });
                }, timeout);
            }
        }
    };

})(window, jQuery);
