<?php

class TarefaService
{
    private $conexao;
    private $tarefa;

    public function __construct(Conexao $conexao, Tarefa $tarefa)
    {
        $this->conexao = $conexao->conectar();
        $this->tarefa = $tarefa;
    }

    public function inserir()
    {
        //c - create
        $query = 'INSERT INTO tb_tarefas(tarefa, horario_lembrete, categoria) VALUES (:tarefa, :horario_lembrete, :categoria)';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        $stmt->bindValue(':horario_lembrete', $this->tarefa->__get('horario_lembrete'));
        $stmt->bindValue(':categoria', $this->tarefa->__get('categoria'));
        $stmt->execute();
    }

    public function recuperar()
    {
        //R - read
        $query = '
            select
                t.id, s.status, t.tarefa
            from
                tb_tarefas as t
                left join tb_status as s on (t.id_status = s.id) 
        ';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function atualizar()
    {
        //U - update
        $query = "
        update 
            tb_tarefas 
        set 
            tarefa = :tarefa,
            horario_lembrete = :horario_lembrete
        where 
            id = :id";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        $stmt->bindValue(':horario_lembrete', $this->tarefa->__get('horario_lembrete'));
        $stmt->bindValue(':id', $this->tarefa->__get('id'));
        return $stmt->execute();
    }

    public function remover()
    {
        //D - delete
        $query = '
        delete from 
            tb_tarefas
        where
            id= :id 
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id', $this->tarefa->__get('id'));
        $stmt->execute();
    }

    public function marcarRealizada()
    {
        $query = "
        update 
            tb_tarefas 
        set 
            id_status = ? 
        where 
            id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(1, $this->tarefa->__get('id_status'));
        $stmt->bindValue(2, $this->tarefa->__get('id'));
        return $stmt->execute();
    }

    public function recuperarTarefasPendentes()
    {
        $query = '
        select
            t.id,
            s.status,
            t.tarefa
        from
            tb_tarefas as t
            left join tb_status as s on(t.id_status = s.id)
        where
            t.id_status = :id_status
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id_status', 1); // 1 é o ID do status para "pendente"
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ordenarTarefas()
    {
        $orderBy = $_GET["atribute"];
        $query = "SELECT t.id, s.status, t.tarefa FROM tb_tarefas as t LEFT JOIN tb_status as s ON (t.id_status = s.id) ORDER BY $orderBy";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperarTarefasConcluidas()
    {
        $query = '
        select
            t.id,
            s.status,
            t.tarefa
        from
            tb_tarefas as t
            left join tb_status as s on(t.id_status = s.id)
        where
            t.id_status = :id_status
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id_status', 2); // 2 é o ID do status para "concluída"
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperarTarefasCategoria($filtro)
    {
        $query = '
        select
            t.id,
            s.status,
            t.tarefa
        from
            tb_tarefas as t
            left join tb_status as s on(t.id_status = s.id)
        where
            t.categoria = :categoria
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':categoria', $filtro); // filtro ID da categoria para "1" "2" ou "3"
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function verificarTarefa()
    {
        $hoje = date('Y-m-d');

        $query = "SELECT * FROM tb_tarefas WHERE horario_lembrete = :hoje";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindParam(':hoje', $hoje);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function arquivarTarefa()
    {
        $query = "
        UPDATE 
            tb_tarefas 
        SET 
            id_status = 3
        WHERE 
            id = :id
        ";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id', $this->tarefa->__get('id'));
        return $stmt->execute();
    }

    public function recuperarTarefasArquivadas()
    {
        $query = '
        select
            t.id,
            s.status,
            t.tarefa
        from
            tb_tarefas as t
            left join tb_status as s on(t.id_status = s.id)
        where
            t.id_status = :id_status
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id_status', 3); // 3 é o ID do status para "arquivada"
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
