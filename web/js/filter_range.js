(function(){
    var minSpanAnio, maxSpanAnio ,minSpanTiempo, maxSpanTiempo, valueAnioMin, valueAnioMax, valueLengthMin, valueLengthMax;
    var slider = new Slider('#anio', {});
    var slider2 = new Slider('#length', {});

    function initVars(){
        minSpanTiempo = $('#length-min');
        maxSpanTiempo = $('#length-max');
        minSpanAnio = $('#anio-min');
        maxSpanAnio = $('#anio-max');
        setAnio();
        setLength();
    }

    function setLength() {
        var val = $('input#length').attr('data-value');
        valueLengthMin = val.substring(0, val.indexOf(","));
        valueLengthMax = val.substring(val.indexOf(",")+1, val.length);
        minSpanTiempo.html(valueLengthMin+' min');
        maxSpanTiempo.html(valueLengthMax+' min');
        $('input#length').value = $('input#length').attr('data-slider-value');
    }

    function setAnio() {
        var val = $('input#anio').attr('data-value');
        valueAnioMin = val.substring(0, val.indexOf(","));
        valueAnioMax = val.substring(val.indexOf(",")+1, val.length);
        minSpanAnio.html(valueAnioMin);
        maxSpanAnio.html(valueAnioMax);
        $('input#anio').value = $('input#anio').attr('data-slider-value');
    }

    $('input#anio').on('change', function(){
        setAnio();
    });

    $('input#length').on('change', function(){
        setLength();
    });
    initVars();
})();