window.addEventListener('load', () => {

    const formProject = () => {
        const project = document.querySelectorAll('.js-project');
        const modal = document.querySelectorAll('.js-modal');

        for(let i = 0; i < project.length; i++){
            project[i].addEventListener('click', () => {
                const bg = modal[i].querySelector('.form-bg');
                const switchBtn = modal[i].querySelectorAll('.projectSwitch');
                const inputWrap = modal[i].querySelectorAll('.edit-project-input');
                const input = modal[i].querySelectorAll('.edit-input');
                const form = modal[i].querySelector('.form');
                const warning = modal[i].querySelector('.warning');

                // モーダル開閉
                if(!modal[i].classList.contains('edit-form-open')){
                    modal[i].classList.add('edit-form-open');
                }
                bg.addEventListener('click', () => {
                    if(modal[i].classList.contains('edit-form-open')){
                        modal[i].classList.remove('edit-form-open');
                    }
                })

                // 案件表示切り替え
                for(let j = 0; j < switchBtn.length; j++){
                    switchBtn[j].addEventListener('click', () => {
                        for(let k = 0; k < input.length; k++){
                            inputWrap[k].classList.remove('radio-open');
                        }
                        if(switchBtn[j].checked){
                            inputWrap[j].classList.add('radio-open');
                        }
                    })
                }

                // バリデーション
                form.addEventListener('submit', function(event) {
                    for(let j = 0; j < switchBtn.length; j++){
                        if(switchBtn[j].checked){
                            if(input[j].value == ""){
                                warning.style.display = 'block';
                                event.preventDefault(); // 条件に基づいて送信をキャンセル
                            }
                        }
                    }
                })
                // バリデーション解除
                for(let j = 0; j < input.length; j++){
                    input[j].addEventListener('focusout', () => {
                        if(!input[j].value == ""){
                            warning.style.display = 'none';
                        }else{
                            warning.style.display = 'block';
                        }
                    })
                }

            })
        }
    }
    formProject();

    const createShift = () => {
        const btn = document.querySelectorAll('.js-create-shift-btn');
        const modal = document.querySelectorAll('.js-create-modal');
        // modal[0].style.display = 'block';

        for(let i = 0; i < btn.length; i++){
            btn[i].addEventListener('click', () => {
                const bg = modal[i].querySelector('.js-create-shift-modal-bg');

                // モーダル開閉
                modal[i].style.display = 'block';
                bg.addEventListener('click', () => {
                    modal[i].style.display = 'none';
                })
            })
        }
    }
    // createShift();

})
