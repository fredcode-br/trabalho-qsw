const revisarBtn = document.getElementById('revisarInscricao');
const confirmarBtn = document.getElementById('confirmarInscricao');

function limparSelecao(disciplina) {
    var checkboxes = document.getElementsByName('selectedClasses[]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = false;
    }
    verificarSelecao();
}

function verificarSelecao() {
    const checkboxes = document.querySelectorAll('input[name="selectedClasses[]"]');
    const peloMenosUmSelecionado = Array.from(checkboxes).some(checkbox => checkbox.checked);

    try {
        revisarBtn.disabled = !peloMenosUmSelecionado;
        confirmarBtn.disabled = !peloMenosUmSelecionado;
    } catch (error) {
        console.error(error);
    }
}

function limitarSelecao(disciplina) {
    const checkboxes = document.querySelectorAll('input[name="selectedClasses[]"][data-disciplina="' + disciplina + '"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                // Desabilitar outras checkboxes da mesma disciplina
                checkboxes.forEach(function (otherCheckbox) {
                    if (otherCheckbox !== checkbox) {
                        otherCheckbox.disabled = true;
                    }
                });
            } else {
                // Habilitar todas as checkboxes da mesma disciplina
                checkboxes.forEach(function (otherCheckbox) {
                    otherCheckbox.disabled = false;
                });
            }
        });
    });
}

function obterTurmasSelecionadas() {
    const turmasSelecionadas = [];

    document.querySelectorAll('input[name="selectedClasses[]"]:checked').forEach(function (checkbox) {
        const disciplina = checkbox.getAttribute('data-disciplina');
        const turmaId = checkbox.value;
        turmasSelecionadas.push({ disciplina, turmaId });
    });

    return turmasSelecionadas;
}


function submitForm() {
    const turmasSelecionadas = obterTurmasSelecionadas();
    inscrever(turmasSelecionadas);
}

function inscrever(turmasSelecionadas) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'enrollment/check', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            
            const textModal = document.getElementById('modal-text');
            const textWaitList = document.getElementById('wait-list-text');
            const btnWaitList = document.querySelector('.btn-wait-list')

            var myModal = new bootstrap.Modal(document.getElementById('myModal'));
            
            for (var i = 0; i < resp.length; i++) {
                console.log( resp[i])
                if (resp[i].enrolled !== '' && resp[i].enrolled !== undefined ) {
                    textModal.innerText = resp[i].enrolled;
                    myModal.show();
                    break;
                }
                if (resp[i].conflict !== '' && resp[i].conflict !== undefined ) {
                    console.log( resp[i].conflict)
                    textModal.innerText = resp[i].conflict;
                    myModal.show();
                    break;
                }
                
                if (resp[i].status !== '') {
                    textModal.innerText = resp[i].status;
                    textWaitList.classList.replace('d-none', 'd-block');
                    btnWaitList.classList.replace('d-none', 'd-block');
                    btnWaitList.setAttribute('id', resp[i].turma_id)
                    // Dai aqui vc insere na lista de espera quando apertar em sim e mostra a colocação na lista
                    myModal.show();
                }

                // Precisa rever a lógica pq antes de fazer qualquer inserção tem de ver se ja esta
                // inscrito em alguma das matérias selecionadas ou se teve algum choque
                // acho q o melhor é criar dois loops, um pra verificar isso e se passar nele dai roda
                // outro pra fazer as inserções

                // Dps disso é só fazer a pagina de revisão. Ela é mais simples pq é só listar oq selecionou
                // e no botão confirmar usar a mesma lógica daqui
                

            }        
        }
    };

    var dados = JSON.stringify({ turmas: turmasSelecionadas });
    xhr.send(dados);
}

try {
    [revisarBtn, confirmarBtn].forEach(function (btn) {
        btn.addEventListener('click', function (event) {
            event.preventDefault();
        });
    });

    document.querySelectorAll('input[name="selectedClasses[]"]').forEach(function (checkbox) {
        const disciplina = checkbox.getAttribute('data-disciplina');
        limitarSelecao(disciplina);
        checkbox.addEventListener('change', verificarSelecao);
    });

    verificarSelecao();
} catch (error) {
    console.error(error);
}


