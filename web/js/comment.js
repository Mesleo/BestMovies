(function(){

    var page;

    function initVars(){

    }



    function writePagination(data) {
        var paginate = "";
        if (data['total_paginas'] > 1) {
            paginate = '<div class="pagination-true">';
            for (var i = 0; i < data['total_paginas']; i++) {
                if (data['total_paginas'] > 4 && data['page_num'] > 1 && i < 1
                    && data['page_num'] - 2 > 0) {
                    paginate += '<span class="first"><a class="a-page" data-page="0"><<</a></span>';
                    paginate += '<span class="previous"><a class="a-page" data-page="' + (data['page_num'] - 1) + '">&lt;</a></span>';
                }
                if (data['page_num'] == i) {
                    paginate += '<span class="current">' + (i + 1) + '</span>';
                }
                if (data['total_paginas'] > 4) {
                    if (data['page_num'] + 3 < data['total_paginas'] &&
                        i == data['total_paginas'] - 1 && data['page_num'] < data['total_paginas'] - 2) {
                        paginate += '<span class="last">' +
                            '<a class="a-page" data-page="' + (data['total_paginas'] - 1) + '">>></a></span>';
                    } else if (data['page_num'] + 3 < data['total_paginas'] - 1
                        && i == data['total_paginas'] - 2 && data['page_num'] < data['total_paginas'] - 1) {
                        paginate += '<span class="next"><a class="a-page" data-page="' + (data['page_num'] + 1) + '">&gt;</a></span>';
                    }
                    if ((i < data['page_num'] + 3) && (i > data['page_num'] - 3)
                        && i != data['page_num']) {
                        paginate += '<span class="page"><a class="a-page" data-page="' + i + '">' + (i + 1) + '</a></span>';
                    }
                } else if (i > data['page_num'] || i < data['page_num']) {
                    paginate += '<span><a class="a-page" data-page="' + i + '">' + (i + 1) + '</a></span>';
                }
            }
            paginate += '</div>';
            paginationFilms.html(paginate);
        }else
            paginationFilms.html('');
    }

    initVars();
})();