window.addEventListener('load', () => {

    const modalActive = () => {
        const modal = document.querySelector('.clientModal');
        const clientElem = document.querySelectorAll('.clientElem');
        const closeElem = document.querySelectorAll('.closeElem');
        const setShiftIdElem = document.querySelector('.setShiftIdElem');

        // 新規か既存か選択の挙動制御
        const actionAreaActive = () => {
            const radio = document.querySelectorAll('.witchRadio');
            const actionElem = document.querySelectorAll('.actionElem');

            for(let i = 0; i < radio.length; i++){
                radio[i].addEventListener('change', () => {
                    for(let j = 0; j < actionElem.length; j++){
                        actionElem[j].style.display = 'none';
                    }
                    actionElem[i].style.display = 'flex';
                })
            }
        }

        for(let i = 0; i < clientElem.length; i++){
            clientElem[i].addEventListener('click', () => {
                // モーダル表示
                modal.style.display = 'block';
                actionAreaActive();

                setShiftIdElem.value = clientElem[i].querySelector('.input').value;
            })
        }

        // モーダル閉じる
        for(let i = 0; i < closeElem.length; i++){
            closeElem[i].addEventListener('click', () => {
                modal.style.display = 'none';
            })
        }
    }
    modalActive();

    const shiftProjectModal = () => {
        const activeElem = document.querySelectorAll('.projectNameBox');
        const modal = document.querySelector('.shiftProjectModal');
        const closeBtn = document.querySelectorAll('.shiftProjectModalClose');

        for(let i = 0; i < activeElem.length; i++){
            activeElem[i].addEventListener('click', () => {
                modal.style.display = 'block';
                setTxt(activeElem[i]);
                setValue(activeElem[i]);
                setStyle(activeElem[i]);
            })
        }

        const setTxt = (activeElem) => {
            modal.querySelector('.year').textContent = activeElem.querySelector('.setYear').value;
            modal.querySelector('.month').textContent = activeElem.querySelector('.setMonth').value;
            modal.querySelector('.day').textContent = activeElem.querySelector('.setDay').value;
            modal.querySelector('.projectName').textContent = activeElem.querySelector('.setProjectName').value;
            modal.querySelector('.driverName').textContent = activeElem.querySelector('.setEmployeeName').value;
        }

        const setValue = (activeElem) => {
            modal.querySelector('.setShiftPvId').value = activeElem.querySelector('.setId').value;
            modal.querySelector('.inputProject').value = activeElem.querySelector('.setProjectName').value;
        }

        const setStyle = (activeElem) => {
            if(activeElem.classList.contains('register')){
                // 案件表示
                modal.querySelector('.modalProjectShow').style.display = 'flex';
                // 案件入力非表示
                modal.querySelector('.modalSelectProject').style.display = 'none';
                // 登録ボタン
                modal.querySelector('.saveBtn').style.display = 'none';
            }else{
                // 案件表示
                modal.querySelector('.modalProjectShow').style.display = 'none';
                // 案件入力表示
                modal.querySelector('.modalSelectProject').style.display = 'flex';
                // 登録ボタン
                modal.querySelector('.saveBtn').style.display = 'flex';
            }
        }

        // ラジオ切り替え
        const radioToggle = () => {
            const radio = document.querySelectorAll('.projectRadio');
            const inputProject = document.querySelector('.inputProject');
            const selectProject = document.querySelector('.selectProject');

            for(let i = 0; i < radio.length; i++){
                radio[i].addEventListener('change', () => {
                    if(radio[i].value == 0){
                        selectProject.style.display = 'block';
                        inputProject.style.display = 'none';
                    }else{
                        selectProject.style.display = 'none';
                        inputProject.style.display = 'block';
                    }
                })
            }
        }
        radioToggle();

        for(let i = 0; i < closeBtn.length; i++){
            closeBtn[i].addEventListener('click', () => {
                modal.style.display = 'none';
            })
        }
    }
    shiftProjectModal();

    // ドライバーモーダル
    const shiftDriverModal = () => {
        const activeElem = document.querySelectorAll('.driverNameBox');
        const modal = document.querySelector('.shiftDriverModal');
        const closeBtn = document.querySelectorAll('.shiftDriverModalClose');

        for(let i = 0; i < activeElem.length; i++){
            activeElem[i].addEventListener('click', () => {
                modal.style.display = 'block';
                setTxt(activeElem[i]);
                setValue(activeElem[i]);
            })
        }

        const setTxt = (activeElem) => {
            modal.querySelector('.year').textContent = activeElem.querySelector('.setYear').value;
            modal.querySelector('.month').textContent = activeElem.querySelector('.setMonth').value;
            modal.querySelector('.day').textContent = activeElem.querySelector('.setDay').value;
        }

        const setValue = (activeElem) => {
            modal.querySelector('.setShiftPvId').value = activeElem.querySelector('.setId').value;
            // selectの初期値を取得した名前にセット
            const employeeName = activeElem.querySelector('.setEmployeeName').value;
            const select = modal.querySelector('.employeeSelect');
            // option要素をループ処理で確認
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].text == employeeName) {
                    // テキストが一致するoptionをselectedに設定
                    select.options[i].selected = true;
                    break; // マッチしたらループから抜ける
                }
            }
        }

        for(let i = 0; i < closeBtn.length; i++){
            closeBtn[i].addEventListener('click', () => {
                modal.style.display = 'none';
            })
        }
    }
    shiftDriverModal();

    // 案件未登録モーダル
    const projectChangeUnregisterModal = () => {
        const modal = document.getElementById('projectChangeModal');
        const openElem = document.querySelectorAll('.clientViewElem');
        const closeElem = document.querySelectorAll('.projectChangeModalClose');
        const projectNameViewElem = document.querySelector('.projectNameView');
        const deleteBtn = modal.querySelector('.deleteBtn');
        const warningTxt = modal.querySelector('.warning-txt');

        // シフトデータセット要素
        const setProjectId = modal.querySelector('.setProjectId');
        const setShiftPvId = modal.querySelector('.setShiftPvId');

        for(let i = 0; i < openElem.length; i++){
            openElem[i].addEventListener('click', () => {
                modal.style.display = 'block';
                // 案件名を取得
                let projectName = openElem[i].querySelector('.charter-input').getAttribute( "data-project-name" );
                let shiftPvId = openElem[i].querySelector('.charter-input').getAttribute( "data-shift-id" );
                let projectId = openElem[i].querySelector('.charter-input').getAttribute( "data-project-id" );
                let location = openElem[i].querySelector('.charter-input').getAttribute( "data-location" );
                // 案件名のテキストをセット
                projectNameViewElem.textContent = projectName;
                // シフトデータをセット
                setShiftPvId.value = shiftPvId;
                setProjectId.value = projectId;
                if(location != 2){
                    deleteBtn.style.display = 'none';
                    warningTxt.style.display = 'block';
                }
            })
        }

        for(let i = 0; i < closeElem.length; i++){
            closeElem[i].addEventListener('click', () => {
                modal.style.display = 'none';
                deleteBtn.style.display = 'flex';
                warningTxt.style.display = 'none';
            })
        }
    }
    projectChangeUnregisterModal();

    // カンマの制御
    const commmaActive = () => {
        const inputElem = document.querySelectorAll('.commaInput');
        for(let i = 0; i < inputElem.length; i++){
            inputElem[i].addEventListener('input', function(e) {
                // 入力値からカンマを削除し、数値に変換
                var value = e.target.value.replace(/,/g, '');
                var numberValue = parseInt(value, 10);

                // isNaN関数で数値かどうかをチェック
                if (!isNaN(numberValue)) {
                    // 数値をロケールに応じた文字列に変換（例: "1,234"）
                    e.target.value = numberValue.toLocaleString();
                } else {
                    // 数値でない場合は入力を空にする
                    e.target.value = '';
                }
            })
        }
    }
    commmaActive();

})
