
window.addEventListener('DOMContentLoaded', () => {
        /*
    *************** アコーディオン ***************
    */

    const acc = () => {
        let btn = document.querySelectorAll('.accordionBtn'); //アコーディオンクリック要素
        let ct = document.querySelectorAll('.accordionCt'); //アコーディオン本体
        let angle = document.querySelectorAll('.angle');
        let accWrap = document.querySelectorAll('.acc-wrap');
        let underHeight = [];

        for (let i = 0; i < ct.length; i++) {
            if (ct[i].classList.contains('js-accordion-close')) {
                ct[i].style.height = '0px';
                angle[i].classList.add('js-cross-active');
                accWrap[i].classList.add('acc-wrap-close');
            }
        }
    }
    acc();


    const projectsContainer = document.getElementById('projectsContainer');
    projectsContainer.addEventListener('click', (e) => {
        const btn = e.target.closest('.accordionBtn');
        const inputElem = e.target;

        if(btn !== null && !inputElem.classList.contains('c-input')){
            const parentWrap = btn.closest('.project-info-wrap');//大枠の親要素を取得
            const ct = btn.nextElementSibling;//開閉要素を取得
            const angle = btn.querySelector('.angle');

            let ctHeight = 0;
            // お約束を取り付けたい処理にPromise
            new Promise((resolve) => {
                ct.style.height = 'auto';
                ctHeight = ct.clientHeight;
                ct.style.height = '0px';
                if(!ct.classList.contains('js-accordion-close')){
                    ct.style.height = `${ctHeight}px`;
                }else{
                    ct.style.height = '0px';
                }
                resolve();
            }).then(() => {
                setTimeout(() => {
                    if(ct.classList.contains('js-accordion-close')){
                        ct.style.height = `${ctHeight}px`;
                        ct.classList.remove('js-accordion-close');
                        parentWrap.classList.remove('acc-wrap-close');
                        angle.classList.remove('js-cross-active');
                    }else{
                        ct.style.height = '0px';
                        ct.classList.add('js-accordion-close');
                        parentWrap.classList.add('acc-wrap-close');
                        angle.classList.add('js-cross-active');
                    }
                  }, 1);
            });
        }

        // チャーターのトグルの制御
        const toggle = e.target;
        if(toggle.classList.contains('toggle')){
            toggle.classList.toggle('checked');
            // 親要素を取得
            const listItem = toggle.closest('.list-item');
            // 取得した親要素の上代・ドライバー価格のinputを取得
            const retailInput = listItem.querySelector('.retailInput');
            const salaryInput = listItem.querySelector('.salaryInput');

            if(toggle.classList.contains('checked')){
                toggle.querySelector('.toggle-input').checked = true;
                // 歩合日給と被らないように
                retailInput.classList.add('charterActive');
            }else{
                toggle.querySelector('.toggle-input').checked = false;
                // 歩合日給と被らないように
                retailInput.classList.remove('charterActive');
            }

            if(!retailInput.classList.contains('not-input')){
                if(toggle.classList.contains('checked')){
                    retailInput.querySelector('.c-input').removeAttribute('required');
                    salaryInput.querySelector('.c-input').removeAttribute('required');
                }else{
                    retailInput.querySelector('.c-input').setAttribute('required', '');
                    salaryInput.querySelector('.c-input').setAttribute('required', '');
                }
            }
        }

        // 追加した案件の削除
        const createDeleteBtn = e.target;
        if(createDeleteBtn.classList.contains('create-delete-btn')){
            const projectItem = createDeleteBtn.closest('.list-item');
            projectItem.remove();
        }

        // 歩合が選択時の上代とドライバー価格のinputを制御
        const commission = e.target;
        if(commission.classList.contains('commission')){
            // 親要素を取得
            const listItem = commission.closest('.list-item');
            // 取得した親要素の上代・ドライバー価格のinputを取得
            const retailInput = listItem.querySelector('.retailInput');
            const salaryInput = listItem.querySelector('.salaryInput');

            if(commission.value == 0){
                retailInput.classList.add('not-input');
                salaryInput.classList.add('not-input');
                retailInput.querySelector('.c-input').removeAttribute('required');
                salaryInput.querySelector('.c-input').removeAttribute('required');
            }else{
                retailInput.classList.remove('not-input');
                salaryInput.classList.remove('not-input');
                if(!retailInput.classList.contains('charterActive')){
                    retailInput.querySelector('.c-input').setAttribute('required', '');
                    salaryInput.querySelector('.c-input').setAttribute('required', '');
                }
            }
        }

        // // チャーター選択時の上代とドライバー価格のinputのrequiredを制御
        // const isCharter = e.target;
        // if(isCharter.classList.contains('isCharter')){
        //     console.log(isCharter.value);
        // }

    })


    //   従業員別給与切り替え
    const employeeSalaryChange = () => {
        const tag01 = document.querySelectorAll('.employeeTag01');
        const tag02 = document.querySelectorAll('.employeeTag02');
        const tag03 = document.querySelectorAll('.employeeTag03');
        const list01 = document.querySelectorAll('.employeeList01');
        const list02 = document.querySelectorAll('.employeeList02');
        const list03 = document.querySelectorAll('.employeeList03');

        for(let i = 0; i < tag01.length; i++){
            tag01[i].addEventListener('click', () => {
                tag01[i].classList.add('open');
                tag02[i].classList.remove('open');
                tag03[i].classList.remove('open');
                list01[i].classList.add('employee-list-open');
                list02[i].classList.remove('employee-list-open');
                list03[i].classList.remove('employee-list-open');
            })
            tag02[i].addEventListener('click', () => {
                tag01[i].classList.remove('open');
                tag02[i].classList.add('open');
                tag03[i].classList.remove('open');
                list01[i].classList.remove('employee-list-open');
                list02[i].classList.add('employee-list-open');
                list03[i].classList.remove('employee-list-open');
            })
            tag03[i].addEventListener('click', () => {
                tag01[i].classList.remove('open');
                tag02[i].classList.remove('open');
                tag03[i].classList.add('open');
                list01[i].classList.remove('employee-list-open');
                list02[i].classList.remove('employee-list-open');
                list03[i].classList.add('employee-list-open');
            })
        }
    }
    employeeSalaryChange();

    document.getElementById('addProject').addEventListener('click', () => {
        employeeSalaryChange();
    })





})
