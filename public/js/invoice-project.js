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
    }
    settingModalActive();

    // テーブルの幅を指定
    const tableWidthSet = () => {
        const table = document.getElementById('calendarTable');
        const txtBoxLength = document.querySelectorAll('.txtBox').length;
        const numberBoxLength = document.querySelectorAll('.numberBox').length;
        let totalWidth = (txtBoxLength * 80) + (numberBoxLength * 50);

        table.style.width = totalWidth + 600 + 'px';
    }
    tableWidthSet();

    const calendarClmActive = () => {
        const checkBox = document.querySelectorAll('.viewClmCheck');

        for(let i = 0; i < checkBox.length; i++){
            const className = checkBox[i].value;
            const targetElem = document.querySelectorAll(`.${className}`);

            if(checkBox[i].getAttribute('data-check') == 'one'){
                if(checkBox[i].checked){
                    for(let j = 0; j < targetElem.length; j++){
                        targetElem[j].classList.add('clm-none');
                    }
                }else{
                    for(let j = 0; j < targetElem.length; j++){
                        targetElem[j].classList.remove('clm-none');
                    }
                }
            }else{
                if(checkBox[i].checked){
                    for(let j = 0; j < targetElem.length; j++){
                        targetElem[j].classList.add('all-clm-none');
                    }
                }else{
                    for(let j = 0; j < targetElem.length; j++){
                        targetElem[j].classList.remove('all-clm-none');
                    }
                }
            }
        }

        const colspanCalc = () => {

            var rightHead = document.querySelectorAll('.rightHead');
            var coHead = document.querySelectorAll('.co-head');

            const projectCountElem = document.getElementById('projectCount');
            const projectCount = projectCountElem.value;
            const clmHead = document.querySelectorAll('.clmHead');

            const companyCountElem = document.getElementById('companyCount');
            const companyCount = companyCountElem.value;
            const coClmHead = document.querySelectorAll('.coClmHead');

            let coCount = 0;
            let count = 0;

            if(projectCount > 1 || companyCount > 1){
                for(let i = 0; i < clmHead.length; i++){
                    if(!clmHead[i].classList.contains('clm-none') && !clmHead[i].classList.contains('all-clm-none')){
                        count++;
                    }
                }
                for(let i = 0; i < coClmHead.length; i++){
                    if(!coClmHead[i].classList.contains('all-clm-none')){
                        coCount++;
                    }
                }

                for(let i = 0; i < rightHead.length; i++){
                    //  現在のcolspanの値を取得（文字列として返されるので、整数に変換する）
                    var currentColspan = parseInt(rightHead[i].getAttribute("colspan"));
                    var newColspan = count / projectCount;
                    rightHead[i].setAttribute("colspan", newColspan.toString());
                    if(newColspan == 0){
                        rightHead[i].classList.add('clm-none');
                    }
                }
                for(let i = 0; i < coHead.length; i++){
                    var newColspan = coCount / projectCount;
                    coHead[i].setAttribute("colspan", newColspan.toString());
                    if(newColspan == 0){
                        coHead[i].classList.add('all-clm-none');
                    }
                }
            }

        }
        colspanCalc();
    }
    calendarClmActive();

    const setCheckByChecked = () => {
        const setInvoiceElem = document.querySelectorAll('.setDisplayActiveInvoice');
        const setCalendarElem = document.querySelectorAll('.setDisplayActiveByCalendar');
        const hasCheck = document.querySelectorAll('.hasDisplayValue');

        for(let i = 0; i < hasCheck.length; i++){
            if(hasCheck[i].checked){
                setInvoiceElem[i].value = 0;
                setCalendarElem[i].value = 0;
            }
        }

        const hasCoCheck = document.querySelectorAll('.hasDisplayCoValue');
        const setCoCalendarElem = document.querySelectorAll('.setArrowCompanyByCalendar');
        const setCoInvoiceElem = document.querySelectorAll('.setArrowCompanyByInvoice');
        for(let i = 0; i < hasCoCheck.length; i++){
            if(!hasCoCheck[i].checked){
                setCoCalendarElem[i].value = hasCoCheck[i].getAttribute('data-company-id');
                setCoInvoiceElem[i].value = hasCoCheck[i].getAttribute('data-company-id');
            }
        }
    }
    setCheckByChecked();

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
})
