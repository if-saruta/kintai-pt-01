window.addEventListener('DOMContentLoaded', () => {

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

    const loadCommmaActive = () => {
        const inputElem = document.querySelectorAll('.commaInput');
        for(let i = 0; i < inputElem.length; i++){
            // 入力値からカンマを削除し、数値に変換
            var value = inputElem[i].value.replace(/,/g, '');
            var numberValue = parseInt(value, 10);

            // isNaN関数で数値かどうかをチェック
            if (!isNaN(numberValue)) {
                // 数値をロケールに応じた文字列に変換（例: "1,234"）
                inputElem[i].value = numberValue.toLocaleString();
            } else {
                // 数値でない場合は入力を空にする
                inputElem[i].value = '';
            }
        }
    }
    loadCommmaActive();

    const inputCommma = (target) => {
            // 入力値からカンマを削除し、数値に変換
            var value = target.replace(/,/g, '');
            var numberValue = parseInt(value, 10);

            // isNaN関数で数値かどうかをチェック
            if (!isNaN(numberValue)) {
                // 数値をロケールに応じた文字列に変換（例: "1,234"）
                return numberValue.toLocaleString();
            } else {
                // 数値でない場合は入力を空にする
                return target.value = '';
            }
    }

    const modalActive = () => {
        const modal = document.getElementById('shiftModal');
        const target = document.querySelectorAll('.targetShift');
        const closeElem = document.querySelectorAll('.modalClose');


        for(let i = 0; i < target.length; i++){
            target[i].addEventListener('click', () => {
                modal.style.display = 'block';
                setValue(target[i]);
                changeRadio();
                commmaActive();
            })
        }
        for(let i = 0; i < closeElem.length; i++){
            closeElem[i].addEventListener('click', () => {
                modal.style.display = 'none';
                returnInitialState();
                commmaActive();
            })
        }
    }
    modalActive();

    const setValue = (target) => {
        let setId = document.getElementById('setShiftId');
        let setEmployee = document.getElementById('setEmployeeName');
        let setProject = document.getElementById('projectInput');
        let projectSelect = document.getElementById('projectSelect');
        let setVehicle = document.getElementById('vehicleInput');
        let vehicleSelect = document.getElementById('vehicleSelect');
        let setRetail = document.getElementById('retailInput');
        let setSalary = document.getElementById('salaryInput');
        let setYear = document.querySelector('.setYear');
        let setMonth = document.querySelector('.setMonth');
        let setDate = document.querySelector('.setDate');
        let setPart = document.querySelector('.setPart');

        // モーダルのtext・valueの書き換え
        setId.value = target.querySelector('.shiftId').value;
        setProject.placeholder = target.querySelector('.projectName').value;
        setVehicle.placeholder = target.querySelector('.vehicleNumber').value;
        setRetail.value = inputCommma(target.querySelector('.retailPrice').value);
        setSalary.value = inputCommma(target.querySelector('.salaryPrice').value);
        setEmployee.innerHTML = target.querySelector('.employeeName').value;
        setYear.innerHTML = target.querySelector('.findYear').value;
        setMonth.innerHTML = target.querySelector('.findMonth').value;
        setDate.innerHTML = target.querySelector('.findDate').value;
        if(target.querySelector('.timeOfPart').value == 0){
            setPart.innerHTML = '午前の案件';
        }else{
            setPart.innerHTML = '午後の案件';
        }

        // 取得した案件をchecked
        for(let i = 0; i < projectSelect.options.length; i++){
            if (projectSelect.options[i].text === target.querySelector('.projectName').value) {
                // 一致するオプションが見つかったら、selected 属性を設定
                projectSelect.options[i].selected = true;
                break; // マッチしたらループを抜ける
            }
        }

        // 取得した車両をchecked
        for(let i = 0; i < vehicleSelect.options.length; i++){
            if (vehicleSelect.options[i].text === target.querySelector('.vehicleNumber').value) {
                // 一致するオプションが見つかったら、selected 属性を設定
                vehicleSelect.options[i].selected = true;
                break; // マッチしたらループを抜ける
            }
        }
    }

    const changeRadio = () => {
        const projectInput = document.getElementById('projectInput');
        const projectSelect = document.getElementById('projectSelect');
        const vehicleInput = document.getElementById('vehicleInput');
        const vehicleSelect = document.getElementById('vehicleSelect');

        const projectRadio = document.querySelectorAll('.projectRadio');
        const vehicleRadio = document.querySelectorAll('.vehicleRadio');

        for(let i = 0; i < projectRadio.length; i++){
            projectRadio[i].addEventListener('change', () => {
                projectInput.style.display = 'none';
                projectSelect.style.display = 'none';
                if(projectRadio[i].value == '0'){
                    projectSelect.style.display = "block"
                }else{
                    projectInput.style.display = "block";
                }
            })
        }

        for(let i = 0; i < vehicleRadio.length; i++){
            vehicleRadio[i].addEventListener('change', () => {
                vehicleInput.style.display = 'none';
                vehicleSelect.style.display = 'none';
                if(vehicleRadio[i].value == '0'){
                    vehicleSelect.style.display = "block"
                }else{
                    vehicleInput.style.display = "block";
                }

            })
        }
    }

    const returnInitialState = () => {
        const projectSelect = document.getElementById('projectSelect');
        const vehicleSelect = document.getElementById('vehicleSelect');
        const projectRadio = document.querySelectorAll('.projectRadio');
        const vehicleRadio = document.querySelectorAll('.vehicleRadio');

        projectSelect.options[0].selected = true;
        vehicleSelect.options[0].selected = true;
        projectRadio[0].checked = true;
        vehicleRadio[0].checked = true;

        const projectInput = document.getElementById('projectInput');
        const vehicleInput = document.getElementById('vehicleInput');

        projectInput.style.display = 'none';
        projectSelect.style.display = 'block';
        vehicleInput.style.display = 'none';
        vehicleSelect.style.display = 'block';
    }

    // シフト新規作成モーダルの挙動を制御
    const createModalActive = () => {
        const targetElem = document.querySelectorAll('.createBtn');
        const modal = document.getElementById('createShiftModal');
        const closeElem = document.querySelectorAll('.createCloseModal');

        // モダールに値をセット
        const setValue = (target) => {
            const setId = document.getElementById('createSetId');
            const setEmployee = document.getElementById('createEmployee');
            const setYear = document.getElementById('createYear');
            const setMonth = document.getElementById('createMonth');
            const setDay = document.getElementById('createDay');
            const setTxtPart = document.getElementById('createSetTxtPart');
            const setPart = document.getElementById('createSetPart');

            setId.value = target.querySelector('.createShiftId').value;
            setEmployee.innerHTML = target.querySelector('.createEmployeeName').value;
            setYear.innerHTML = target.querySelector('.createFindYear').value;
            setMonth.innerHTML = target.querySelector('.createFindMonth').value;
            setDay.innerHTML = target.querySelector('.createFindDate').value;
            if(target.querySelector('.createTimeOfPart').value == 0){
                setTxtPart.innerHTML = "午前の案件";
                setPart.value = 0;
            }else{
                setTxtPart.innerHTML = "午後の案件";
                setPart.value = 1;
            }
        }

        // 案件select変化時の上代と給与の金額をセット
        const projectSelect = document.getElementById('createProjectSelect');
        const createRetailInput = document.getElementById('createRetailInput');
        const createSalaryInput = document.getElementById('createSalaryInput');
        const paymentSelect = document.getElementById('paymentSelect');
        if(projectSelect != null){
            function displaySelectedOption(target) {
                // 編集中の従業員IDを取得
                const employeeId = target.querySelector('.createEmployeeName').getAttribute('data-employee-id');
                // 選択されている案件IDを取得
                const projectId = projectSelect.options[projectSelect.selectedIndex].value;
                // 条件が一致する給与を検索
                let paymentAmount = 0;
                for(let i = 0; i < paymentSelect.options.length; i++){
                    // オプションを格納
                    var optionElem = paymentSelect.options[i];
                    var paymentProjectId = optionElem.getAttribute('data-payment-project-id'); //オプションのデータ属性を取得
                    var paymentEmployeeId = optionElem.getAttribute('data-payment-employee-id'); //オプションのデータ属性を取得
                    // 選択されている案件と比較
                    if(paymentProjectId == projectId && paymentEmployeeId == employeeId){
                        paymentAmount = optionElem.value;
                        break;
                    }
                }

                // 上代の金額取得
                const projectAmount = projectSelect.options[projectSelect.selectedIndex].getAttribute('data-retail-amount');
                // ドライバー価格の取得
                const driverAmount = projectSelect.options[projectSelect.selectedIndex].getAttribute('data-driver-amount');
                
                // 金額をセット
                if(projectAmount != null){
                    createRetailInput.value = inputCommma(projectAmount);
                }
                if(paymentAmount != 0){ //案件別給与が一致していれば
                    createSalaryInput.value = inputCommma(paymentAmount);
                }else if(driverAmount != null){
                    createSalaryInput.value = inputCommma(driverAmount);
                }
            }
        }

        // 新規と既存のラジオボタンで表示inputを制御
        const changeRadio = (target) => {
            const projectInput = document.getElementById('createProjectInput');
            const projectSelect = document.getElementById('createProjectSelect');
            const vehicleInput = document.getElementById('createVehicleInput');
            const vehicleSelect = document.getElementById('createVehicleSelect');

            const projectRadio = document.querySelectorAll('.createProjectRadio');
            const vehicleRadio = document.querySelectorAll('.createVehicleRadio');
            // 案件の挙動
            for(let i = 0; i < projectRadio.length; i++){
                projectRadio[i].addEventListener('change', () => {
                    projectInput.style.display = 'none';
                    projectSelect.style.display = 'none';
                    if(projectRadio[i].value == '0'){
                        projectSelect.style.display = "block";
                        // 上代・給与の値をセット
                        displaySelectedOption(target);
                    }else{
                        projectInput.style.display = "block";
                        // 上代・給与の値を空に
                        createRetailInput.value = '';
                        createSalaryInput.value = '';
                    }
                })
            }
            // 車両の挙動
            for(let i = 0; i < vehicleRadio.length; i++){
                vehicleRadio[i].addEventListener('change', () => {
                    vehicleInput.style.display = 'none';
                    vehicleSelect.style.display = 'none';
                    if(vehicleRadio[i].value == '0'){
                        vehicleSelect.style.display = "block"
                    }else{
                        vehicleInput.style.display = "block";
                    }

                })
            }
        }
        // モーダルが閉じた時にすべてのデータを初期値にする
        const CreateReturnInitialState = () => {
            const projectInput = document.getElementById('createProjectInput');
            const projectSelect = document.getElementById('createProjectSelect');
            const vehicleInput = document.getElementById('createVehicleInput');
            const vehicleSelect = document.getElementById('createVehicleSelect');
            const retailInput = document.getElementById('createRetailInput');
            const salaryInput = document.getElementById('createSalaryInput');

            const projectRadio = document.querySelectorAll('.createProjectRadio');
            const vehicleRadio = document.querySelectorAll('.createVehicleRadio');

            projectSelect.options[0].selected = true;
            vehicleSelect.options[0].selected = true;
            projectRadio[0].checked = true;
            vehicleRadio[0].checked = true;

            projectInput.style.display = 'none';
            projectInput.value = '';
            projectSelect.style.display = 'block';
            vehicleInput.style.display = 'none';
            vehicleInput.value = '';
            vehicleSelect.style.display = 'block';
            retailInput.value = '';
            salaryInput.value = '';
        }
        // 開ける
        for(let i = 0; i < targetElem.length; i++){
            targetElem[i].addEventListener('click', () => {
                modal.style.display = "block";
                setValue(targetElem[i]);
                changeRadio(targetElem[i]);
                displaySelectedOption(targetElem[i]);
                // 選択が変更されたときに選択されているオプションを表示
                projectSelect.addEventListener('change', () => {
                    displaySelectedOption(targetElem[i])
                });
            })
        }
        // 閉じる
        for(let i = 0; i < closeElem.length; i++){
            closeElem[i].addEventListener('click', () => {
                modal.style.display = "none";
                CreateReturnInitialState();
            })
        }


    }
    createModalActive();

    // csvファイルのインポートの挙動を制御
    const csvActive = () => {
        const input = document.querySelector('.csvInput');
        const setFileName = document.querySelector('.active-file-txt');

        const fileUpBox = document.querySelector('.file-up-wrap');
        const fileTxt = document.querySelector('.file-txt');
        const csvIconBox = document.querySelector('.csv-icon');
        const defaultCsvIcon = document.querySelector('.default-csv');
        const checkCsvIcon = document.querySelector('.check-csv');
        const warningCsvIcon = document.querySelector('.warning-csv');

        const activeFileBox = document.querySelector('.active-file-box');
        const activeIconBox = document.querySelector('.active-icon-box');
        const activeIcon = document.querySelector('.active-icon');

        const btn = document.querySelector('.import-btn');
        // 警告要素
        const csvWarning = document.querySelector('.csvWarning');

        if(input != null){
            input.addEventListener('change', function() {
                var fileName = ''; // ファイル名を格納する変数
                csvWarning.innerHTML = ''; //警告文を消す
                if (this.files && this.files.length > 0) {
                  // inputタグを通じて選択された最初のファイルの名前を取得
                  fileName = this.files[0].name;
                  var extension = fileName.split('.').pop().toLowerCase(); // ファイル名から拡張子を取得

                    // 拡張子をチェック（ここではPDFファイルのみを許可）
                    if (extension !== 'csv') {
                        warningCsv();
                        btn.disabled = true;
                    }else{
                        clearCsv();
                        btn.disabled = false;
                    }
                }
                // ファイル名をどこかに表示する（例えば、idがfileNameのdiv要素内）
                setFileName.textContent = fileName;


            });
        }

          const warningCsv = () => {
            fileUpBox.style.backgroundColor = 'white';
            fileUpBox.style.border = '2px solid #D14F4F'
            fileTxt.textContent = "ファイル形式が違います";
            fileTxt.style.color = "#787984";
            csvIconBox.style.backgroundColor = "#D14F4F";
            defaultCsvIcon.style.display = 'none';
            checkCsvIcon.style.display = 'none';
            warningCsvIcon.style.display = 'block';

            activeFileBox.style.border = '2px solid #98989D';
            activeFileBox.style.opacity = '0.6';
            activeIconBox.style.backgroundColor = '#98989D';

            btn.style.opacity = "0.6";
            btn.style.backgroundColor = "#98989D";
          }

          const clearCsv = () => {

            fileUpBox.style.backgroundColor = '#4FD1C5';
            fileUpBox.style.border = '2px solid #4FD1C5'
            fileTxt.textContent = "選択中";
            fileTxt.style.color = "white";
            csvIconBox.style.backgroundColor = "white";
            defaultCsvIcon.style.display = 'none';
            checkCsvIcon.style.display = 'block';
            checkCsvIcon.style.color = '#4FD1C5';
            warningCsvIcon.style.display = 'none';

            activeFileBox.style.border = '2px solid #4FD1C5';
            activeFileBox.style.opacity = '1';
            activeIconBox.style.backgroundColor = '#4FD1C5';

            btn.style.opacity = "1";
            btn.style.backgroundColor = "#4F9FD1";
          }

    }
    csvActive();

    const setHeight = () => {
        const setElem = document.querySelectorAll('.setHightElem');

        let max = 0;
        for(let i = 0; i < setElem.length; i++){
            let hight = setElem[i].clientHeight;
            if(max < hight){
                max = hight;
            }
        }

        for(let i = 0; i < setElem.length; i++){
            setElem[i].style.height = `${max}px`;
        }
    }
    setHeight();

    // 所属先の列の挙動を制御
    const companyClmCreate = () => {
        const companyView = document.getElementById('companyView');
        const getRow = document.querySelectorAll('.getRow');
        const companyInfo = document.querySelectorAll('.companyInfo');
        const companyKind = [];
        let companyKindCount = 0;
        const companyHeightArray = [];

        for(let i = 0; i < companyInfo.length; i++){
            // 会社の種類を格納
            if(!companyKind.includes(companyInfo[i].getAttribute('data-company-name'))){
                companyKind[companyKindCount] = companyInfo[i].getAttribute('data-company-name');
                // 会社の種類の分だけ初期値設定
                companyHeightArray[companyKindCount] = 0;
                //index番号付与
                companyKindCount++;
            }
        }
        // 会社ごとの高さを取得
        for(let i = 0; i < companyKind.length; i++){
            for(let j = 0; j < companyInfo.length; j++){
                if(companyKind[i] == companyInfo[j].getAttribute('data-company-name')){
                    companyHeightArray[i] += companyInfo[j].closest('.getRow').offsetHeight - 0.5;
                }
            }
        }

        for(let i = 0; i < companyHeightArray.length; i++){
            // divを作成
            let newElem = document.createElement('div');
            let txtElem = document.createElement('p');
            newElem.classList.add('company-view__item');
            newElem.style.height = companyHeightArray[i] + 'px';
            txtElem.textContent = companyKind[i];
            newElem.appendChild(txtElem);
            companyView.appendChild(newElem);
        }


    }
    companyClmCreate();

})
