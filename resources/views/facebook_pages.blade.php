@extends('layouts.app')

@section('content')
<!-- MODAL -->
<div class="modal fade" id="facebookFormModal" tabindex="-1" role="dialog" aria-labelledby="sendMessageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendMessageLabel">Postar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
			</div>
			<!-- FORMULÁRIO -->
            <div class="modal-body">
                <form method="POST" action="{{route('facebook')}}" id="facebookForm">
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Mensagem:</label>
                        <textarea class="form-control" id="message" name="message"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Imagem <b>(somente arquivos de imagem)</b>:</label>
                        <input type="file" id="image" name="image" accept="image/*">
					</div>
					<div class="form-group">
						<label for="scheduling" class="col-form-label">Agendar <b>(no mínimo 15 min a partir da data/hora atual)</b>: </label>
						<input class="form-control" type="datetime-local" id="scheduling" name="scheduling">
					</div>
					<div id="actionAlertContainer" class="alert" style="visibility: hidden" role="alert"></div>
                    <div class="modal-footer">
						<input type="hidden" name="page_token" id="page_token" value="">
						<input type="hidden" name="page_id" id="page_id" value="">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" id="btnSubmitFacebookForm" class="btn btn-primary">Enviar</button>
                        {{csrf_field()}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- TABELA -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Páginas do Facebook</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>Nome</th>
                            <th class="text-center">Ações</th>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                            <tr>
                                <td>{{$page['name']}}</td>
                                <td class="text-center">
                                    <button id="btnModal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#facebookFormModal" data-page_token="{{$page['access_token']}}" data-page_id="{{$page['id']}}">
                                        <i class="fas fa-paper-plane"></i>
                                        Publicar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
		//VARIÁVEIS
		const form =  document.getElementById('facebookForm');
		var btnModal = document.getElementById('btnModal');
		var actionAlertContainer = document.getElementById('actionAlertContainer');
        var btnSubmitFacebookForm = document.getElementById('btnSubmitFacebookForm');
        
        btnSubmitFacebookForm.addEventListener('click', function(){
            actionAlertContainer.classList.remove('alert-danger');
            actionAlertContainer.classList.remove('alert-success');
            actionAlertContainer.classList.add('alert-warning');
            actionAlertContainer.innerHTML = '<b>Aguarde...</b>';
            actionAlertContainer.style.visibility = 'visible';
        });
        
		//BOTÃO PARA ABERTURA DO MODAL
		btnModal.addEventListener('click', function(e) {
            document.getElementById('page_token').value = this.dataset.page_token;
            document.getElementById('page_id').value = this.dataset.page_id;
        });

		//EVENTO DE SUBMISSÃO DO FORMULÁRIO
       	form.addEventListener('submit', function(e) {
            e.preventDefault();

			const data = new FormData(this);

			//REQUISIÇÃO ASSÍNCRONA UTILIZANDO A FETCH API
			fetch('http://localhost:8000/facebook', {
				method: 'POST',
				body: data
			})
			.then(function(response){
				//TRATATIVA DE RETORNO PARA EXIBIR MENSAGEM DE SUCESSO
				if (response.status == 200) {
					form.reset();
					actionAlertContainer.classList.remove('alert-danger');
					actionAlertContainer.classList.remove('alert-warning');
					actionAlertContainer.classList.add('alert-success');
                    actionAlertContainer.innerHTML = 'Ação realizada com sucesso.';
                    actionAlertContainer.style.visibility = 'visible';
					setTimeout(function(){
						actionAlertContainer.style.visibility = 'hidden';
					}, 3000);
				}
				//TRATATIVA DE RETORNO PARA EXIBIR MENSAGEM DE ALERTA
                if (response.status == 406) {
					form.reset();
					actionAlertContainer.classList.remove('alert-success');
					actionAlertContainer.classList.remove('alert-warning');
					actionAlertContainer.classList.add('alert-danger');
                    actionAlertContainer.innerHTML = 'Ação não realizada';
                    actionAlertContainer.style.visibility = 'visible';
					setTimeout(function(){
						actionAlertContainer.style.visibility = 'hidden';
					}, 3000);
				}
				
				return response.json();
			})
			.catch(function(error){
				console.log(error);
			})
        });
    }, false);
</script>
@endsection 