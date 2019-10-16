<?php

use \Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/recuperar-senha', function () {
    return view('auth.passwords.email');
});

Route::get('/exibir-inscricoes', function () {
    return view('layouts.participante');
});

Route::get('/inscricao/fazer-inscricao', function () {
    return view('layouts.inscricao');
})->name('minhasinscricoes');

Auth::routes();

foreach (\App\Resource::all() as $resource) {
    $route = Route::match($resource->method, $resource->uri, $resource->controller . '@' . $resource->action)->name($resource->nome);

    if (!empty($resource->middleware)) {
        foreach (explode(',', $resource->middleware) as $middleware) {
            $route->middleware($middleware);
        }
    }
}
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/eventochangeano/{id}', 'HomeController@alteraAnoSistema')->name('eventochangeano');
Route::get('/cadastrar', 'Publico\ParticipanteController@create')->name('cadastrar');
Route::get('/inscricao/atividades-inscricao', 'HomeController@listarAtividadesParaInscricao');
Route::get('/inscricao/minhas-inscricoes', 'HomeController@listarInscricoesParticipante');
Route::post('/inscricao/pesquisar-inscricoes', 'HomeController@pesquisar');
Route::get('/inscricao/listar-inscricoes-gerenciar', 'HomeController@listarInscricoesGerenciar');
Route::post('/inscricao/pesquisar-inscricoes-nome', 'HomeController@pesquisaNome')->name('pesquisar-inscricoes-nome');
Route::post('/inscricao/pesquisar-inscricoes-cpf', 'HomeController@pesquisaCPF')->name('pesquisar-inscricoes-cpf');
Route::get('/atividade/gerenciar-monitor', 'MonitorController@home')->name('gerenciar-monitor');
Route::get('/atividade/monitor/carregar-monitorias', 'MonitorController@carregarMonitorias');
Route::get('/atividade/monitor/vincular-monitor', 'MonitorController@vincularMonitor');
Route::get('/atividade/monitor/remover-monitoria', 'MonitorController@removerMonitoria');
Route::get('/atividade/presenca/gerenciar-presenca', 'AtividadeController@carregarAtividades');
Route::get('/atividade/presenca/carregar-participantes', 'AtividadeController@carregarParticipantes');
Route::get('/atividade/presenca/setar-presenca', 'AtividadeController@setarPresenca');
Route::get('/atividade/presenca/busca-participante', 'AtividadeController@buscaParticipante');
Route::get('/atividade/presenca/setar-presenca-code', 'AtividadeController@setarPresencaCode');
Route::get('/atividade/sorteio/realizar-sorteio', 'AtividadeController@realizarSorteio');
Route::get('/atividade/presenca/gerenciar', 'AtividadeController@home')->name('gerenciar-presenca');
Route::get('/atividade/sorteio', 'AtividadeController@sorteio')->name('gerenciar-sorteio');
Route::post('/cadastrarevento/{id}', 'EventoUpdateController@update')->name('eventoUpdate');
Route::get('/participanteinfo/{participanteId}/{eventoId}', 'ParticipanteInfoController@show');
Route::POST('/participanteinfo/coordenadoraccess', 'ParticipanteInfoController@presencaCoordernador')->name('coordernador-setpresenca');
Route::get('/gerarpdf/{id?}', 'PdfGenerator@getInformations')->name('gerarpdf');
Route::resource('pdf', 'PdfGenerator');
Route::get('/pdfdelete/{id?}', 'PdfGenerator@deleta')->name('deletar-da-lista-pdf');