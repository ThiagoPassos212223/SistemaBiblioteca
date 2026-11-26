
document.addEventListener('DOMContentLoaded', () => {
    const limitePadraoTextarea = 500;
    let alterouAlgumDado = false;
    let enviandoFormulario = false;

    document.querySelectorAll('form').forEach((form) => {
        const campos = Array.from(
            form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="reset"]), select, textarea')
        );

        // Limpa espaços duplicados ao sair do campo
        form.querySelectorAll('input[type="text"], input:not([type])').forEach((input) => {
            input.addEventListener('blur', () => {
                input.value = input.value.replace(/\s+/g, ' ').trim();
            });
        });

        // 2. Contador automático para qualquer textarea
        form.querySelectorAll('textarea').forEach((textarea) => {
            const limite = Number(textarea.dataset.maxLength) || limitePadraoTextarea;
            const contador = document.createElement('small');
            contador.style.display = 'block';
            contador.style.marginTop = '4px';
            textarea.insertAdjacentElement('afterend', contador);

            function atualizarContador() {
                const atual = textarea.value.length;
                contador.textContent = `${atual} / ${limite}`;
                contador.style.color = atual > limite ? '#dc2626' : '#64748b';
            }

            atualizarContador();
            textarea.addEventListener('input', atualizarContador);

            // 3. Ctrl+Enter dentro de qualquer textarea
            textarea.addEventListener('keydown', (event) => {
                if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
                    form.requestSubmit();
                }
            });
        });

        // procura por modificações em algum dado do formulário
        campos.forEach((campo) => {
            campo.addEventListener('input', () => { alterouAlgumDado = true; });
            campo.addEventListener('change', () => { alterouAlgumDado = true; });
        });

        // impede o envio com apenas espaços em campos obrigatórios ou acima do limite
        form.addEventListener('submit', (event) => {
            for (const campo of campos) {
                if (campo.tagName === 'INPUT' && (campo.type === 'text' || !campo.type)) {
                    campo.value = campo.value.replace(/\s+/g, ' ').trim();
                }

                if (campo.required && !campo.value.trim()) {
                    event.preventDefault();
                    campo.focus();
                    alert('Preencha este campo com um valor válido (sem apenas espaços).');
                    return;
                }

                if (campo.tagName === 'TEXTAREA') {
                    const limite = Number(campo.dataset.maxLength) || limitePadraoTextarea;
                    if (campo.value.length > limite) {
                        event.preventDefault();
                        campo.focus();
                        alert(`O texto deve ter no máximo ${limite} caracteres.`);
                        return;
                    }
                }
            }

            enviandoFormulario = true;
        });
    });

    // Aviso ao sair da pagina, quando as informações ainda não foram salvas
    window.addEventListener('beforeunload', (event) => {
        if (alterouAlgumDado && !enviandoFormulario) {
            event.preventDefault();
            event.returnValue = '';
        }
    });
});
