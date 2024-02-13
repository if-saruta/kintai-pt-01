window.addEventListener('load', () => {

    const allowanceModal = () => {
        const targetArea = document.querySelectorAll('.allowance-area');
        const modal = document.getElementById('allowance-modal');
        const btn = document.querySelector('.allowance-modal-btn');
        const bg = document.querySelector('.allowanceModalBg');

        // 最後の入力保存する
        var setInput = null;
        // 手当の数
        let count = 1;

        // プロジェクトごとにモーダルを作成
        for(let i = 0; i < targetArea.length; i++){
            targetArea[i].addEventListener('click', () => {
                const names = targetArea[i].querySelectorAll('.allowanceName');
                const amounts = targetArea[i].querySelectorAll('.amount');
                const select = document.getElementById('allowanceSelect');
                setInput = targetArea[i].querySelector('.allowance-input');

                modal.style.display = 'block';

                for(let j = 0; j < names.length; j++){
                    var option = document.createElement("option");
                    option.text = names[j].value
                    option.value = amounts[j].value
                    select.appendChild(option)
                    count++
                }
            })
        }

        // selectとinputの切り替え
        const radio = document.querySelectorAll('.allowanceRadio');
        const radioRalation = document.querySelectorAll('.radioRalation');

        for(let h = 0; h < radio.length; h++){
            radio[h].addEventListener('change', () => {
                for(let i = 0; i < radioRalation.length; i++){
                    radioRalation[i].style.display = 'none';
                }
                radioRalation[h].style.display = 'block';
            })
        }

        // モーダルを初期化し選択されたデータを渡す
        btn.addEventListener('click', () => {
            const select = document.getElementById('allowanceSelect');
            const input = document.getElementById('allowanceInput');
            const radio = document.querySelectorAll('.allowanceRadio');

            new Promise((resolve) => {
                if(radio[0].checked){
                    const setAmount = select.value;
                    setInput.value = setAmount;
                    modal.style.display = 'none';
                }else{
                    const setAmount = input.value;
                    setInput.value = setAmount;
                    modal.style.display = 'none';
                }
                resolve();
            }).then(() => {
                for(let k = 1; k < count; k++){
                    select.remove(1);
                }
                input.value = null;
            })
        })

        bg.addEventListener('click', () => {
            const select = document.getElementById('allowanceSelect');
            const input = document.getElementById('allowanceInput');

            // select・inputの値を初期化
            input.value = null;
            for(let k = 1; k < count; k++){
                select.remove(1);
            }
            // モーダルを閉じる
            modal.style.display = 'none';
        })

    }
    allowanceModal();

    const vehicleModal = () => {
        const mainVehicle = document.querySelectorAll('.mainVehicle');
        const modal = document.getElementById('vehicleModal');
        const btn = document.querySelector('.vehicle-modal-btn');
        const bg = document.querySelector('.vehicleModalBg');

        // 最後の入力保存する
        var setInput = null;

        for(let i = 0; i < mainVehicle.length; i++){
            mainVehicle[i].addEventListener('click', () => {
                modal.style.display = 'block';
                setInput = mainVehicle[i];
            })
        }

        // selectとinputの切り替え
        const radio = document.querySelectorAll('.vehicleRadio');
        const radioRalation = document.querySelectorAll('.vehicleRadioRalation');

        for(let h = 0; h < radio.length; h++){
            radio[h].addEventListener('change', () => {
                for(let i = 0; i < radioRalation.length; i++){
                    radioRalation[i].style.display = 'none';
                }
                radioRalation[h].style.display = 'block';
            })
        }

        // モーダルを初期化し選択されたデータを渡す
        btn.addEventListener('click', () => {
            const select = document.getElementById('vehicleSelect');
            const input = document.getElementById('vehicleInput');
            const radio = document.querySelectorAll('.vehicleRadio');

            new Promise((resolve) => {
                if(radio[0].checked){
                    const setAmount = select.value;
                    setInput.value = setAmount;
                    modal.style.display = 'none';
                }else{
                    const setAmount = input.value;
                    setInput.value = setAmount;
                    modal.style.display = 'none';
                }
                resolve();
            }).then(() => {
                input.value = null;
            })
        })

        bg.addEventListener('click', () => {
            const select = document.getElementById('vehicleSelect');
            const input = document.getElementById('vehicleInput');
            // inputを初期化
            input.value = null;
            // モーダルを閉じる
            modal.style.display = 'none';
        })
    }
    vehicleModal();

    const itemActiveCheck = () => {
        const amountCheck = document.getElementById('amountCheck');
        const expressCheck = document.getElementById('expresswayCheck');
        const parkingCheck = document.getElementById('parkingCheck');
        const vehicleCheck = document.getElementById('vehicleCheck');
        const overtimeCheck = document.getElementById('overtimeCheck');

        const amountRow = document.querySelectorAll('.amountRow');
        const expressRow = document.querySelectorAll('.expresswayRow');
        const parkingRow = document.querySelectorAll('.parkingRow');
        const vehicleRow = document.querySelectorAll('.vehicleRow');
        const overtimeRow = document.querySelectorAll('.overtimeRow');

        const activeRow = (check, row) => {
            check.addEventListener('change', () => {
                if(!check.checked){
                    for(let i = 0; i < row.length; i++){
                        row[i].style.display = 'none';
                    }
                }else{
                    for(let i = 0; i < row.length; i++){
                        row[i].style.display = 'flex';
                    }
                }
            })
        }
        activeRow(amountCheck, amountRow);
        activeRow(expressCheck, expressRow);
        activeRow(parkingCheck, parkingRow);
        activeRow(vehicleCheck, vehicleRow);
        activeRow(overtimeCheck, overtimeRow);

    }
    itemActiveCheck();
})
