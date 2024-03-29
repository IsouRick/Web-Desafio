<?php

require "tarefa.model.php";
require "tarefa.service.php";
require "conexao.php";

$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;
if ($acao == 'inserir') {
    $tarefa = new Tarefa();
    $tarefa->__set('tarefa', $_POST['tarefa']);
    $tarefa->__set('horario_lembrete', $_POST['horario_lembrete']);
    $tarefa->__set('categoria', $_POST['categoria']);

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->inserir();

    header('Location:todas_tarefas.php');

} else if ($acao == 'recuperar') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperar();

} else if ($acao == 'atualizar') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_POST['id'])
        ->__set('tarefa', $_POST['tarefa']);
    $tarefa->__set('horario_lembrete', $_POST['horario_lembrete']);

    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->atualizar();
    header('Location: index.php');

} else if ($acao == 'remover') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id']);
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->remover();
    header('Location: todas_tarefas.php');

} else if ($acao == 'recuperarTarefasPendentes') {
    $conexao = new Conexao();
    $tarefa = new Tarefa();

    $tarefa->__set('id_status', 1); // 1 é o ID do status para "pendente"

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperarTarefasPendentes();

} else if ($acao == 'marcarRealizadas') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    $tarefa->__set('id', $_GET['id']);
    $tarefa->__set('id_status', 2);

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->marcarRealizada();
    header('location: todas_tarefas.php');

} else if ($acao == 'ordenarTarefas') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->ordenarTarefas();

} else if ($acao == 'filtrar') {
    $filtro = $_GET['filtro'];
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);

    if ($filtro == 'pendentes') {
        $tarefas = $tarefaService->recuperarTarefasPendentes();
    } else if ($filtro == 'concluidas') {
        $tarefas = $tarefaService->recuperarTarefasConcluidas();
    } else if ($filtro == 'arquivadas') {
        $tarefas = $tarefaService->recuperarTarefasArquivadas();
    } else {
        $tarefas = $tarefaService->recuperar();
    }

} else if ($acao == 'categoria') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);

    $filtro = $_GET['filtro'];

    $tarefas = $tarefaService->recuperarTarefasCategoria($filtro);

} else if ($acao == 'verificarTarefas') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);
    $vencido = $tarefaService->verificarTarefa();
    if ($vencido) {
        echo "<script>alert('Você possui tarefas vencendo hoje ou que vencerão em breve. Verifique suas tarefas para não perder nenhum prazo!');</script>";
    }

} else if ($acao == 'arquivar') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id']);
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->arquivarTarefa();

    header('Location: todas_tarefas.php');
}