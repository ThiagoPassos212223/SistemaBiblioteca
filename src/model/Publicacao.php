<?php

// Esta classe representa uma publicacao cadastrada no acervo.
// Ela guarda título, data, descrição e referências relacionadas.
// Os métodos permitem consultar e atualizar todas as informações.
class Publicacao implements JsonSerializable {
    private ?int $id;
    private ?int $idTipoPublicacao;
    private ?int $idDivulgacao;
    private ?int $idAutor;
    private string $titulo;
    private string $dataPublicacao;
    private string $descricao;

    public function __construct(
        ?int $id,
        string $titulo,
        string $dataPublicacao,
        string $descricao,
        ?int $idTipoPublicacao = null,
        ?int $idDivulgacao = null,
        ?int $idAutor = null
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->dataPublicacao = $dataPublicacao;
        $this->descricao = $descricao;
        $this->idTipoPublicacao = $idTipoPublicacao;
        $this->idDivulgacao = $idDivulgacao;
        $this->idAutor = $idAutor;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getIdTipoPublicacao(): ?int {
        return $this->idTipoPublicacao;
    }

    public function setIdTipoPublicacao(?int $idTipoPublicacao): void {
        $this->idTipoPublicacao = $idTipoPublicacao;
    }

    public function getIdDivulgacao(): ?int {
        return $this->idDivulgacao;
    }

    public function setIdDivulgacao(?int $idDivulgacao): void {
        $this->idDivulgacao = $idDivulgacao;
    }

    public function getIdAutor(): ?int {
        return $this->idAutor;
    }

    public function setIdAutor(?int $idAutor): void {
        $this->idAutor = $idAutor;
    }

    public function getTitulo(): string {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void {
        $this->titulo = $titulo;
    }

    public function getDataPublicacao(): string {
        return $this->dataPublicacao;
    }

    public function setDataPublicacao(string $dataPublicacao): void {
        $this->dataPublicacao = $dataPublicacao;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }

    public function jsonSerialize(): array {
        return [
            'idPUBLICACAO' => $this->id,
            'idTIPO_PUBLICACAO' => $this->idTipoPublicacao,
            'idDIVULGACAO' => $this->idDivulgacao,
            'idAUTOR' => $this->idAutor,
            'titulo' => $this->titulo,
            'data_publicacao' => $this->dataPublicacao,
            'descricao' => $this->descricao
        ];
    }
}
