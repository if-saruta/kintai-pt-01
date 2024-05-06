window.addEventListener('load', () => {
    /**
     * create/updateページ
     */
    const ownerSwitchActive = () => {
        const ownerType = document.getElementById('ownerType');
        const ownerSelect = document.querySelectorAll('.ownerSelect');

        if(ownerType){
            ownerType.addEventListener('change', function() {
                // 選択された<option>要素を取得
                var selectedOption = this.options[this.selectedIndex];
                // 選択された<option>からdata-switch属性の値を取得
                var switchValue = selectedOption.getAttribute('data-switch');

                // 0 : 所属先を表示 1 : 従業員表示
                if(switchValue == 0){
                    ownerSelect[0].classList.remove('close');
                    ownerSelect[0].querySelector('.owner-select').setAttribute('required', '');
                    ownerSelect[1].classList.add('close');
                    ownerSelect[1].querySelector('.owner-select').removeAttribute('required');
                }else{
                    ownerSelect[1].classList.remove('close');
                    ownerSelect[1].querySelector('.owner-select').setAttribute('required', '');
                    ownerSelect[0].classList.add('close');
                    ownerSelect[0].querySelector('.owner-select').removeAttribute('required');
                }
            })

            const initialSelectedOption = ownerType.options[ownerType.selectedIndex];
            const initialInfo = initialSelectedOption.getAttribute('data-switch');
            // 0 : 所属先を表示 1 : 従業員表示
            if(initialInfo == 0){
                ownerSelect[0].classList.remove('close');
                ownerSelect[0].querySelector('.owner-select').setAttribute('required', '');
                ownerSelect[1].classList.add('close');
                ownerSelect[1].querySelector('.owner-select').removeAttribute('required');
            }else{
                ownerSelect[1].classList.remove('close');
                ownerSelect[1].querySelector('.owner-select').setAttribute('required', '');
                ownerSelect[0].classList.add('close');
                ownerSelect[0].querySelector('.owner-select').removeAttribute('required');
            }
        }
    }
    ownerSwitchActive();

    const checkUserVehicle = () => {
        const userVehicleSelect = document.getElementById('userVehicleSelect');
        const form = document.getElementById('form');
        const warningTxt = document.getElementById('vehicleUserWarningTxt');

        if(userVehicleSelect){
            userVehicleSelect.addEventListener('change', function() {
                // 現在選択されている従業員idを格納
                var selectedUserId = parseInt(this.value, 10); //文字列のため、int型にキャスト
                // 配列にIDが含まれているか確認
                if(vehicleUserArray.includes(selectedUserId)){
                    warningTxt.textContent = '既に別の車両で登録されている従業員のため、変更してください';
                    form.dataset.valid = "false";
                } else {
                    warningTxt.textContent = '';
                    form.dataset.valid = "true";
                }
            });

            // フォーム送信時のイベント
            form.addEventListener('submit', function(event) {
                if (form.dataset.valid === "false") {
                    event.preventDefault(); // フォームの送信を阻止
                    alert('この従業員は使用できません。別の従業員を選択してください。');
                }
            });
        }

    }
    checkUserVehicle();

    const modalActive = () => {
        const modal = document.getElementById('vehicleModal');
        const openBtn = document.getElementById('modalOpenBtn');
        const closeBtn = document.querySelectorAll('.modalCloseBtn');

        if(openBtn){
            openBtn.addEventListener('click', () => {
                modal.style.display = 'block';
            })

            for(let i = 0; i < closeBtn.length; i++){
                closeBtn[i].addEventListener('click', () => {
                    modal.style.display = 'none';
                })
            }
        }
    }
    modalActive();


    /**
     * indexページ
     */

    const widthSet = () => {

        const setting = (target) => {
            let maxWidth = 0;

            for(let i = 0; i < target.length; i++){
                let targetWidth = target[i].clientWidth;
                if(maxWidth < targetWidth){
                    maxWidth = targetWidth;
                }
            }

            for(let i = 0; i < target.length; i++){
                target[i].style.width = maxWidth + 'px';
            }
        }

        const number = document.querySelectorAll('.number');
        const employee = document.querySelectorAll('.employee');
        const date = document.querySelectorAll('.date');

        setting(number);
        setting(employee);
        setting(date);
    }
    widthSet();
})
