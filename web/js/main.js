(function(){
    var response = "", paginationFilms, pNumFilms, bNumFilms, idFilm;

    function initVars(){
        bNumFilms = $('#num_films');
        pNumFilms = $('#p-num-films');
        paginationFilms = $('#pagination-films');
        idFilm = "";
    }

    function showModalVideo() {
        $('button.btn-url-video').on('click', function () {
            var url = $(this).attr('data-url').replace('watch?v=', 'embed/');
            $.sweetModal({
                title: '',
                content: '<iframe width="560" height="315" src="'+url+'" frameborder="0" allowfullscreen></iframe>',
                theme: $.sweetModal.THEME_DARK
            }).css({
                'width': '100%',
                'left': '5%'
            });
        });
    }

    function sendDataView(a, b, c){
        var val = 1;
        if(b == false) {
            val = 0;
        }
        var object = {
            'idFilm': a,
            'view':val,
            'punt': c
        };
        $.ajax({
            type: "POST",
            url: "{{ path('set_view_film') }}",
            data: object,
            dataType : 'json',
            success: function() {
                if(!b) {
                    $('p.my-punt_' + a).remove();
                }else if(!isNaN(c)){
                    var respo = '<p class="my-punt_'+a+'"><b>Mi calificación: </b><span class="rating"> '+c.replace('.0', '')+'</span></p>';
                    $(respo).insertAfter('p.view_'+a);
                }
            }
        }).done(function(){
        });
    }

    /**
     * Muestro un modal si se intenta cambiar el campo de visto/no visto
     */
    function changeInputView(){
        var idFilm = $(this).attr('data-film');
        var che = $(this).is(':checked');
        var title = "¿Estás seguro que has visto esta película?";
        var message = "¡Puntúala si es así!";
        var type = "input";
        if(!che){
            title = "¿Estás seguro que no has visto esta película?";
            type = "warning";
            message = "";
        }
        swal({
                title: title,
                text: message,
                type: type,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {
                    sendDataView(idFilm, che, "");
                    swal("¡Cambios guardados correctamente!", "success");
                    sendDataView(idFilm, che, inputValue);
                } else {
                    swal("No se han realizado cambios", "error");
                }
            });
    }

    {% if num_films is defined and page == 0 %}

//                $('#myModal').modal();
//
//                setTimeout(function() {
//                    $('#myModal').modal('hide');
//                }, 3000);
    {% endif %}

    function writeResponse(data){
        response = '<ul id="list-movies">';
        for(var i = 0; i < data['movies'].length; i++) {
            var punt = data['movies'][i]['rating'].replace(".", "");
            var rest = 100 - punt;
            response += '<li class="panel-movie"><div class="title-rating">' +
                '<h3>'+data['movies'][i]["originalTitle"]+'</h3><span class="rating">'+data['movies'][i]["rating"]+
                '</span><span class="punt" style="background: linear-gradient(to top, #CAAF00 '+punt+'%,' +
                '#CAAF00 '+punt+'%, #fffffa '+rest+'%)"></span>' +
                '</div>';
            if (data['movies'][i]['saga'] != ""){
                response += '<div class="title-saga">' +
                    '<h4><b>Saga : </b>'+data['movies'][i]['saga']+'</h4></div>';
            }
            response += '<ul class="nav nav-tabs nav-film">'+
                '<li role="presentation" class="a-desc active" data-id="'+data['movies'][i]['num']+'">'+
                '<a href="javascript:void(0);" data-id="'+data['movies'][i]['num']+'">Descripción</a>'+
                '</li>'+
                '<li role="presentation" class="a-trai" data-id="'+data['movies'][i]['num']+'">'+
                '<a href="javascript:void(0);" data-id="'+data['movies'][i]['num']+'">Trailers</a>'+
                '</li>'+
                '<li role="presentation" class="a-plus" data-id="'+data['movies'][i]['num']+'">'+
                '<a href="javascript:void(0);" data-id="'+data['movies'][i]['num']+'">Más...</a>'+
                '</li>'+
                '</ul>'+
                '<div class="description" data-id="'+data['movies'][i]['num']+'">'+
                '<p class="image"><img class="img-film" ' +
                'src="/upload/img/'+data['movies'][i]["picture"]+'" alt="'+data['movies'][i]["originalTitle"]+'"></p>' +
                '<p><b>Director:</b>'+data['movies'][i]["director"]+'</p><p><b>Actores: </b>'+data['movies'][i]["actors"]+'</p>' +
                '<div class="year-length"><span><b>Año: </b>'+data['movies'][i]["year"]+'</span><span><b>Duración: ' +
                '</b>'+data['movies'][i]["length"]+' min</span></div><p><b>Género: </b>'+data['movies'][i]["category"]+'</p>' +
                '<p class="country-ubication"><span><b>País: </b> '+data['movies'][i]["country"]+' </span> <span><b> Ubicación: ' +
                '</b>'+data['movies'][i]["media"]+'</span></p>' +
                '</div><div class="trailers" data-id="'+data['movies'][i]['num']+'">';
            if(data['movies'][i]['urlVideo']!=""){
                response += '<div class="show-trailer"><button type="button" class="btn btn-info btn-url-video" ' +
                    'data-url="'+data['movies'][i]['urlVideo']+'">Ver tráiler</button></div>';
            }else{
                response += '<h4>No hay ningún vídeo para mostrar.</h4>';
            }
            response += '</div>'+
                '<div class="plus" data-id="'+data['movies'][i]['num']+'">';
            if (data['movies'][i]['description'] != "" ){
                response += '<h4>Sinopsis</h4><p class="sipnosis">'+data['movies'][i]['description']+'</p>';
            }else{
                response += '<h4>No hay sinopsis para esta película.</h4>';
            }
            if(data['movies'][i]['visto']) {
                if (data['movies'][i]['visto'] == 1) {
                    che = "checked";
                } else {
                    che = "";
                }
                response += '<p class="format-view" id="' + data['movies'][i]['num'] + '"><span><b>Formato: </b>' + data['movies'][i]['videoFormat'] + '</span>' +
                    '<span class="view"><b>Vista: </b></span></p>';

                {% if is_granted('ROLE_ADMIN') %}
                response += '<input class="inline view_' + data['movies'][i]['num'] + '" type="checkbox" data-film="' + data['movies'][i]['num'] + '" name="checkbox-view" ' + che + '>';
                {% else %}
                response += '<span class="view-movie">';
                if (data['movies'][i]['visto'] == 1)
                    response += 'Si';
                else
                    response += 'No';
                response += '</span><p class="hidden view_'+data['movies'][i]['num']+'" </p>';
                {% endif %}
            }
            if(data['movies'][i]['miNota'] != ""){
                response += '<p class="my-punt_'+data['movies'][i]['num']+'"><b>Mi calificación: </b><span class="rating">'
                    +data['movies'][i]['miNota'].replace('.0', '')+'</span></p>';
            }
            response += '<p><b>Tamaño: </b>'+data['movies'][i]['fileSize']+' GB</p></div></li>';
        }
        response += '</ul>';
        $("#list-movies").html(response);
        bNumFilms.html(data['total_pelis']);
        pNumFilms.removeClass('hidden');
        var cont = 0;
        for (var x=0; x < $('input[name=category]').length; x++) {
            if ($('input[name=category]')[x].checked) {
                cont = cont + 1;
            }
        }
        $('p#result-films').css('display', "none");
        if(cont == 0){
            bNumFilms.html("");
            pNumFilms.addClass("hidden");
            $('p#result-films').css('display', "block");
        }
        var paginate = "";
        if(data['total_paginas'] > 1){
            paginate = '<div class="custom-pagination">';
            for(var i = 0; i < data['total_paginas']; i++){
                if(data['total_paginas'] > 4 && data['page_num'] > 1 && i < 1
                    && data['page_num']-2 > 0){
                    paginate += '<span class="first"><a class="a-page" data-page="0"><<</a></span>';
                    paginate += '<span class="previous"><a class="a-page" data-page="'+(data['page_num']-1)+'">&lt;</a></span>';
                }
                if(data['page_num'] == i){
                    paginate += '<span class="current">'+(i+1)+'</span>';
                }
                if(data['total_paginas'] > 4){
                    if(data['page_num']+3 < data['total_paginas'] &&
                        i == data['total_paginas']-1 && data['page_num'] < data['total_paginas']-2){
                        paginate += '<span class="last">' +
                            '<a class="a-page" data-page="'+(data['total_paginas']-1)+'">>></a></span>';
                    }else if(data['page_num']+3 < data['total_paginas']-1
                        && i == data['total_paginas'] - 2 && data['page_num'] < data['total_paginas']-1){
                        paginate += '<span class="next"><a class="a-page" data-page="'+(data['page_num']+1)+'">&gt;</a></span>';
                    }
                    if((i < data['page_num']+3) && (i > data['page_num']-3)
                        && i != data['page_num']){
                        paginate += '<span class="page"><a class="a-page" data-page="'+i+'">'+(i+1)+'</a></span>';
                    }
                }else if (i > data['page_num'] || i < data['page_num']){
                    paginate += '<span><a class="a-page" data-page="'+i+'">'+(i+1)+'</a></span>';
                }
            }
            paginate += '</div>';
        }
        paginationFilms.html(paginate);
        changeNavFilm();
        showModalVideo();
        changeInputView();
        getFunctionPagination();
    }

    function changeNavFilm(){
        $('.a-desc').on('click', function(){
            idFilm = $(this).attr('data-id');
            $('li[role="presentation"][data-id="'+idFilm+'"]').removeClass('active');
            $(this).addClass('active');
            $('.trailers[data-id="'+idFilm+'"]').css('display', 'none');
            $('.plus[data-id="'+idFilm+'"]').css('display', 'none');
            $('.description[data-id="'+idFilm+'"]').css('display', 'block');
        });

        $('.a-trai').on('click', function(){
            idFilm = $(this).attr('data-id');
            $('li[role="presentation"][data-id="'+idFilm+'"]').removeClass('active');
            $(this).addClass('active');
            $('.description[data-id="'+idFilm+'"]').css('display', 'none');
            $('.plus[data-id="'+idFilm+'"]').css('display', 'none');
            $('.trailers[data-id="'+idFilm+'"]').css('display', 'block');
        });

        $('.a-plus').on('click', function(){
            idFilm = $(this).attr('data-id');
            $('li[role="presentation"][data-id="'+idFilm+'"]').removeClass('active');
            $(this).addClass('active');
            $('.description[data-id="'+idFilm+'"]').css('display', 'none');
            $('.trailers[data-id="'+idFilm+'"]').css('display', 'none');
            $('.plus[data-id="'+idFilm+'"]').css('display', 'block');
        });
    }

    function getFunctionPagination(){
        $('a.a-page').on('click', function(){

            var page = $(this).attr('data-page');

            $.ajax({
                type: "GET",
                url: "{{ path('filter_films_category') }}",
                data: {'page':page},
                dataType : 'json',
                beforeSend: function () {
                    $("#list-movies").html("Procesando, espere por favor...");
                },
                success: function(data) {
                    writeResponse(data);
                }
            });
        });
    }

    $('input[name=category]').on('change', function(){
        var arrCategories = [];
        $.each($('input[name="category"]:checked'), function() {
            var value = $(this).val();
            arrCategories.push(value);
        });
        $.ajax({
            data: {'categories': arrCategories},
            url:   '{{ path('filter_films_category') }}',
            type:  'post',
            dataType : 'json',
            beforeSend: function () {
                $("#list-movies").html("Procesando, espere por favor...");
            },
            success:  function (data) {
                writeResponse(data);
            }
        })
    });
    initVars();
    changeNavFilm();
    showModalVideo();
    changeInputView();
})();