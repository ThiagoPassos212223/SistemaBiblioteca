<?php

class HomeController {
    private PublicacaoService $publicacaoService;
    private AutorService $autorService;
    private ClassificacaoService $classificacaoService;
    private TipoPublicacaoService $tipoPublicacaoService;
    private DivulgacaoService $divulgacaoService;
    private ConvidadoService $convidadoService;

    public function __construct(
        PublicacaoService $publicacaoService,
        AutorService $autorService,
        ClassificacaoService $classificacaoService,
        TipoPublicacaoService $tipoPublicacaoService,
        DivulgacaoService $divulgacaoService,
        ConvidadoService $convidadoService
    ) {
        $this->publicacaoService = $publicacaoService;
        $this->autorService = $autorService;
        $this->classificacaoService = $classificacaoService;
        $this->tipoPublicacaoService = $tipoPublicacaoService;
        $this->divulgacaoService = $divulgacaoService;
        $this->convidadoService = $convidadoService;
    }

    public function index(): void {
        // array
        $totais = [
            'publicacoes' => count($this->publicacaoService->listarTodos()),
            'autores' => count($this->autorService->listarTodos()),
            'classificacoes' => count($this->classificacaoService->listarTodos()),
            'tipos' => count($this->tipoPublicacaoService->listarTodos()),
            'divulgacoes' => count($this->divulgacaoService->listarTodos()),
            'convidados' => count($this->convidadoService->listarTodos()),
        ];
        require __DIR__ . '/../viewer/index.html';
    }
}
