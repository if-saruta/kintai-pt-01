window.addEventListener('load', () => {

    const createEditModalActive = () => {
        const modal = document.getElementById('shiftModal');
        const shiftActive = document.querySelectorAll('.shiftActive');
        const closeBtn = document.querySelectorAll('.shiftModalClose');

        for(let i = 0; i < shiftActive.length; i++){
            shiftActive[i].addEventListener('click', () => {
                modal.style.display = 'block'; //モーダルを開く
                // 新規作成
                if(shiftActive[i].classList.contains('createShift')){
                    createShift(shiftActive[i]);
                    changeModalStyle(shiftActive[i]);
                    setValue(shiftActive[i]);
                }else{
                    editShift(shiftActive[i]);
                    changeModalStyle(shiftActive[i]);
                    setValue(shiftActive[i]);
                }
            })
        }

        // 案件作成
        const createShift = (shiftActive) => {
            // クリックした行の日付を取得
            const date = shiftActive.querySelector('.shiftDate');
            // モーダル内の日付のテキストを書き換え
            setDateTxt(date)
            // モダール内の情報のテキストを書き換え
            setModalTxt(shiftActive);
        }
        // 案件編集
        const editShift = (shiftActive) => {
            // クリックした行の日付を取得
            const date = shiftActive.querySelector('.shiftDate');
            // モーダル内の日付のテキストを書き換え
            setDateTxt(date)
            // モダール内の情報のテキストを書き換え
            setModalTxt(shiftActive);
            // シフトの情報を保持してる要素を取得
            const values = shiftActive.querySelector('.shiftValues');
        }

        // モダールの日付の表示のテキストをセット
        const setDateTxt = (date) => {
            const year = date.getAttribute('data-year');
            const month = date.getAttribute('data-month');
            const day = date.getAttribute('data-day');
            const setDateValue = document.querySelector('.setDateValue');

            document.querySelector('.setYearTxt').textContent = year;
            document.querySelector('.setMonthTxt').textContent = month;
            document.querySelector('.setDayTxt').textContent = day;
            // 日付の値もセット
            setDateValue.value = date.value;
        }

        // モーダル内のテキストをセット
        const setModalTxt = (shiftActive) => {
            const title = document.querySelector('.shiftModalTitle');
            const timeOfPart = document.querySelector('.shiftTimeOfPart');
            const waningTxt = document.querySelector('.shiftModalWarningTxt');
            // シフトの情報を保持してる要素を取得
            const values = shiftActive.querySelector('.shiftValues');

            if(shiftActive.classList.contains('createShift')){
                title.textContent = '案件作成';
                timeOfPart.textContent = '';
                waningTxt.style.display = 'none';
            }else{
                title.textContent = '案件編集';
                // 午前また午後のテキスト
                if(values.getAttribute('data-time-of-part') == 0){
                    timeOfPart.textContent = '午前';
                }else{
                    timeOfPart.textContent = '午後';
                }
                // 未登録案件の警告文
                if(values.getAttribute('data-project-id') == ''){
                    waningTxt.style.display = 'block';
                    document.querySelector('.unProjectTxt').textContent = values.getAttribute('data-unproject-name');
                }else{
                    waningTxt.style.display = 'none';
                }
            }
        }

        // 作成と編集でスタイルを変更
        const changeModalStyle = (shiftActive) => {
            const deleteBtn = document.querySelector('.shiftModalDelete');
            const timeOfPartRadio = document.querySelector('.shiftTimeOfPartRadioBox');

            if(shiftActive.classList.contains('createShift')){
                deleteBtn.style.display = 'none';
                timeOfPartRadio.style.display = 'flex';
            }else{
                deleteBtn.style.display = 'flex';
                timeOfPartRadio.style.display = 'none';
            }
        }

        // シフトのデータをセット
        const setValue = (shiftActive) => {
            const setIdElem = document.querySelector('.setShiftPvId');
            const setRetailPrice = document.querySelector('.setRetailPrice');
            const setDriverPrice = document.querySelector('.setDriverPrice');
            const projectSelect = document.querySelector('.projectSelect');
            const vehicleSelect = document.querySelector('.vehicleSelect');

            const selectSelected = (select, id) => {
                for(let i = 0; i < select.options.length; i++){
                    if(select.options[i].value === id){
                        select.options[i].selected = true;
                        break; // マッチしたらループを抜ける
                    }
                }
            }

            if(shiftActive.classList.contains('createShift')){
                // 値を空にする
                setIdElem.value = '';
                setRetailPrice.value = '';
                setDriverPrice.value = '';
                projectSelect.options[0].selected = true;
                vehicleSelect.options[0].selected = true;
            }else{
                // シフトの情報を保持してる要素を取得
                const values = shiftActive.querySelector('.shiftValues');
                setIdElem.value = values.getAttribute('data-id');
                setRetailPrice.value = values.getAttribute('data-retail-price');
                setDriverPrice.value = values.getAttribute('data-driver-price');
                const projectId = values.getAttribute('data-project-id');
                const vehicleId = values.getAttribute('data-vehicle-id');
                selectSelected(projectSelect, projectId);
                selectSelected(vehicleSelect, vehicleId);
            }
        }


        // モーダルを閉じる
        for(let i = 0; i < closeBtn.length; i++){
            closeBtn[i].addEventListener('click', () => {
                modal.style.display = 'none';
            })
        }
    }
    createEditModalActive()

    const allowanceModal = () => {
        // 要素
        const modal = document.getElementById('allowanceModal');
        const targetElem = document.querySelectorAll('.allowance-input');
        const closeElem = document.querySelectorAll('.modalClose');
        const registerdWrap = document.getElementById('registerdWrap');
        const createWrap = document.getElementById('createWrap');
        const selectBox = document.getElementById('selectBox');
        const createAllowanceForm = document.getElementById('createAllowanceForm');
        const switchAllowances = document.querySelectorAll('.switchAllowance');
        const createBtn = document.getElementById('allowanceCreateBtn');
        const prevBtn = document.querySelector('.prevBtn');
        const saveBtn = document.querySelector('.saveBtn');
        const allowanceCreate = document.getElementById('allowanceCreate');
        const selectAddBtn = document.getElementById('selectAddBtn');
        // データ
        const createAllowancePorjectId = document.getElementById('createAllowancePorjectId');
        const shiftPvIdElem = modal.querySelectorAll('.shiftPvId');
        const projectIdElem = modal.querySelector('.projectId');
        // セレクトを挿入する親要素を取得
        const allowanceList = document.getElementById('allowanceList');
        // バナー
        const succsessBunner = document.getElementById('succsessBunner');
        const errorBunner = document.getElementById('errorBunner');

        let projectId = null;
        let shiftPvId = null;

        // ロードが必要か
        let isLoad = false;

        for(let i = 0; i < targetElem.length; i++){
            targetElem[i].addEventListener('click', () => {
                // モーダル表示
                modal.style.display = 'block';

                // 案件のidを取得・セット
                projectId = targetElem[i].getAttribute('data-project-id');
                projectIdElem.value = projectId;

                // シフトのIDを取得
                shiftPvId = targetElem[i].getAttribute('data-shift-pv-id');
                shiftPvIdElem.forEach(elem => {
                    elem.value = shiftPvId;
                })

                createList();
                succsessBunner.classList.remove('bunner-animation');
                errorBunner.classList.remove('bunner-animation');
            })
        }

        // 登録済みの手当を取得
        const fetchAllowance = async () => {
            try {
                const response = await fetch(`/fetch-data/${projectId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const allowances = await response.json();
                return allowances;
            } catch (error) {
                console.error('Error:', error);
            }
        };
        // 登録済みの手当を表示
        const createList = async () => {
            let allowances = await fetchAllowance();
            allowanceList.innerHTML = '';
            allowances.forEach(allowance => {
                allowance.shift_allowance.forEach(shiftPv => {
                    // 操作しているシフトに手当が登録されているか
                    if(shiftPv['id'] == shiftPvId){
                        const newDiv = document.createElement('div');
                        newDiv.className = 'registered-allownce__list__item';

                        newDiv.innerHTML = `
                            <p class="allowance-name">${allowance['name']}</p>
                            <p class="delete allowanceDeleteBtn" data-allowance-id="${allowance['id']}">削除</p>
                        `;
                        allowanceList.appendChild(newDiv);
                    }
                });
            });
        };

        // 手当追加
        createBtn.addEventListener('click', () => {
            registerdWrap.style.display = 'none';
            createWrap.style.display = 'block';
            addAllowance();
            switchRegisterdOrNew();
        })
        // セレクトタグの作成
        const addAllowance = async () => {
            // 手当を取得
            let allowances = await fetchAllowance();
            // selectを作成
            const newSelect = document.createElement('select');
            newSelect.className = 'c-select allowance-select';
            newSelect.name = 'allowanceId';
            // option初期値
            const allowanceOption = document.createElement('option');
            allowanceOption.value = '';
            allowanceOption.text = '選択してください';
            newSelect.appendChild(allowanceOption);

            allowances.forEach(allowance => {
                const allowanceOption = document.createElement('option');
                allowanceOption.value = allowance['id'];
                allowanceOption.text = allowance['name'];
                newSelect.appendChild(allowanceOption);
            })
            selectBox.appendChild(newSelect);
        }
        // 既存か新規かを切り替え
        const switchRegisterdOrNew = () => {
            switchAllowances.forEach(switchBtn => {
                switchBtn.addEventListener('click', () => {
                    if(switchBtn.value == "registered"){
                        selectBox.style.display = 'block';
                        createAllowanceForm.style.display = 'none';
                    }else{
                        selectBox.style.display = 'none';
                        createAllowanceForm.style.display = 'flex';
                    }
                })
            })
        }

        // 手当登録
        saveBtn.addEventListener('click', () => {
            switchAllowances.forEach(switchBtn => {
                if(switchBtn.checked){
                    if(switchBtn.value == "registered"){
                        const form = document.getElementById('updateAllowanceForm');
                        let formData = new FormData(form);
                        succsessBunner.classList.remove('bunner-animation');
                        errorBunner.classList.remove('bunner-animation');

                        // 通信
                        fetch('/allowance-update', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            succsessBunner.classList.add('bunner-animation');
                            backToFirstView();
                            isLoad = true;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // alert('Failed to create shift.');
                            errorBunner.classList.add('bunner-animation');
                        });
                    }else{ //手当新規作成

                        // formごとデータを取得
                        let allowanceCreateForm = document.getElementById('createAllowanceForm');
                        let formData = new FormData(allowanceCreateForm);
                        // バナーをリセット
                        succsessBunner.classList.remove('bunner-animation');
                        errorBunner.classList.remove('bunner-animation');

                        // 通信
                        fetch('/create-allowance', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            // console.log('Success:', data);
                            // alert('Shift created successfully!');
                            allowanceCreateForm.reset();
                            succsessBunner.classList.add('bunner-animation');
                            backToFirstView();
                            isLoad = true;
                        })
                        .catch(error => {
                            // console.error('Error:', error);
                            // alert('Failed to create shift.');
                            errorBunner.classList.add('bunner-animation');
                        });
                    }
                }
            })
        })

        // 手当削除の処理
        registerdWrap.addEventListener('click', function (e) {
            let deleteBtn = e.target;

            if(deleteBtn.classList.contains('allowanceDeleteBtn')){
                const confirmation = confirm('本当に削除しますか？');
                if(confirmation){
                    let allowanceId = deleteBtn.getAttribute('data-allowance-id');

                    fetch(`/allowance-delete/${allowanceId}/${shiftPvId}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        // alert('Shift created successfully!');
                        succsessBunner.classList.add('bunner-animation');
                        backToFirstView();
                        isLoad = true;
                    })
                    .catch(error => {
                        // console.error('Error:', error);
                        // alert('Failed to create shift.');
                        errorBunner.classList.add('bunner-animation');
                    });
                }
            }
        });

        const backToFirstView = () => {
            // 表示画面切り替え
            registerdWrap.style.display = 'block';
            createWrap.style.display = 'none';
            // セレクトボックスリセット
            selectBox.innerHTML = '';
            // ラジオボタンリセット
            switchAllowances[0].checked = true;
            switchAllowances[1].checked = false;
            selectBox.style.display = 'block';
            createAllowanceForm.style.display = 'none';
            // 登録済み手当を取得
            createList();
        }
        // 前へ戻る
        prevBtn.addEventListener('click', () => {
            backToFirstView();
        })


        // モーダル閉じる時の処理
        for(let i = 0; i < closeElem.length; i++){
            closeElem[i].addEventListener('click', () => {
                // モーダル非表示
                modal.style.display = 'none';
                allowanceList.innerHTML = '';
                // ページをリロード
                if(isLoad){
                    location.reload();
                }
            })
        }

    }
    allowanceModal();

    const vehicleModal = () => {
        const mainVehicle = document.querySelectorAll('.mainVehicle');
        const modal = document.getElementById('vehicleModal');
        const btn = document.querySelector('.vehicle-modal-btn');
        const closeBtn = document.querySelectorAll('.modalClose');

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

        for(let i = 0; i < closeBtn.length; i++){
            closeBtn[i].addEventListener('click', () => {
                const select = document.getElementById('vehicleSelect');
                const input = document.getElementById('vehicleInput');
                // inputを初期化
                input.value = null;
                // モーダルを閉じる
                modal.style.display = 'none';
            })
        }
    }
    vehicleModal();

    // 残業代モーダル
    const overtimeModal = () => {
        const modal = document.getElementById('overtimeModal');
        const openElem = document.querySelectorAll('.overtimeOpenTarget');
        const closeElem = document.querySelectorAll('.overtimeCloseTarget');
        const saveBtn = document.getElementById('overtimeSaveBtn');
        const modalInput = document.getElementById('overtimeValueInput');
        const overtimeType = document.querySelectorAll('.overtimeType');

        const succsessBunner = document.getElementById('succsessBunner');
        const errorBunner = document.getElementById('errorBunner');

        const addComma = (argInput) => {
            let value = argInput.value.replace(/,/g, ''); // カンマを一旦削除

            // 値が数値かどうかを確認
            if (!isNaN(value) && value.trim() !== '') {
                let formattedValue = Number(value).toLocaleString();
                argInput.value = formattedValue;
            } else {
                argInput.value = ''; // 非数値の場合は空にする
            }
        }

        let input = null
        // モダール表示
        for(let i = 0; i < openElem.length; i++){
            openElem[i].addEventListener('click', () => {
                modal.style.display = 'block';
                input = openElem[i];
                setValue(input);
            })

            // 残業代が金額なのであればカンマをつける
            if(openElem[i].getAttribute('data-over-time-type') == 'amount'){
                addComma(openElem[i]);
            }
        }

        // 保存されている値をセット
        const setValue = (argInput) => {
            modal.querySelector('.shiftPvId').value = input.getAttribute('data-shift-pv-id');
            // 金額
            modalInput.value = argInput.value;
            // タイプ
            let overtimeTypeValue = argInput.getAttribute('data-over-time-type');
            // タイプが設定されていれば、付随するradioにchecked
            if(overtimeTypeValue != ''){
                overtimeType.forEach(type => {
                    if(type.value == overtimeTypeValue){
                        type.checked = true;
                    }else{
                        type.checked = false;
                    }
                });
            }
        }

        // 金額を入力ならカンマをつける
        modalInput.addEventListener('input', () => {
            overtimeType.forEach(type => {
                if (type.checked && type.value === 'amount') {
                    addComma(modalInput);
                }
            });
        });

        // Ajaxでデータを保存
        const sendValue = () => {
            saveBtn.addEventListener('click', () => {
                let shiftPvId = input.getAttribute('data-shift-pv-id');

                // 入力された値を取得
                let amount = modalInput.value;
                let typeValue = '';
                overtimeType.forEach(type => {
                    if(type.checked){
                        typeValue = type.value;
                    }
                });
                // json形式に変換
                const data = {
                    id : shiftPvId,
                    type : typeValue,
                    amount : amount
                }
                // Ajax通信
                fetch('/over-time-update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    input.value = amount;
                    input.dataset.overTimeType = typeValue;
                    // resultバナーを表示
                    succsessBunner.classList.add('bunner-animation');
                    setTimeout(() => {
                        succsessBunner.classList.remove('bunner-animation');
                    }, 2000);
                })
                .catch((error) => {
                    // resultバナーを表示
                    errorBunner.classList.add('bunner-animation');
                    setTimeout(() => {
                        errorBunner.classList.remove('bunner-animation');
                    }, 2000);

                    // console.log(error);
                });
            });
        }
        // sendValue();

        // モダール非表示
        for(let i = 0; i < closeElem.length; i++){
            closeElem[i].addEventListener('click', () => {
                modal.style.display = 'none';
                modalInput.value = '';
            })
        }
    }
    overtimeModal();

    const itemActiveCheck = () => {
        const amountCheck = document.getElementById('narrowAmountCheck');
        const expressCheck = document.getElementById('narrowExpresswayCheck');
        const parkingCheck = document.getElementById('narrowParkingCheck');
        const vehicleCheck = document.getElementById('narrowVehicleCheck');
        const overtimeCheck = document.getElementById('narrowOvertimeCheck');
        const allowanceCheck = document.getElementById('narrowAllowanceCheck');

        const amountRow = document.querySelectorAll('.amountRow');
        const allowanceRow = document.querySelectorAll('.allowanceRow')
        const expressRow = document.querySelectorAll('.expresswayRow');
        const parkingRow = document.querySelectorAll('.parkingRow');
        const vehicleRow = document.querySelectorAll('.vehicleRow');
        const overtimeRow = document.querySelectorAll('.overtimeRow');

        const activeRow = (check, row) => {
            if(check.checked){
                for(let i = 0; i < row.length; i++){
                    row[i].classList.add('display-none');
                }
            }else{
                for(let i = 0; i < row.length; i++){
                    row[i].classList.remove('display-none');
                }
            }
        }
        activeRow(amountCheck, amountRow);
        activeRow(allowanceCheck, allowanceRow)
        activeRow(expressCheck, expressRow);
        activeRow(parkingCheck, parkingRow);
        activeRow(vehicleCheck, vehicleRow);
        activeRow(overtimeCheck, overtimeRow);

        const setAmountCheck = document.querySelectorAll('.setAmountCheck');
        const setAllowanceCheck = document.querySelectorAll('.setAllowanceCheck');
        const setExpresswayCheck = document.querySelectorAll('.setExpresswayCheck');
        const setParkingCheck = document.querySelectorAll('.setParkingCheck');
        const setVehicleCheck = document.querySelectorAll('.setVehicleCheck');
        const setOvertimeCheck = document.querySelectorAll('.setOvertimeCheck');

        const setValue = (check, setElem) => {
            if(check.checked){
                for(let i = 0; i < setElem.length; i++){
                    setElem[i].value = 1;
                }
            }else{
                for(let i = 0; i < setElem.length; i++){
                    setElem[i].value = null;
                }
            }
        }
        setValue(amountCheck, setAmountCheck);
        setValue(allowanceCheck, setAllowanceCheck)
        setValue(expressCheck, setExpresswayCheck);
        setValue(parkingCheck, setParkingCheck);
        setValue(vehicleCheck, setVehicleCheck);
        setValue(overtimeCheck, setOvertimeCheck);

        const getRowCount = document.querySelector('.row-select');
        const setRowCount = document.querySelector('.setRowCount');
        setRowCount.value = getRowCount.value;
    }
    itemActiveCheck();

    const textAreaSet = () => {
        const getTextArea = document.getElementById('getTextArea');
        const setTextArea = document.getElementById('setTextArea');

        getTextArea.addEventListener('change', () => {
            setTextArea.value = getTextArea.value;
        })
    }
    // textAreaSet();

    // 設定モダール
    const settingModalActive = () => {
        const modalwrap = document.getElementById('settingModalWrap');
        const openBtn = document.getElementById('settingBtn');
        const closeBtn = document.querySelectorAll('.settingCloseBtn');

        // モーダルオープン
        openBtn.addEventListener('click', () => {
            modalwrap.style.display = 'block';
        });
        // モーダルクローズ
        for(let i = 0; i < closeBtn.length; i++){
            closeBtn[i].addEventListener('click', () => {
                modalwrap.style.display = 'none';
            })
        }
    }
    settingModalActive();

    // 集計表の給与テーブルの行追加の制御
    const salaryTableRowActive = () => {
        const bodyElem = document.getElementById('totalSalaryTableBody');
        const addBtn = document.getElementById('totalSalaryAddBtn');

        addBtn.addEventListener('click', () => {
            let tr = document.createElement('tr');
            tr.classList.add('info-table-row');
            tr.innerHTML = `
                <th><input type="text" name="otherName[]" value="" class=""></th>
                <td><input type="text" name="otherAmount[]" value="" class="commaInput"><div class="row-delete-btn"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
            `;

            bodyElem.appendChild(tr);
            commmaActive();
        })
    }
    salaryTableRowActive();
    // 集計表の費用テーブルの行追加の制御
    const costTableRowActive = () => {
        const bodyElem = document.getElementById('totalCostTableBody');
        const addBtn = document.getElementById('totalCostAddBtn');

        addBtn.addEventListener('click', () => {
            let tr = document.createElement('tr');
            tr.classList.add('info-table-row');
            tr.innerHTML = `
                <th><input type="text" name="otherCostName[]" value="" class=""></th>
                <td><input type="text" name="otherCostAmont[]" value="" class="commaInput"><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
            `;

            bodyElem.appendChild(tr);
            commmaActive();
        })
    }
    costTableRowActive();

    // 集計表の行の削除の制御
    const infoTableRowDelete = () => {
        const parentElem = document.getElementById('infoTableParent');

        parentElem.addEventListener('click', (e) => {
            if(e.target.classList.contains('delete-btn-target')){
                e.target.closest('.info-table-row').remove();
            }
        })
    }
    infoTableRowDelete()

    // カレンダーの送信ボタンを連携
    const calendarFormSubmit = () => {
        const btn = document.getElementById('calendarSaveBtn');
        const form = document.getElementById('calendarForm');
        btn.addEventListener('click', () => {
            form.submit();
        })
    }
    calendarFormSubmit();

    const changeFormSubmit = () => {
        const form = document.getElementById('form');
        const submits = document.querySelectorAll('.formSubmit');

        for(let i = 0; i < submits.length; i++){
            submits[i].addEventListener('click', () => {
                if(submits[i].classList.contains('invoice-form')){
                    form.action = 'driver-edit-pdf';
                    form.submit();
                }else{
                    form.action = 'driver-calendar-pdf';
                    form.submit();
                }
            })
        }
    }
    changeFormSubmit();

    // 送信ボタンを連携
    const formSubmit = () => {
        const clickBtn = document.getElementById('invoiceBtnClickElem');
        const gearingBtn = document.getElementById('gearingBtn');

        clickBtn.addEventListener('click', () => {
            gearingBtn.click();
        })
    }
    // formSubmit();

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
