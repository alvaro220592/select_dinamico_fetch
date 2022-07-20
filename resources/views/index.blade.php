<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">    

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    {{-- Bootstrap icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css">

    {{-- <link href="css/sweetalert2.css" rel="stylesheet"> --}}
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

</head>
<body>
    <div class="container">
        <div class="row">
            {{-- Div do select dos autores --}}
            <div class="col-md-4 mb-3">
                    <label for="" class="mt-2">Selecione o autor </label>

                <div class="d-flex justify-content-between mb-2">
                    <select name="author_id" id="author_id" onchange="buscarLivros()" class="form-select" style="width: 90%">
                        {{--  --}}
                    </select>
                    <i id="add_autor" onclick="add_autor()" class="bi bi-plus-circle-fill mt-1 fs-4" style="cursor: pointer" title="Cadastrar autor"></i>
                </div>
            </div>

            {{-- Div do select dos livros --}}
            <div class="col-md-4 mb-3" id="div_select_livro" style="display: none">
                <label for="">Selecione o livro</label>
                <select name="livro_id" id="livro_id" class="form-select" onchange="informacoes_livro()">
                    {{-- O conteúdo será preenchido via JS --}}
                </select>
            </div>
        </div>

        <div class="row" id="carregando" style="display: none">
            <div class="col-md-4">
                <span>Carregando...</span>
            </div>
        </div>

        <div id="div_info" style="display: none">
            <h5>Informações do livro</h5>
            <ul id="lista_info">
                {{-- Será preenchido via JS --}}
            </ul>
        </div>

        <div>
            <button type="button" class="btn btn-success" onclick="modalFile()">abrir</button>
        </div>
    </div>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    {{-- Bootbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

    {{-- Sweet Alert --}}
    {{-- tema --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" />
    {{-- funcionalidade --}}
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="js/sweetalert2.all.min.js"></script>

    
    
    <script>        

        // Chamando a função q traz todos os autores ao carregar a página
        allAuthors();

        // Token para o envio de dados pro controller
        const token = document.querySelector('meta[name="csrf-token"]').content;

        // Select dos autores
        let select_autor = document.getElementById('author_id')

        // Row que traz a frase 'Carregando...'
        let carregando = document.getElementById('carregando');

        // Div do select dos livros
        let div_select_livro = document.getElementById('div_select_livro');

        // Select dos livros
        let select_livro = document.getElementById('livro_id');

        // Bloco de informações
        let div_info = document.getElementById('div_info');

        // Lista de informações
        let lista_info = document.getElementById('lista_info')

        // Busca todos os autores
        function allAuthors(){
            fetch(`allAuthors`)
            .then(response => response.json())
            .then(data => {
                // console.log(data);

                select_autor.innerHTML = ''
                select_autor.innerHTML += `<option value="" selected disabled>Selecione</option>`
                data.dados.forEach(dado => {
                    select_autor.innerHTML += `<option value=${dado.id}>${dado.firstname} ${dado.lastname }</option>`
                })

            })
            .catch(error => console.log(error))
        }

        function buscarLivros(){            
            
            // Requisição
            fetch(`authorsBooks/${select_autor.value}`, {
                headers: {
                    "Content-Type": "application/json; charset=utf-8",
                    "X-CSRF-Token": token
                },
                method: 'GET',
                // Corpo da requisição com o id do autor pra ser pego no controller via $request(antigo)
                // body: JSON.stringify({
                //     author_id: select_autor.value
                // })
            })
            .then(response => response.json())
            .then(data => {
                                
                div_info.style.display = 'none';

                // Fazendo aparecer o aviso de "Carregando..."
                carregando.style.display = 'block';
                
                // Fazendo aparecer a div dos livros
                div_select_livro.style.display = 'block';

                // Zerando os options do select de livros sempre que muda o autor
                select_livro.innerHTML = '';

                select_livro.innerHTML += `<option value="" selected disabled>Selecione</option>`

                // Percorrendo os dados vindos na resposta para gerar os options
                data.dados.forEach(item => {
                    select_livro.innerHTML += `<option value="${item.id}">${item.title}</option>`
                })

                // Fazendo sumir o aviso de "Carregando..."
                carregando.style.display = 'none';
            })
            // Caso haja erros
            .catch(error => console.log(error))
        }

        function informacoes_livro(){
            fetch(`bookInfo/${select_livro.value}`, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json; charset=utf-8",
                    "X-CSRF-Token": token
                },
            })
            .then(response => response.json())
            .then(data => {

                div_info.style.display = 'block';

                lista_info.innerHTML = '';

                lista_info.innerHTML += `
                    <li><strong>Título: </strong>${data.dados.title}</li>
                    <li><strong>Ano: </strong>${data.dados.year}</li>
                    <li><strong>Páginas: </strong>${data.dados.pages}</li>
                    <li><strong>Edição: </strong>${data.dados.edition}</li>
                `
            })
            .catch(error => console.log(error))            
        }

        function add_autor(){

            // 
            Swal.fire({
                title: 'Insira os dados',
                showCancelButton: true,
                confirmButtonText: 'Salvar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger',
                },
                buttonsStyling: false,
                html: `
                    <label for="firstname" class="text-left">Nome</label>
                    <input type="text" id="firstname" class="form-control mb-3">

                    <label for="lastname" class="text-left">Sobrenome</label>
                    <input type="text" id="lastname" class="form-control">
                `,
                preConfirm: function(result) {
                    let firstname = document.getElementById('firstname').value;
                    let lastname = document.getElementById('lastname').value;
                    
                    return new Promise(function(resolve, reject) {
                        if (result) {
                            fetch(`authors`, {
                                method: 'POST',
                                headers: {
                                    "Content-Type": "Application/json; charset=utf8",
                                    "X-CSRF-Token": token
                                },
                                body: JSON.stringify({
                                    firstname: firstname,
                                    lastname: lastname
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log(data.response);
                                Swal.fire("Deu certo!", data.response, "success")
                            })
                            .catch(error => {
                                console.log(data.response)
                                Swal.fire("Deu errado", data.response, "error")
                            })
                        }
                    });
                },
                allowOutsideClick: () => !this.$swal.isLoading(),
            })

            // // Exemplo
            // Swal.fire({
            // title: 'Submit your Github username',
            // // input: 'text',
            // html: `<input type="text" id="texto" class="form-control">`,
            // inputAttributes: {
            //     autocapitalize: 'off'
            // },
            // showCancelButton: true,
            // confirmButtonText: 'Look up',
            // showLoaderOnConfirm: true,
            // preConfirm: (login) => {
            //     return fetch(`//api.github.com/users/${login}`)
            //     .then(response => {

            //         // teste
            //         console.log(document.getElementById('texto').value);

            //         if (!response.ok) {
            //             throw new Error(response.statusText)
            //         }
            //         return response.json()
            //     })
            //     .catch(error => {
            //         Swal.showValidationMessage(
            //             `Request failed: ${error}`
            //         )
            //     })
            // },
            // allowOutsideClick: () => !Swal.isLoading()
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         Swal.fire({
            //             title: `${result.value.login}'s avatar`,
            //             imageUrl: result.value.avatar_url
            //         })
            //     }
            // })
        }

        function modalFile(){
            Swal.fire({
                title: 'Insira o arquivo',
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger',
                },
                buttonsStyling: false,
                html: `
                    <input type="file" id="arquivo">
                `,
                preConfirm: function(result) {

                    let arquivo = document.getElementById('arquivo')

                    let formData = new FormData()
                    formData.append('arquivo', arquivo.files[0])

                    return new Promise(function(resolve, reject) {
                        if (result) {
                            fetch('sendFile', {
                                method: 'POST',
                                headers: {
                                    contentType: false,
                                    processData: false,
                                    "X-CSRF-Token": token
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data =>{
                                Swal.fire({
                                    title: data.resposta[0]
                                })
                            })
                        }
                    });
                },
                allowOutsideClick: () => !this.$swal.isLoading(),
            })
        }
    </script>
</body>
</html>