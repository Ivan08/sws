var page = 1;
var url = $(".infBlock").attr('data-url');
$(window).scroll(function () {
    if (page != 0 && $(window).scrollTop() + $(window).height() >= $(document).height()) {
        page++;
        load(page);
    }
});

function load(page) {
    $.ajax(
        {
            url: window.url + '?page=' + page,
            type: "get",
        })

        .done(function (data) {
            $(".infBlock").append(data);
            if (data.length === 1) {
                window.page = 0;
            }
        })

        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('server not responding...');
        });

}
