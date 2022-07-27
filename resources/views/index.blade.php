<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- Bootstrap CSS --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}

    {{-- Bootstrap icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css">

    <link href="css/sweetalert2.css" rel="stylesheet">

    <script src="js/books.js" defer></script>

</head>

<body>
    <div class="container">
        <div class="row">
            {{-- Div do select dos autores --}}
            <div class="col-md-4 mb-3">
                <label for="" class="mt-2">Selecione o autor </label>

                <div class="d-flex justify-content-between mb-2">
                    <select name="author_id" id="author_id" onchange="buscarLivros()" class="form-select"
                        style="width: 90%">
                        {{--  --}}
                    </select>
                    <i id="add_autor" onclick="add_autor()" class="bi bi-plus-circle-fill mt-1 fs-4"
                        style="cursor: pointer" title="Cadastrar autor"></i>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Div do select dos livros --}}
            <div class="col-md-4 mb-3" id="div_select_livro" style="display: none;">
                <label for="" class="mt-2">Selecione o livro</label>

                <div class="d-flex justify-content-between mb-2">
                    <select name="livro_id" id="livro_id" class="form-select" onchange="informacoes_livro()"
                        style="width: 90%">
                        {{-- O conteúdo será preenchido via JS --}}
                    </select>
                    <i id="add_livro" onclick="add_livro()" class="bi bi-plus-circle-fill mt-1 fs-4"
                        style="cursor: pointer" title="Cadastrar livro"></i>
                </div>
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

        {{-- <div>
            <button type="button" class="btn btn-success" onclick="modalFile()">abrir</button>
        </div> --}}
    </div>

    {{-- ============================Modal vanilla================================== --}}

    <div id="modalAutor" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body px-5">
                    {{-- <button type="button" class="close bg-transparent border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button> --}}

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h3>Insira os dados do autor</h3>
                        </div>
                    </div>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="aviso" style="display: none; padding-bottom: 0px;">

                        <ul id="erros">
                            {{-- Preenchido via JS --}}
                        </ul>

                        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="firstname">Nome</label>
                            <input type="text" name="" id="firstname" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="lastname">Sobrenome</label>
                            <input type="text" name="" id="lastname" class="form-control">
                        </div>
                    </div>
                
                    <div class="row justify-content-between">
                        <div class="col-md-4 mb-1">
                            <button class="btn btn-outline-danger border-0 form-control" data-dismiss="modal" style="cursor: pointer">Cancelar</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-dark form-control" data-dismiss="" id="btn_ok_autor">Salvar</button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="modalLivro" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body px-5">
                    {{-- <button type="button" class="close bg-transparent border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button> --}}

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h3>Insira os dados do livro</h3>
                        </div>
                    </div>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="aviso" style="display: none; padding-bottom: 0px;">

                        <ul id="erros">
                            {{-- Preenchido via JS --}}
                        </ul>

                        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title">Título</label>
                            <input type="text" name="" id="title" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="year">Ano</label>
                            <input type="number" name="" id="year" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pages">Páginas</label>
                            <input type="number" name="" id="pages" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edition">Edição</label>
                            <input type="text" name="" id="edition" class="form-control">
                        </div>
                    </div>
                
                    <div class="row justify-content-between">
                        <div class="col-md-4 mb-1">
                            <button class="btn btn-outline-danger border-0 form-control" data-dismiss="modal" style="cursor: pointer">Cancelar</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-dark form-control" data-dismiss="" id="btn_ok_livro">Salvar</button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {{-- ----- --}}

    {{-- Alert de aviso que deu certo ou errado --}}
    <div id="modalResposta" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body px-5">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h3>
                                <span id="icone_resposta">
                                    {{-- Definido via JS --}}
                                </span>
                                <span id="mensagem_resposta">
                                    {{-- Definido via JS --}}
                                </span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    {{-- =========================================================================== --}}

    {{-- Bootstrap JS --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> --}}

    {{-- Bootbox JS --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script> --}}

    {{-- Sweet Alert --}}
    {{-- tema --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" /> --}}
    {{-- funcionalidade --}}
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="js/sweetalert2.min.js"></script>

    <script src="js/modal.min.js"></script>
    <!--Modal vanilla Kane Cohen-->
    {{-- <script src="js/picoModal.js"></script> --}}
    {{-- <script src="js/nanomodal.js"></script> --}}


    {{-- <div id="custom-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
              <p>One fine body…</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal --> --}}
</body>

</html>
