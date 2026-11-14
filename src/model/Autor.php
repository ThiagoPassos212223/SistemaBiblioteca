<?php

class Autor implements JsonSerializable {
    private ?int $id;
    private ?int $idClassificacao;
    private string $nome;
    private string $descricao;

    public function __construct(?int $id, string $nome, string $descricao, ?int $idClassificacao = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->idClassificacao = $idClassificacao;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getIdClassificacao(): ?int {
        return $this->idClassificacao;
    }

    public function setIdClassificacao(?int $idClassificacao): void {
        $this->idClassificacao = $idClassificacao;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }

    public function jsonSerialize(): array {
        return [
            'idAUTOR' => $this->id,
            'idCLASSIFICACAO' => $this->idClassificacao,
            'nome' => $this->nome,
            'descricao' => $this->descricao
        ];
    }
}
