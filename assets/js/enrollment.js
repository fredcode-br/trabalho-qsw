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

function listarTurmasSelecionadas(){
    const turmasSelecionadas = [];

    document.querySelectorAll('#revisaoTurmas tbody tr').forEach(function (row) {
        const disciplina = row.querySelector('td:nth-child(1)').innerText;
        const turmaId = row.id;
        turmasSelecionadas.push({ disciplina, turmaId });
    });

    return turmasSelecionadas;
}


function submitForm() {
    const turmasSelecionadas = obterTurmasSelecionadas();
    checkInscricao(turmasSelecionadas);
}

function submitReviewForm(){
    const turmasSelecionadas = listarTurmasSelecionadas();
    checkInscricao(turmasSelecionadas);
}

function inscrever(turma_id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/trabalho-qsw/enrollment/inscribe', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            return resp;
        }
    };

    var dados = JSON.stringify({ turmaId: turma_id });
    xhr.send(dados);
}

function entrarListaEspera() {
    const textWaitList = document.getElementById('wait-list-text');
    const btnWaitList = document.querySelector('.btn-wait-list')
    turma_id = btnWaitList.id;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/trabalho-qsw/enrollment/waitlist', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            textWaitList.innerText = "Posição na lista: "+resp;
            btnWaitList.disabled = true;
            return resp;
        }
    };

    var dados = JSON.stringify({ turmaId: turma_id });
    xhr.send(dados);
}


function checkInscricao(turmasSelecionadas) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/trabalho-qsw/enrollment/check', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            
            const textModal = document.getElementById('modal-text');
            const textWaitList = document.getElementById('wait-list-text');
            const btnWaitList = document.querySelector('.btn-wait-list')
            let inscrito = false;
            let choque = false;
            textWaitList.classList.replace('d-block', 'd-none');
            btnWaitList.classList.replace('d-block', 'd-none');
            var myModal = new bootstrap.Modal(document.getElementById('myModal'));
            
            for (var i = 0; i < resp.length; i++) {
                if (resp[i].enrolled !== '' && resp[i].enrolled !== undefined ) {
                    textModal.innerText = resp[i].enrolled;
                    myModal.show();
                    inscrito = true;
                    break;
                }
                if (resp[i].conflict !== '' && resp[i].conflict !== undefined ) {
                    textModal.innerText = resp[i].conflict;
                    myModal.show();
                    choque = true;
                    break;
                }
            } 
            if (!inscrito && !choque) {
                var modalShown = false;
            
                for (var i = 0; i < resp.length; i++) {
                    if (resp[i].status !== '') {
                        myModal.show();
                        modalShown = true;
            
                        if (resp[i].wait) {
                            textModal.innerText = resp[i].status + " Você já está na lista de espera.";
                        } else {
                            textModal.innerText = resp[i].status;
                            textWaitList.classList.replace('d-none', 'd-block');
                            btnWaitList.classList.replace('d-none', 'd-block');
                            btnWaitList.setAttribute('id', resp[i].turma_id)
                            btnWaitList.disabled = false;
            
                            if (i === resp.length - 1 && !myModal._isShown) {
                                
                                // criar lógica para redirecionar somente se o modal estiver fechado e for
                                // a ultima iteração

                                success();
                            }
                        }
                    } else {
                        inscrever(resp[i].turma_id);
                    }
                }
            }   
        }
    };

    var dados = JSON.stringify({ turmas: turmasSelecionadas });
    xhr.send(dados);
}

function success(){
    window.location.href = 'http://localhost/trabalho-qsw/enrollment/success';
}

function excluirInscricao(inscricaoId){
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/trabalho-qsw/enrollment/unsubscribe', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            console.log(resp)
            location.reload();
        }
    };

    var dados = JSON.stringify({ inscricaoId: inscricaoId });
    xhr.send(dados);
}

try {
    confirmarBtn.addEventListener('click', function (event) {
        event.preventDefault();
    });
  

    function review() {
        document.getElementById('enrollmentForm').action = 'enrollment/review';
        document.getElementById('enrollmentForm').submit();
    }

    document.querySelectorAll('input[name="selectedClasses[]"]').forEach(function (checkbox) {
        const disciplina = checkbox.getAttribute('data-disciplina');
        limitarSelecao(disciplina);
        checkbox.addEventListener('change', verificarSelecao);
    });

    verificarSelecao();
} catch (error) {
    console.error(error);
}


