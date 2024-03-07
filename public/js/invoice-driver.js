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
        const targetArea = document.querySelectorAll('.allowance-area');
        const modal = document.getElementById('allowance-modal');
        const btn = document.querySelector('.allowance-modal-btn');
        const closeBtn = document.querySelectorAll('.modalClose');

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

        for(let i = 0; i< closeBtn.length; i++){
            closeBtn[i].addEventListener('click', () => {
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
                <th><input type="text" name="otherName[]" value=""></th>
                <td><input type="text" name="otherAmount[]" value=""><div class="row-delete-btn"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
            `;

            bodyElem.appendChild(tr);
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
                <th><input type="text" name="otherCostName[]" value=""></th>
                <td><input type="text" name="otherCostAmont[]" value=""><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
            `;

            bodyElem.appendChild(tr);
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

})
