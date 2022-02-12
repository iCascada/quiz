(function($) {
    const $buttons = document.getElementsByClassName('close')
    const $preloader = $('.preloader')
    let sec = 2500

    setTimeout(() => $preloader.fadeOut(), 500)

    if ($buttons.length) {
        for (let $button of $buttons) {
            $button.addEventListener('click', (event) => {
                event.target.closest('.alert').remove()
            })
        }
    }

    const link = document.querySelectorAll('a[href="' + window.location.href + '"]')

    if (link) {
        link.forEach(function(item) {
            item.classList.add('active')
        })
    }

    $('.alert').each(function() {
        if (!$(this).hasClass('show-always')) {
            setTimeout(() => {
                $(this).fadeOut()
            }, sec)
        }
    })

    window.message = (message) => {
        const $alert = $('<div>')
            .addClass('alert alert-danger fz-1-1 align-items-center w-100')

        const $message = $('<div>')
            .addClass('text-center')
            .html(message)

        const $icon = $('<div>')
            .append(
                $('<span>')
                    .append(
                        $('<i>')
                            .addClass('fa fa-exclamation-circle')
                    )
                    .addClass('alert-icon')
            )

        const $button = $('<button>')
            .addClass('close')
            .attr('data-dismiss', 'alert')
            .append(
                $('<span>').html("&times;")
            )

        return $alert
            .prepend($icon)
            .append($message)
            .append($button)

    }

})(jQuery)
