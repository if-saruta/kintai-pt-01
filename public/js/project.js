// window.addEventListener('load', () => {

//     // const createProjectDelete = () => {
//     //      // 削除ボタンに対するイベントリスナーを設定
//     //     document.querySelectorAll('.create-delete-button').forEach(function(button) {
//     //         button.addEventListener('click', function() {
//     //             // 削除ボタンの親要素の「project-info-wrap」を取得
//     //             var projectInfoWrap = button.closest('.project-info-wrap');
//     //             // 親要素をDOMから削除
//     //             if (projectInfoWrap) {
//     //                 projectInfoWrap.remove();
//     //             }
//     //         });
//     //     });
//     // }
//     // createProjectDelete();

//     // document.getElementById('addProject').addEventListener('click', () => {
//     //     createProjectDelete();
//     // })
// })
window.addEventListener('DOMContentLoaded', () => {
        /*
    *************** アコーディオン ***************
    */

    const acc = () => {
        const btn = document.querySelectorAll('.accordionBtn'); //アコーディオンクリック要素
        const ct = document.querySelectorAll('.accordionCt'); //アコーディオン本体
        const angle = document.querySelectorAll('.angle');
        const accWrap = document.querySelectorAll('.acc-wrap');
        let underHeight = [];

        const updateHeights = () => {
        for (let i = 0; i < ct.length; i++) {
            ct[i].style.height = 'auto';
            underHeight[i] = ct[i].clientHeight;
            ct[i].style.height = '0px';
            if (!ct[i].classList.contains('js-accordion-close')) {
            ct[i].style.height = `${underHeight[i]}px`;
            } else {
            ct[i].style.height = '0px';
            }
        }
        };

        const accordionActive = () => {
            updateHeights();

            window.addEventListener('resize', () => {
                updateHeights();
            });

            for (let i = 0; i < ct.length; i++) {
                btn[i].addEventListener('click', () => {
                if (ct[i].classList.contains('js-accordion-close')) {
                    ct[i].style.height = `${underHeight[i]}px`;
                    ct[i].classList.remove('js-accordion-close');
                    accWrap[i].classList.remove('acc-wrap-close');
                    angle[i].classList.remove('js-cross-active');
                } else {
                    ct[i].style.height = '0px';
                    ct[i].classList.add('js-accordion-close');
                    accWrap[i].classList.add('acc-wrap-close');
                    angle[i].classList.add('js-cross-active');
                }
                });
            }
        };

        accordionActive();

    }
    acc();

    document.querySelectorAll('.toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
          // クラス 'checked' の追加・削除を切り替える
          toggle.classList.toggle('checked');

          // input要素のchecked状態を切り替える
          var input = toggle.querySelector('input[name="check"]');
          if(input) {
            input.checked = !input.checked;
          }
        });
      });

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
        acc();
        employeeSalaryChange();

    })

})
