//modulo do angular
angular.module("myApp", []).controller("myCtrl", function($scope){
    $scope.pesquisa = "";
    $scope.retorno = false;
    $scope.categoria = '0';
    $scope.grupo = '0';
    $scope.subgrupo = '0';
    $scope.tipo = '0';
    $scope.subtipo = '0';
    $scope.fotos = false;
});

if(window.location.href == 'http://defesacivil.bc.sc.gov.br/index.php' || window.location.href == 'http://defesacivil.bc.sc.gov.br/'){
//if(window.location.href == 'http://localhost/aplicacao/' || window.location.href == 'http://localhost/aplicacao/index.php'){
    var FEED_URL = 'https://idapfile.mi.gov.br/idap/api/rss/cap'
    $.get(FEED_URL, function (data) {
        html = ''
        $(data).find("entry").each(function () { // or "item" or whatever suits your feed
            var el = $(this);
            if(el.find("areaDesc").text().endsWith("/SC")){
                html += '<article><h4>'
                html += el.find("title").text()
                html += '</h4><hr><p>'
                html += el.find("description").text()
                html += '</p></article><br>'
            }
        });
        if(html == '')
            html = '<h4>Nenhum alerta atualmente.</h4>'
        document.getElementById("noticias").innerHTML = html
    })
}

//codigos jquery - javascript
$("#cep").focusout(function(){
    $(this).val().replace('-', '');
    $(this).val().replace('.', '');
    $.ajax({
        url: 'https://viacep.com.br/ws/'+$(this).val()+'/json/unicode/',
        dataType: 'json',
        success: function(resposta){
            //$("#logradouro").val(resposta.logradouro);
            //$("#complemento").val(resposta.complemento);
            //$("#bairro").val(resposta.bairro);
            //$("#cidade").val(resposta.localidade);
            //$("#uf").val(resposta.uf);
            //$("#numero").focus();
        }
    });	
});

$("#telefone_pessoa").mask("(00) 0000-0000");
$("#telefone").mask("(00) 00000-0000");
$("#celular_pessoa").mask("(00) 00000-0000");
$("#cep").mask("00000-000");
$("#cpf").mask("000.000.000-00");
$("#cpf_pessoa").mask("000.000.000-00");

function verificaCpf(cpf){
    if (cpf == ""){
        $("#erroCpf").addClass("hide");
        return;
    }
    cpf = cpf.split(".").join("").replace('-','');
    var numeros, digitos, soma, i, resultado;
    var digitos_iguais = true;
    digitos_iguais = 1;
    if(cpf.length < 11){
        $("#erroCpf").removeClass("hide");
        return;
    }
    for(i = 0; i < cpf.length - 1; i++){
        if (cpf.charAt(i) != cpf.charAt(i + 1)){
            digitos_iguais = false;
            break;
        }
    }
    if(!digitos_iguais){
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--){
            soma += numeros.charAt(10 - i) * i;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0)){
            $("#erroCpf").removeClass("hide");
            return;
        }
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--){
            soma += numeros.charAt(11 - i) * i;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1)){
            $("#erroCpf").removeClass("hide");
            return;
        }
        $("#erroCpf").addClass("hide");
        return;
    }else{
        $("#erroCpf").removeClass("hide");
        return;
    }
}

function verificaCelular(telefone){
    
    if(telefone.length > 0){
        if(/\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}/.test(telefone))
        $("#erroCelular").addClass("hide");
    else
        $("#erroCelular").removeClass("hide");
    }else{
        $("#erroCelular").addClass("hide");
    }
}

function verificaTelefone(telefone){
    if(telefone.length > 0){
        if(/\([0-9]{2}\)\s[0-9]{4}\-[0-9]{4}/.test(telefone))
        $("#erroTelefone").addClass("hide");
        else
        $("#erroTelefone").removeClass("hide");
    }else{
        $("#erroTelefone").addClass("hide");
    }

}

function verificaSenha(senha){
    if(!/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})/.test(senha)){
        $("#erroSenha").removeClass("hide");
    }else{
        $("#erroSenha").addClass("hide");
    }
}

function verificaConfirmaSenha(confirmaSenha){
    senha = $("#senha").val();
    if(senha != confirmaSenha){
        $("#erroConfirmaSenha").removeClass("hide");
    }else{
        $("#erroConfirmaSenha").addClass("hide");
    }
}


function validarFormCadastroUsuario(){
    if(!$("#erroCpf").hasClass("hide") || !$("#erroTelefone").hasClass("hide")
       || !$("#erroSenha").hasClass("hide") || !$("#erroConfirmaSenha").hasClass("hide")){
        
        alert("Existe campo(s) infomado(s) incorretamente.");
        return false;
    }
}

function validarFormCadastroPessoa(){
    if(!$("#erroCpf").hasClass("hide") || !$("#erroTelefone").hasClass("hide")){
        alert("Existe campo(s) infomado(s) incorretamente.");
        return false;
    }
    return true;
}

function validarFormAlterarSenha(){
    if(!$("#erroSenha").hasClass("hide") || !$("#erroConfirmaSenha").hasClass("hide")){
        alert("Existe campo(s) infomado(s) incorretamente.");
        return false;
    }
}

//ordenar tabela de ocorrencias
function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("tabela");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc"; 
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("td")[n];
            y = rows[i + 1].getElementsByTagName("td")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch= true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount ++;      
        } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
            dir = "desc";
            switching = true;
            }
        }
    }
}

function showResult(str, id_input) {
    var id = "livesearch"+id_input;
    if (str.length===0) { 
        document.getElementById(id).innerHTML="";
        document.getElementById(id).style.border="0px";
        return;
    }
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {  // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
           document.getElementById(id).innerHTML=this.responseText;
            document.getElementById(id).style.border="1px solid #A5ACB2";
        }


    }
    xmlhttp.open("GET","livesearch.php?q="+str+"&id="+id_input,true);
    xmlhttp.send();
 console.log(xmlhttp);
}

function selecionaComplete(value, id_input){
    var id = "livesearch"+id_input;
    document.getElementById(id_input).value = value;
    document.getElementById(id).innerHTML="";
    document.getElementById(id).style.border="0px";
}

$(document).on("click", ".open-AddBookDialog", function () {
    var element_id = $(this).data('id');
    if(element_id == 'map'){
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(myMap);
            $('#map').modal('show');  
        }else{
            $('#map').modal('show');
        }
    }else if(element_id == 'motivo'){
        $('#cancelarModal').modal('show');
    }else if(element_id == 'pessoa_nome1'){
        $(".modal-body #id_pessoa").val( element_id );
        $('#pessoa1Modal').modal('show');
    }else if(element_id = 'pessoa_nome2'){
        $(".modal-body #id_pessoa").val( element_id );
        $('#pessoa2Modal').modal('show');
    }else if(element_id = 'excluirFoto'){
        $("#excluirModal").modal('show');
    }
    else{
        $(".modal-body #id_pessoa").val( element_id );
        $('#pessoasModal').modal('show');
    }
});

//POST pessoa
var input = document.getElementById("submitFormData");
// Execute a function when the user releases a key on the keyboard
input.addEventListener("keydown", function(event) {
// Number 13 is the "Enter" key on the keyboard
if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    document.getElementById("submitFormData").click();
}
});

function SubmitFormData() {
    if(!validarFormCadastroPessoa()){
        return false;
    }

    var id_input = $("#id_pessoa").val();
    var nome_pessoa = $("#nome_pessoa").val();
    var email_pessoa = $("#email_pessoa").val();
    var celular_pessoa = $("#celular_pessoa").val();
    var telefone_pessoa = $("#telefone_pessoa").val();
    var cpf_pessoa = $("#cpf_pessoa").val();
    var outros_documentos = $("#outros_documentos").val();

    var id="result"+id_input;

    
    //$.post("processa_cadastrar_pessoa.php", { nome_pessoa: nome_pessoa, email_pessoa: email_pessoa,
    //    telefone_pessoa: telefone_pessoa, cpf_pessoa: cpf_pessoa, outros_documentos:outros_documentos, nome_salvar: nome_pessoa });
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {  // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById('pessoa_nome').value = nome_pessoa;
            this.responseText = 'Pessoa cadastrada com sucesso'
            document.getElementById('alertpessoasucesso').innerHTML=this.responseText;
            document.getElementById(id).style.color="#00FF00";


        }
    }
    xmlhttp.open("GET","processa_cadastrar_pessoa.php?nome_pessoa="+nome_pessoa+"&email_pessoa="+email_pessoa+"&celular_pessoa="+celular_pessoa+"&telefone_pessoa="+telefone_pessoa+"&cpf_pessoa="+cpf_pessoa+"&outros_documento="+outros_documentos,true);
    xmlhttp.send();

    $('#pessoasModal').modal('hide');
}

function myMap(position) {
    if($('#latitude').html()){

        var latitude = parseFloat($('#latitude').html());
        var longitude = parseFloat($('#longitude').html());
        var myLatLng = {lat: latitude, lng: longitude};
    }else{
        if(position)
            var myLatLng = {lat: position.coords.latitude, lng: position.coords.longitude};
        else
            var myLatLng = {lat: -26.9939744, lng: -48.6542015};
    }

    var mapProp= {
        center:myLatLng,
        zoom:15
    };

    var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map
    });

    if(!$('#latitude').html()){
        google.maps.event.addListener(map, 'click', function(event) {
            $("#latitude").val(event.latLng.lat());
            $("#longitude").val(event.latLng.lng());
            myLatLng = {lat: event.latLng.lat(), lng: event.latLng.lng()}
            marker.setPosition(myLatLng);
        });
    } 
}

function getJSON(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
      var status = xhr.status;
      if (status === 200) {
        callback(null, xhr.response);
      } else {
        callback(status, xhr.response);
      }
    };
    xhr.send();
};

function ativaJson(){
    $('#sensor99018').css('background-color','rgb(24,240,78)');
    $('#sensor1019').css('background-color','rgb(24,240,78)');
    $("body").css("background-color", "rgb(24,240,78)");

    //99018
    //requisicao nivel do rio
    getJSON('http://localhost:3000/?limite=2&estacao=99018&variavel=9001.00', function(err, data){
        var rioAtencao = 35001;
        var rioAlerta = 35002;
        var rioPerigo = 35003;
        if(err !== null){
            alert('Erro ao carregar API - 1?? requisi????o');
        }else{  
            $("#nivel_rio99018").html(data[0].Valor);
            if(data[0].Valor >= rioAtencao && data[0].Valor < rioAlerta){
                if($('#sensor99018').css('background-color') == 'rgb(24, 240, 78)'){
                    $("body").css("background-color", "yellow");
                    $('#sensor99018').css('background-color', 'yellow');
                }
            }else if(data[0].Valor >= rioAlerta && data[0].Valor < rioPerigo){
                if($('#sensor99018').css('background-color') == 'rgb(255, 255, 0)' | $('#sensor99018').css('background-color') == 'rgb(24, 240, 78)'){
                    $("body").css("background-color", "orange");
                    $('#sensor99018').css('background-color', 'orange');
                }
            }else if(data[0].Valor >= rioPerigo){
                $("body").css("background-color", "red");
                $('#sensor99018').css('background-color', 'red');
            }
            $("#nivel_rio99018_indicacao").removeClass();
            if(data[0].Valor < data[1].Valor){
                $("#nivel_rio99018_indicacao").addClass("arrow-down");
            }else if(data[0].Valor > data[1].Valor){
                $("#nivel_rio99018_indicacao").addClass("arrow-up");
            }else{
                $("#nivel_rio99018_indicacao").addClass("estavel");
            }
        }
    });

    //requisicao precipitacao 10min
    getJSON('http://localhost:3000/?limite=2&estacao=99018&variavel=9002.00', function(err, data){
        var chuvaAtencao = 10;
        var chuvaAlerta = 30;
        var chuvaPerigo = 70;
        if(err !== null){
            alert('Erro ao carregar API - 2?? requisi????o');
        }else{
            $("#nivel_precipitacao99018_10").html(data[0].Valor);
            if(data[0].Valor >= chuvaAtencao && data.Valor < chuvaAlerta){
                if($('#sensor99018').css('background-color') == 'rgb(24,240,78)'){
                    $("body").css("background-color", "yellow");
                    $('#sensor99018').css('background-color', 'yellow');
                }
            }else if(data[0].Valor >= chuvaAlerta && data.Valor < chuvaPerigo){
                if($('#sensor99018').css('background-color') == 'rgb(24,240,78)' | $('#sensor99018').css('background-color') == 'rgb(255, 255, 0)'){
                    $("body").css("background-color", "orange");
                    $('#sensor99018').css('background-color', 'orange');
                }
            }else if(data[0].Valor >= chuvaPerigo){
                $("body").css("background-color", "red");
                $('#sensor99018').css('background-color', 'red');
            }
            $("#nivel_precipitacao99018_indicacao_10").removeClass();
            if(data[0].Valor < data[1].Valor){
                $("#nivefdl_precipitacao99018_indicacao_10").addClass("arrow-down");
            }else if(data[0].Valor > data[1].Valor){
                $("#nivel_precipitacao99018_indicacao_10").addClass("arrow-up");
            }else{
                $("#nivel_precipitacao99018_indicacao_10").addClass("estavel");
            }
        }
    });

    //1019
    //requisicao precipitacao 1 hora
    getJSON('http://localhost:3000/?limite=2&estacao=1019&variavel=271.00', function(err, data){
        var chuvaAtencao = 10;
        var chuvaAlerta = 30;
        var chuvaPerigo = 70;
        if(err !== null){
            alert('Erro ao carregar API - 3?? requisi????o');
        }else{
            $("#nivel_precipitacao1019_1").html(data[0].Valor);
            if(data[0].Valor >= chuvaAtencao && data.Valor < chuvaAlerta){
                if($('#sensor1019').css('background-color') == 'rgb(24,240,78)'){
                    $("body").css("background-color", "yellow");
                    $('#sensor1019').css('background-color', 'yellow');
                }
            }else if(data[0].Valor >= chuvaAlerta && data.Valor < chuvaPerigo){
                if($('#sensor1019').css('background-color') == 'rgb(24,240,78)' | $('#sensor99018').css('background-color') == 'rgb(255, 255, 0)'){
                    $("body").css("background-color", "orange");
                    $('#sensor1019').css('background-color', 'orange');
                }
            }else if(data[0].Valor >= chuvaPerigo){
                $("body").css("background-color", "red");
                $('#sensor1019').css('background-color', 'red');
            }
            $("#nivel_precipitacao1019_indicacao_1").removeClass();
            if(data[0].Valor < data[1].Valor){
                $("#nivel_precipitacao1019_indicacao_1").addClass("arrow-down");
            }else if(data[0].Valor > data[1].Valor){
                $("#nivel_precipitacao1019_indicacao_1").addClass("arrow-up");
            }else{
                $("#nivel_precipitacao1019_indicacao_1").addClass("estavel");
            }
        }
    });

    //requisicao precipitacao 12 horas
    getJSON('http://localhost:3000/?limite=2&estacao=1019&variavel=271.04', function(err, data){
        var chuvaAtencao = 10;
        var chuvaAlerta = 30;
        var chuvaPerigo = 70;
        if(err !== null){
            alert('Erro ao carregar API - 4?? requisi????o');
        }else{
            $("#nivel_precipitacao1019_12").html(data[0].Valor);
            if(data[0].Valor >= chuvaAtencao && data.Valor < chuvaAlerta){
                if($('#sensor1019').css('background-color') == 'rgb(24,240,78)'){
                    $("body").css("background-color", "yellow");
                    $('#sensor1019').css('background-color', 'yellow');
                }
            }else if(data[0].Valor >= chuvaAlerta && data.Valor < chuvaPerigo){
                if($('#sensor1019').css('background-color') == 'rgb(24,240,78)' | $('#sensor99018').css('background-color') == 'rgb(255, 255, 0)'){
                    $("body").css("background-color", "orange");
                    $('#sensor1019').css('background-color', 'orange');
                }
            }else if(data[0].Valor >= chuvaPerigo){
                $("body").css("background-color", "red");
                $('#sensor1019').css('background-color', 'red');
            }
            $("#nivel_precipitacao1019_indicacao_12").removeClass();
            if(data[0].Valor < data[1].Valor){
                $("#nivel_precipitacao1019_indicacao_12").addClass("arrow-down");
            }else if(data[0].Valor > data[1].Valor){
                $("#nivel_precipitacao1019_indicacao_12").addClass("arrow-up");
            }else{
                $("#nivel_precipitacao1019_indicacao_12").addClass("estavel");
            }
        }
    });

    //requisicao temperatura
    getJSON('http://localhost:3000/?limite=2&estacao=1019&variavel=192', function(err, data){
        var tempAtencao = 35
        var tempAlerta = 40;
        var tempPerigo = 45;
        if(err !== null){
            alert('Erro ao carregar API - 5?? requisi????o');
        }else{
            $("#temperatura1019").html(data[0].Valor);
            if(data[0].Valor >= tempAtencao && data.Valor < tempAlerta){
                if($('#sensor1019').css('background-color') == 'rgb(24,240,78)'){
                    $("body").css("background-color", "yellow");
                    $('#sensor1019').css('background-color', 'yellow');
                }
            }else if(data[0].Valor >= tempAlerta && data.Valor < tempPerigo){
                if($('#sensor1019').css('background-color') == 'rgb(24,240,78)' | $('#sensor99018').css('background-color') == 'rgb(255, 255, 0)'){
                    $("body").css("background-color", "orange");
                    $('#sensor1019').css('background-color', 'orange');
                }
            }else if(data[0].Valor >= tempPerigo){
                $("body").css("background-color", "red");
                $('#sensor1019').css('background-color', 'red');
            }
            $("#temperatura1019_indicacao").removeClass();
            if(data[0].Valor < data[1].Valor){
                $("#temperatura1019_indicacao").addClass("arrow-down");
            }else if(data[0].Valor > data[1].Valor){
                $("#temperatura1019_indicacao").addClass("arrow-up");
            }else{
                $("#temperatura1019_indicacao").addClass("estavel");
            }
        }
    });
}

function monitorarChamado() {
    if(window.XMLHttpRequest){
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }else{  // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById('requestChamado').innerHTML=this.responseText;
        }
    }
    xmlhttp.open("GET","requestChamado.php",true);
    xmlhttp.send();
}


function formatarDataInterdicao(){
    let data = document.getElementById('data_interdicao').value;

    if(data.length == 2){
        data = data + '/';
        document.forms[0].data.value = data;
        return true;              
    }
    if (data.length == 5){
        data = data + '/';
        document.forms[0].data.value = data;
        return true;
    }
}

$(function() {
    // Previsualizar as imagens no cadastro
    var imagesPreview = function(input, placeToInsertImagePreview) {
        
        if (input.files) {
            var filesAmount = input.files.length;
            if(input.files.length > 0 ){
                $("div.gallery").html("");
            }
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img class="imageThumb">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#imgInp').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
});


function abrirMapa(){
    let map = document.getElementById('map');

    if(map.classList.contains('hide')){
        map.classList.remove('hide');
    }
}

function fecharMapa(){
    let map = document.getElementById('map');

        map.classList.add('hide')
}

function modalFoto(value,id){
    document.getElementById('excluirModal').classList.remove('modal')
    document.getElementById('excluirModal').classList.remove('fade')
    document.getElementById(id).classList.add('hide');
    document.getElementById('pegarIdFoto').value = id;
}

function fecharModalFoto(){
    let id = document.getElementById('pegarIdFoto').value;
    document.getElementById(id).classList.remove('hide');
    document.getElementById('excluirModal').classList.add('modal')
    document.getElementById('excluirModal').classList.add('fade')
}

function excluirFoto(){
    let id = document.getElementById('pegarIdFoto').value;
    document.getElementById('excluirModal').classList.add('modal')
    document.getElementById('excluirModal').classList.add('fade')
    document.getElementById('idFotoParaExcluir').value = id;

}

function corrigeTelefone(){
   let telefone = document.getElementById('telefone_pessoa').value;
    if(telefone.length == 1){
        let telefoneNovo = '';
        document.getElementById('telefone_pessoa').value = telefoneNovo;
    }
}

function editarPessoa(){
    document.getElementById('editar_pessoa').classList.add('hidden');
    document.getElementById('salvar_pessoa').classList.remove('hidden');
    document.getElementById('nome_pessoa').removeAttribute('readonly');
    document.getElementById('cpf_pessoa').removeAttribute('readonly');
    document.getElementById('outros_documentos').removeAttribute('readonly');
    document.getElementById('celular_pessoa').removeAttribute('readonly');
    document.getElementById('telefone_pessoa').removeAttribute('readonly');
    document.getElementById('email_pessoa').removeAttribute('readonly');
}

function salvarEditPessoa() {
    
    var id_pessoa = $("#pessoa_id").val();
    var nome_pessoa = $("#nome_pessoa").val();
    var email_pessoa = $("#email_pessoa").val();
    var celular_pessoa = $("#celular_pessoa").val();
    var telefone_pessoa = $("#telefone_pessoa").val();
    var cpf_pessoa = $("#cpf_pessoa").val();
    var outros_documentos = $("#outros_documentos").val();

    //$.post("processa_cadastrar_pessoa.php", { nome_pessoa: nome_pessoa, email_pessoa: email_pessoa,
    //    telefone_pessoa: telefone_pessoa, cpf_pessoa: cpf_pessoa, outros_documentos:outros_documentos, nome_salvar: nome_pessoa });
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {  // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            if(this.response == 'Sucesso'){
                document.getElementById('sucessoEditPessoa').innerHTML = 'Dados alterados com sucesso!';
                document.getElementById('editar_pessoa').classList.remove('hidden');
                document.getElementById('salvar_pessoa').classList.add('hidden');
                document.getElementById('nome_pessoa').readOnly = true;
                document.getElementById('cpf_pessoa').readOnly = true;
                document.getElementById('outros_documentos').readOnly = true;
                document.getElementById('celular_pessoa').readOnly = true;
                document.getElementById('telefone_pessoa').readOnly = true;
                document.getElementById('email_pessoa').readOnly = true;
                document.getElementById('pessoaNome').innerHTML = nome_pessoa;
            }else{
                document.getElementById('falhaEditPessoa').innerHTML = 'Ocorreu uma falha para alterar os dados.';
            }
            
        }
    }
    xmlhttp.open("GET","processa_editar_pessoa.php?nome_pessoa="+nome_pessoa+"&email_pessoa="+email_pessoa+"&celular_pessoa="+celular_pessoa+"&telefone_pessoa="+telefone_pessoa+"&cpf_pessoa="+cpf_pessoa+"&outros_documento="+outros_documentos+"&id_pessoa="+id_pessoa,true);
    xmlhttp.send();
}

function habilitarEdicaoNotificacao(){
    document.querySelector('#editarNotificacao').classList.add('hidden')
    document.querySelector('#salvarNotificacao').classList.remove('hidden')
    document.querySelector('#cidade').removeAttribute('readonly')
    document.querySelector('#bairro').removeAttribute('disabled')
    document.querySelector('#logradouro').removeAttribute('readonly')
    document.querySelector('#numero').removeAttribute('readonly')
    document.querySelector('#referencia').removeAttribute('readonly')
    document.querySelector('#data_emissao').removeAttribute('readonly')
    document.querySelector('#descricao').removeAttribute('readonly')
    document.querySelector('#representante').removeAttribute('disabled')
    document.querySelector('#notificado').removeAttribute('readonly')
    document.querySelector('#data_vencimento').removeAttribute('readonly')
    document.querySelector('#complemento').removeAttribute('readonly')
    document.querySelector('#areaDocumentoAssinado').classList.remove('hidden');
}

