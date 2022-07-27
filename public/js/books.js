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

        select_autor.innerHTML = ''
        select_autor.innerHTML += data.dados.length > 0 ? `<option value="" selected disabled>Selecione</option>` : `<option value="" selected disabled>Nenhum autor cadastrado</option>`
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

        select_livro.innerHTML += data.dados.length > 0 ? `<option value="" selected disabled>Selecione</option>` : `<option value="" selected disabled>Nenhum livro cadastrado</option>`

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

// modal de resposta
modalResposta = new Modal({
    el: document.getElementById('modalResposta')
})

function add_autor(){
    
    //+++++++ Início Modal Vanilla
    let modalAutor = new Modal({
        el: document.getElementById('modalAutor')
    });

    modalAutor.show();

    document.getElementById('btn_ok').addEventListener('click', () => {
        let firstname = document.getElementById('firstname').value;
        let lastname = document.getElementById('lastname').value;
        let lista_erros = document.getElementById('erros');

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

            if(data.status == 'Dados inválidos'){
                document.getElementById('aviso').style.display = 'block'

                if(firstname == '' && lastname == ''){
                    lista_erros.innerHTML = ''
                    lista_erros.innerHTML += `<li>${data.response.firstname}</li>`
                    lista_erros.innerHTML += `<li>${data.response.lastname}</li>`
                }else if(firstname == ''){
                    lista_erros.innerHTML = ''
                    lista_erros.innerHTML += `<li>${data.response.firstname}</li>`
                }else if(lastname == ''){
                    lista_erros.innerHTML = ''
                    lista_erros.innerHTML += `<li>${data.response.lastname}</li>`
                }

                setTimeout(() => {
                    document.getElementById('aviso').style.display = 'none'
                }, 3000);
            }else{
                modalAutor.hide();
                
                modalResposta.show();

                document.getElementById('icone_resposta').innerHTML = `<i class="bi bi-check2-circle text-success fs-1"></i>`
                document.getElementById('mensagem_resposta').innerHTML = `${data.response}`
                
                setTimeout(() => {
                    modalResposta.hide();
                }, 3000);
            }

                allAuthors()
            })
        .catch(error => {

        })
        // modal.hide();
    })
}

function add_livro(){

    //+++++++ Início Modal Vanilla
    let modalLivro = new Modal({
        el: document.getElementById('modalLivro')
    });

    modalLivro.show();

    document.getElementById('btn_ok_livro').addEventListener('click', () => {
        let title = document.getElementById('title').value
        let year = document.getElementById('year').value
        let pages = document.getElementById('pages').value
        let edition = document.getElementById('edition').value
        let id_autor = select_autor.value

        fetch(`books`, {
            method: 'POST',
            headers: {
                "Content-Type": "Application/json; charset=utf8",
                "X-CSRF-Token": token
            },
            body: JSON.stringify({
                title: title,
                year: year, 
                pages: pages, 
                edition: edition, 
                author_id: id_autor,
            })
        })
        .then(response => response.json())
        .then(data => {

            if(data.status == 'ok'){
                buscarLivros()

                modalLivro.hide();
                    
                modalResposta.show();

                document.getElementById('icone_resposta').innerHTML = `<i class="bi bi-check2-circle text-success fs-1"></i>`
                document.getElementById('mensagem_resposta').innerHTML = `${data.response}`
                
                setTimeout(() => {
                    modalResposta.hide();
                }, 3000);            

            }else{
                // Swal.fire("Deu errado", data.response, "error")
            }

        })
        .catch(error => {
            // Swal.fire("Deu errado", data.response, "error")
        })
    })
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