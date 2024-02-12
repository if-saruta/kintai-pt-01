
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


        const toggle = e.target;
        if(toggle.classList.contains('toggle')){
            toggle.classList.toggle('checked');
            if(toggle.classList.contains('checked')){
                toggle.querySelector('.toggle-input').checked = true;
            }else{
                toggle.querySelector('.toggle-input').checked = false;
            }
        }

    })

    const toggle = () => {
        const toggle = document.querySelectorAll('.toggle');

        for(let i = 0; i < toggle.length; i++){
            toggle[i].addEventListener('click', () => {
                toggle[i].classList.toggle('checked');
            })
        }
        // document.querySelectorAll('.toggle').forEach(function(toggle) {

        //     toggle.addEventListener('click', function() {
        //       // クラス 'checked' の追加・削除を切り替える
        //       toggle.classList.toggle('checked');

        //       // input要素のchecked状態を切り替える
        //       var input = toggle.querySelector('.toggle-input');
        //       if(toggle.classList.contains('checked')) {
        //         input.checked = true;
        //       }else{
        //         input.checked = false;
        //       }
        //     });
        //   });
    }
    // toggle();

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
