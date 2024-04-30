window.addEventListener('load', () => {

    const settingModalActive = () => {
        const modal = document.getElementById('settingModalWrap');
        const openBtn = document.getElementById('settingOpen');
        const closeBtn = document.querySelectorAll('.settingCloseBtn');

        openBtn.addEventListener('click', () => {
            modal.style.display = 'block';
        })

        for(let i = 0; i < closeBtn.length; i++){
            closeBtn[i].addEventListener('click', () => {
                modal.style.display = 'none';
            })
        }

        // form送信の制御
        modal.addEventListener('submit', function(event) {
            var hasDisplayCoValue = document.querySelectorAll('.hasDisplayCoValue');
            var projectCheckBox = document.querySelectorAll('.projectCheckBox');
            let isPermissionByCompany = false;
            let isPermissionByProject = false;

            // それぞれのcheckBoxを監視
            for(let i = 0; i < hasDisplayCoValue.length; i++){
                if(hasDisplayCoValue[i].checked){
                    isPermissionByCompany = true;
                }
            }
            for(let i = 0; i < projectCheckBox.length; i++){
                if(projectCheckBox[i].checked){
                    isPermissionByProject = true;
                }
            }

            // formの制御
            if(!isPermissionByCompany){
                event.preventDefault();
                alert('所属先はいずれか一つ以上選択してください');
            }else if(!isPermissionByProject){
                event.preventDefault();
                alert('案件はいずれか一つ以上選択してください');
            }
        })
    }
    settingModalActive();

    // テーブルの幅を指定
    const tableWidthSet = () => {
        const table = document.getElementById('calendarTable');
        const txtBoxLength = document.querySelectorAll('.txtBox').length;
        const numberBoxLength = document.querySelectorAll('.numberBox').length;
        let totalWidth = (txtBoxLength * 110) + (numberBoxLength * 80);

        table.style.width = totalWidth + 'px';
    }
    tableWidthSet();

    const cellHeightSet = () => {
        const tableRow = document.querySelector('.tableRow');
        const cellHeight = document.querySelectorAll('.cellHeight')
        let height = tableRow.clientHeight;

        for(let i = 0; i < cellHeight.length; i++){
            cellHeight[i].style.height = height + 'px';
        }

    }
    cellHeightSet();


    // 削除モーダル
    const shiftDeleteModal = () => {
        const activeElem = document.querySelectorAll('.activeElem');
        const modal = document.querySelector('.shiftDeleteModal');
        const closeBtn = document.querySelectorAll('.shiftDeleteModalClose');

        for(let i = 0; i < activeElem.length; i++){
            activeElem[i].addEventListener('click', () => {
                // モーダル表示
                modal.style.display = 'block';
                // クリックされた要素渡す
                shiftInfoActive(activeElem[i]);
            })
        }

        const shiftInfoActive = (activeElem) => {
            const values = activeElem.querySelector('.hasShiftInfo');
            modalTxtSet(values);
            modalValueSet(values)
        }

        // モーダル内のテキストを変更
        const modalTxtSet = (values) => {
            modal.querySelector('.year').textContent = values.getAttribute('data-shiftPv-year');
            modal.querySelector('.month').textContent = values.getAttribute('data-shiftPv-month');
            modal.querySelector('.day').textContent = values.getAttribute('data-shiftPv-day');
            modal.querySelector('.driverName').textContent = values.getAttribute('data-shiftPv-employee-name');
            modal.querySelector('.projectName').textContent = values.getAttribute('data-shiftPv-project-name');
        }
        // form送信用のデータをセット
        const modalValueSet = (values) => {
            modal.querySelector('.setShiftPvId').value = values.getAttribute('data-shiftPv-id');
        }

        // 閉じる処理
        for(let i = 0; i < closeBtn.length; i++){
            closeBtn[i].addEventListener('click', () => {
                modal.style.display = 'none';
            })
        }
    }
    shiftDeleteModal()

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
                    e.target.value = 0;
                }
            })
        }
    }
    commmaActive();

})
