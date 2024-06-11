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

    /**
     * ***************************************************************************
     * ******************            編集モーダル            ***********************
     * ***************************************************************************
     */

    const modalActive = () => {
        const modal = document.getElementById('shiftModal');


        if(modal != null){
            const target = document.querySelectorAll('.targetShift');
            const closeElem = document.querySelectorAll('.modalClose');
            const editClientWrap = document.getElementById('editClientWrap');
            const editAllowanceWrap = modal.querySelector('#editAllowanceWrap');
            const allowanceCt = modal.querySelector('#allowanceCt');

            let fetchEmployeeId = null;
            let shiftPvId = null;

            // プログラムによる変更のフラグを初期化
            let isProgrammaticChange = false;

            for(let i = 0; i < target.length; i++){
                // モーダルを開く
                target[i].addEventListener('click', () => {
                    modal.style.display = 'block';
                    setValue(target[i]);
                    changeRadio();
                    commmaActive();

                    let firstProject = target[i].querySelector('.editProjectId');
                    let employee = target[i].querySelector('.employeeId');

                    if(firstProject){
                        let firstProjectId = firstProject.value;
                        let employeeId = null;
                        setSelect2ValueProgrammatically(firstProjectId);
                        if(employee != null){
                            employeeId = employee.value;
                        }
                        fetchAllowance(firstProjectId);
                        fetchAmount(firstProjectId, employeeId);
                        fetchProject(firstProjectId);
                    }
                    if(employee){
                        fetchEmployeeId = employee.value;
                    }
                })
            }
            // モーダルを閉じる
            for(let i = 0; i < closeElem.length; i++){
                closeElem[i].addEventListener('click', () => {
                    const confirmation = confirm('入力したデータは失われます。');
                    if(confirmation){
                        modal.style.display = 'none';
                        returnInitialState();
                        commmaActive();
                    }
                })
            }

            // オプションの選択をプログラムで行う関数
            function setSelect2ValueProgrammatically(value) {
                isProgrammaticChange = true; // フラグを立てる
                $('.editProjectSelect').val(value).trigger('change');
                isProgrammaticChange = false; // フラグをリセット
            }

            // モーダル内の値をセットする
            const setValue = (target) => {
                setId = document.getElementById('setShiftId');
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
                shiftPvId = target.querySelector('.shiftId').value;
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
                modal.querySelector('.customName').textContent = target.querySelector('.editCustomProjectName').textContent;

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


            // 案件・車両の入力方法の切り替え
            const changeRadio = () => {
                const projectInput = document.getElementById('projectInput');
                const projectSelect = modal.querySelector('.select2-container');
                const vehicleInput = document.getElementById('vehicleInput');
                const vehicleSelect = document.getElementById('vehicleSelect');

                const projectRadio = document.querySelectorAll('.projectRadio');
                const vehicleRadio = document.querySelectorAll('.vehicleRadio');

                for(let i = 0; i < projectRadio.length; i++){
                    projectRadio[i].addEventListener('change', () => {
                        projectInput.style.display = 'none';
                        projectSelect.style.display = 'none';
                        clientAddActive(projectRadio[i]);
                        if(projectRadio[i].value == '0'){
                            projectSelect.style.display = "block"
                        }else{
                            projectInput.style.display = "block";
                            datePicker.required = false;
                            charterEmployeeSelect.required = false;
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

            const clientSwitchRadio = modal.querySelectorAll('.clientSwitchRadio');
            const clientExistingArea = modal.querySelector('#editclientExistingArea');
            const clientCreateArea = modal.querySelector('#editclientCreateArea');
            const clientSelect = modal.querySelector('.clientSelect');
            const clientInput = modal.querySelectorAll('.clientInput');

            // 案件が新規作成の場合のクラインアント入力画面の制御
            const clientAddActive = (radio) => {
                // 新規か既存案件の切り替え
                if(radio.value == '0'){
                    // 非表示
                    modal.classList.remove('create-add-client-active');
                    editClientWrap.classList.remove('add-client');
                    // 必須を解除
                    clientSelect.required = false;
                    for(let i = 0; i < clientInput.length; i++){
                        clientInput[i].required = false;
                    }
                    // 手当がある場合は表示
                    if(modal.classList.contains('create-add-allowance-active')){
                        editAllowanceWrap.classList.add('add-allowance');
                    }

                }else{
                    // 表示
                    modal.classList.add('create-add-client-active');
                    editClientWrap.classList.add('add-client');
                    // 必須を付与
                    clientSelect.required = true;
                    // 手当を非表示
                    editAllowanceWrap.classList.remove('add-allowance');
                }

                // 新規か既存クライアントの切り替え
                for(let i = 0; i < clientSwitchRadio.length; i++){
                    clientSwitchRadio[i].addEventListener('change', () => {
                        if(clientSwitchRadio[i].value == '0'){
                            clientExistingArea.style.display = 'flex';
                            clientCreateArea.style.display = 'none';
                            // 必須を付与
                            clientSelect.required = true;
                            for(let i = 0; i < clientInput.length; i++){
                                clientInput[i].required = false;
                            }
                        }else{
                            clientExistingArea.style.display = 'none';
                            clientCreateArea.style.display = 'flex';
                            // 必須を付与
                            clientSelect.required = false;
                            for(let i = 0; i < clientInput.length; i++){
                                clientInput[i].required = true;
                            }
                        }
                    })
                }
            }

            // 案件に基づく情報を表示
            let projectId = null;
            $('.editProjectSelect').select2();
            $('.editProjectSelect').on('change', function (e) {
                // プログラムによる変更の場合は何もしない
                if (isProgrammaticChange) return;

                // 選択されたデータを取得
                var data = $(this).select2('data');
                projectId = data[0]['id'];

                if(projectId != ''){
                    fetchAllowance(projectId);
                    fetchProject(projectId)
                }
                if(projectId != '' && fetchEmployeeId != ''){
                    fetchAmount(projectId, fetchEmployeeId);
                }
            })

            // 手当の情報を取得
            function fetchAllowance(id){
                allowanceCt.innerHTML = '';
                fetch(`/fetch-data/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(allowances => { //取得したデータを表示
                    // 取得したデータがない場合
                    if (!allowances || allowances.length === 0) {
                        modal.classList.remove('create-add-allowance-active');
                        editAllowanceWrap.classList.remove('add-allowance');
                        return;
                    }
                    allowances.forEach(allowance => {
                        modal.classList.add('create-add-allowance-active');
                        editAllowanceWrap.classList.add('add-allowance');
                        let newDiv = document.createElement('div');
                        let checked = null;
                        allowance.shift_allowance.forEach(shiftAllowance => {
                            if(shiftAllowance['id'] == shiftPvId){
                                checked = 'checked';
                            }
                        });
                        newDiv.innerHTML = `
                            <div class="check-box-item">
                                <label for="">
                                    <input type="checkbox" name="allowance[]" value="${allowance['id']}" ${checked}>
                                    ${allowance['name']}
                                </label>
                            </div>
                        `
                        allowanceCt.append(newDiv);
                    })
                })
                .catch(error => console.error('Error:', error));
            }

            // 案件の金額を取得
            let setRetail = document.getElementById('retailInput');
            let setSalary = document.getElementById('salaryInput');
            function fetchAmount(projectId, employeeId){
                fetch(`/fetch-project-amount/${projectId}/${employeeId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(project => { //取得したデータを表示
                    // 取得したデータがない場合
                    if (!project) return;
                    setRetail.value = inputCommma(String(project['retail']));
                    setSalary.value = inputCommma(String(project['driver']));
                })
                .catch(error => console.error('Error:', error));
            }


            // チャーター
            const form = document.getElementById('shiftEditForm');
            const charterWrap = modal.querySelector('#charterWrap');
            let charterSwitch = modal.querySelectorAll('.charterSwitch');
            let charterCreate = modal.querySelector('.charterCreate');
            let charterSelect = modal.querySelector('.charterSelect');
            let datePicker = modal.querySelector('.datepicker');
            let charterEmployeeSelect = modal.querySelector('.charterEmployee');
            let charterVehicleSelect = modal.querySelector('.charterVehicle');
            let charterSalary = modal.querySelector('.charterSalary');
            let charterTimeOfPart = modal.querySelectorAll('.timeOfPart');
            let searchBtn = modal.querySelector('.searchBtn');
            const searchShiftList = modal.querySelector('.searchShiftList');
            const relatedShift = modal.querySelector('.relatedShift');
            let relatedShiftDate = modal.querySelector('.relatedShiftDate');
            let relatedShiftEmployee = modal.querySelector('.relatedShiftEmployee');
            let relatedShiftVehicle = modal.querySelector('.relatedShiftVehicle');
            let relatedShiftAmount = modal.querySelector('.relatedShiftAmount');
            const relatedRadio = modal.querySelector('.relatedRaio');

            // 削除ボタンが押された場合は、必須を解除する
            const deleteBtn = modal.querySelector('.editDeleteBtn');
            deleteBtn.addEventListener('click', function(event) {
                if (confirm("本当に削除しますか?")) {
                    // 必須属性を解除
                    datePicker.required = false;
                    charterEmployeeSelect.required = false;
                } else {
                    // フォーム送信を中止
                    event.preventDefault();
                }
            });

            // 案件データを取得
            function fetchProject(projectId) {
                fetch(`/fetch-project/${projectId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(project => { //取得したデータを表示
                    // 取得したデータがない場合
                    if (!project) return;
                    isCharter = project['is_charter']
                    charterRelationActive(isCharter);
                })
                .catch(error => console.error('Error:', error));
            }

            // シフト検索
            let date = modal.querySelector('.searchDate');
            function searchHandler() {
                let date = modal.querySelector('.searchDate').value;
                searchShift(date);
            }
            // シフトの検索
            const searchShift = (date) => {
                // リセット
                searchShiftList.innerHTML = '';
                if(date != ''){
                    fetch(`/search-shift/${date}/${projectId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(error => { throw new Error(error.error); });
                        }
                        return response.json();
                    })
                    .then(shiftProjectVehicles => { //取得したデータを表示
                        // シフトがない場合
                        if(!shiftProjectVehicles || shiftProjectVehicles.length == 0){
                            searchShiftList.textContent = '検索条件のシフトはありません';
                            return;
                        }
                        // セレクトを作成
                        let newSelect = document.createElement('select');
                        newSelect.className = 'c-select charterProjectSelect';
                        newSelect.name = 'charter[shiftPvId]';
                        // オプションを作成
                        shiftProjectVehicles.forEach(shiftProjectVehicle => {
                            let newOption = document.createElement('option');
                            let timeOfPart = '';
                            if(shiftProjectVehicle['time_of_part' == 0]){
                                timeOfPart = '午前';
                            }else{
                                timeOfPart = '午後';
                            }
                            newOption.textContent = `${shiftProjectVehicle['shift']['date']} ${timeOfPart} ${shiftProjectVehicle['shift']['employee']['name']}`;
                            newOption.value = shiftProjectVehicle['id'];
                            // セレクトにオプションを追加
                            newSelect.appendChild(newOption);
                        });
                        // 親要素にセレクトを追加
                        searchShiftList.appendChild(newSelect);
                    })
                    .catch(error => console.error('Error:', error.message));
                }else{
                    searchShiftList.textContent = '日付を選択してください';
                }
            }
            // 紐づくシフトがあるか確認
            function fetchShift () {
                fetch(`/fetch-shiftPv/${shiftPvId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(shiftProjectVehicle => { //取得したデータを表示
                    if(shiftProjectVehicle['related_shift_project_vehicle_id'] != null){
                        relatedShiftInfo(shiftProjectVehicle);
                    }
                })
                .catch(error => console.error('Error:', error.message));
            }
            // formの送信の制御
            const editSaveBtn = modal.querySelector('.editSaveBtn');
            editSaveBtn.addEventListener('click', function(event) {
                if(isCharter == 1){
                    const select = modal.querySelector('.charterProjectSelect');
                    if(charterSelect.classList.contains('open')){
                        if(select == null){
                            event.preventDefault();
                        }
                    }
                }
            })
            const charterRelationActive = (isCharter) => {
                if(isCharter == 1){
                    modal.classList.add('create-add-charter-active');
                    charterWrap.style.display = 'flex';

                    fetchShift()
                    // 必須を付与
                    datePicker.required = true;
                    charterEmployeeSelect.required = true;
                    // 新規か既存かの表示の切り替え
                    charterSwitch.forEach(radio => {
                        radio.addEventListener('click', () => {
                            if(radio.value == 0){
                                charterCreate.style.display = 'flex';
                                charterSelect.style.display = 'none';
                                charterSelect.classList.remove('open');
                                relatedShift.style.display = 'none';
                                // 必須を付与
                                datePicker.required = true;
                                charterEmployeeSelect.required = true;
                            }else if(radio.value == 1){
                                charterCreate.style.display = 'none';
                                charterSelect.style.display = 'flex';
                                charterSelect.classList.add('open');
                                relatedShift.style.display = 'none';
                                // 必須を解除
                                datePicker.required = false;
                                charterEmployeeSelect.required = false;
                            }else if(radio.value == 2){
                                charterCreate.style.display = 'none';
                                charterSelect.style.display = 'none';
                                charterSelect.classList.remove('open');
                                relatedShift.style.display = 'none';
                                // 必須を解除
                                datePicker.required = false;
                                charterEmployeeSelect.required = false;
                            }else{
                                relatedShiftView()
                            }
                        })
                    });

                    searchBtn.addEventListener('click', searchHandler);
                }else{
                    modal.classList.remove('create-add-charter-active');
                    charterWrap.style.display = 'none';
                    // 必須を解除
                    datePicker.required = false;
                    charterEmployeeSelect.required = false;
                }
            }
            // 表示画面の設定
            function relatedShiftView(){
                charterCreate.style.display = 'none';
                charterSelect.style.display = 'none';
                charterSelect.classList.remove('open');
                relatedShift.style.display = 'flex';
                relatedRadio.style.display = 'block';
                // 必須を解除
                datePicker.required = false;
                charterEmployeeSelect.required = false;
                // ラジオボタンを登録済みにセット
                charterSwitch[3].checked = true;
            }
            // 紐づいてるシフトの情報を表示
            const relatedShiftInfo = (shiftProjectVehicle) => {
                relatedShiftView();
                let date = shiftProjectVehicle['related_shift_project_vehicle']['shift']['date'];
                let timeOfPart = shiftProjectVehicle['related_shift_project_vehicle']['time_of_part'] == 0 ? '午前' : '午後'
                let employeeName = shiftProjectVehicle['related_shift_project_vehicle']['shift']['employee']['name'];
                let vehicle = shiftProjectVehicle['related_shift_project_vehicle']['vehicle'] ? shiftProjectVehicle['related_shift_project_vehicle']['vehicle']['number'] : '未設定';
                let driverAmount = shiftProjectVehicle['related_shift_project_vehicle']['driver_price'] ?? '未設定';

                relatedShiftDate.textContent = `${date} : ${timeOfPart}`;
                relatedShiftEmployee.textContent = `${employeeName}`;
                relatedShiftVehicle.textContent = `${vehicle}`;
                relatedShiftAmount.textContent = `${driverAmount.toLocaleString()}`;
            }


            // 初期状態に戻す
            const returnInitialState = () => {
                const projectSelect = modal.querySelector('.select2-container');
                const vehicleSelect = document.getElementById('vehicleSelect');
                const projectRadio = document.querySelectorAll('.projectRadio');
                const vehicleRadio = document.querySelectorAll('.vehicleRadio');

                // projectSelect.options[0].selected = true;
                // vehicleSelect.options[0].selected = true;
                projectRadio[0].checked = true;
                vehicleRadio[0].checked = true;

                const projectInput = document.getElementById('projectInput');
                const vehicleInput = document.getElementById('vehicleInput');

                projectInput.style.display = 'none';
                projectSelect.style.display = 'block';
                vehicleInput.style.display = 'none';
                vehicleSelect.style.display = 'block';
                modal.querySelector('.customName').textContent = '';

                $('.editProjectSelect').val(null).trigger('change');

                // クライアント
                modal.classList.remove('create-add-client-active');
                editClientWrap.classList.remove('add-client');
                // 手当
                modal.classList.remove('create-add-allowance-active');
                allowanceCt.innerHTML = '';
                editAllowanceWrap.classList.remove('add-allowance');

                // チャーター
                modal.classList.remove('create-add-charter-active');
                datePicker.required = true;
                datePicker.value = '';
                charterEmployeeSelect.required = true;
                charterEmployeeSelect.selectedIndex = 0;
                charterVehicleSelect.selectedIndex = 0;
                charterTimeOfPart[0].checked = true;
                charterTimeOfPart[1].checked = false;
                charterSalary.value = '';
                charterWrap.style.display = 'none';
                searchShiftList.innerHTML = '';
                relatedShift.style.display = 'none';
                charterSwitch[0].checked = true;
                relatedRadio.style.display = 'none';
                date.value = '';
                charterCreate.style.display = 'flex';
                charterSelect.style.display = 'none';
                searchBtn.removeEventListener('click', searchHandler);
                modal.querySelector('.ralatedCustomName').value = '';
            }
        }

    }
    modalActive();





    /**
     * *************************************************************************
     * ***************             新規作成モーダル               *****************
     * *************************************************************************
     */
    const modal = document.getElementById('createShiftModal');
    // シフト新規作成モーダルの挙動を制御
    const createModalActive = () => {
        const targetElem = document.querySelectorAll('.createBtn');
        const closeElem = document.querySelectorAll('.createCloseModal');
        let setRetail = document.getElementById('createRetailInput');
        let setSalary = document.getElementById('createSalaryInput');
        let employeeId = null;
        let isCharter = null;
        const form = document.getElementById('shiftCreateForm');

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
            employeeId = target.querySelector('.createEmployeeId').value;

            if(target.querySelector('.createTimeOfPart').value == 0){
                setTxtPart.innerHTML = "午前の案件";
                setPart.value = 0;
            }else{
                setTxtPart.innerHTML = "午後の案件";
                setPart.value = 1;
            }
        }

        let projectId = null;
        // 上代・給与を自動設定
        $('.createProjectSelect').select2();
        $('.createProjectSelect').on('change', function (e) {
            var data = $(this).select2('data');
            projectId = data[0]['id'];
            if(projectId != '' && employeeId != ''){
                fetchAmount(projectId, employeeId);
            }
            if(projectId != ''){
                projectViewActive(projectId);
                fetchProject(projectId)
            }
        })

        function fetchAmount(projectId, employeeId){
            fetch(`/fetch-project-amount/${projectId}/${employeeId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(project => { //取得したデータを表示
                // 取得したデータがない場合
                if (!project) return;
                setRetail.value = inputCommma(String(project['retail']));
                setSalary.value = inputCommma(String(project['driver']));
            })
            .catch(error => console.error('Error:', error));
        }
        // 案件データを取得
        function fetchProject(projectId) {
            fetch(`/fetch-project/${projectId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(project => { //取得したデータを表示
                // 取得したデータがない場合
                if (!project) return;
                isCharter = project['is_charter']
                charterRelationActive(isCharter);
            })
            .catch(error => console.error('Error:', error));
        }


        // 案件と車両の新規と既存のラジオボタンで表示inputを制御
        const changeRadio = (target) => {
            const projectInput = document.getElementById('createProjectInput');
            const projectSelect = modal.querySelector('.select2-container');
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
                        // displaySelectedOption(target);
                        clientViewActive(projectRadio[i]);
                    }else{
                        projectInput.style.display = "block";
                        // 上代・給与の値を空に
                        createRetailInput.value = '';
                        createSalaryInput.value = '';
                        clientViewActive(projectRadio[i]);
                        datePicker.required = false;
                        charterEmployeeSelect.required = false;
                        modal.classList.remove('create-add-charter-active');
                        charterWrap.style.display = 'none';
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

        /**
         * 右側表示に関するコード
         */
        // クライアントに関する要素を取得
        const createClientWrap = document.getElementById('createClientWrap');
        const clientSwitch = document.querySelectorAll('.clientSwitchRadio'); //既存か新規のラジオボタン
        const clientExistingView = document.getElementById('clientExistingArea'); //既存のview
        const clientCreateView = document.getElementById('clientCreateArea'); //新規のview
        const clientSelect = clientExistingView.querySelector('.clientSelect');
        const clientInput = clientCreateView.querySelectorAll('.clientInput'); //新規input

        // 手当に関する要素を取得
        const allowanceParent = document.getElementById('createAllowanceWrap');
        const allowanceCt = modal.querySelector('#allowanceCt');
        const createProjectSelect = document.getElementById('createProjectSelect');

        // チャーター
        const charterWrap = modal.querySelector('#charterWrap');
        let charterSwitch = modal.querySelectorAll('.charterSwitch');
        let charterCreate = modal.querySelector('.charterCreate');
        let charterSelect = modal.querySelector('.charterSelect');
        let datePicker = modal.querySelector('.datepicker');
        let charterEmployeeSelect = modal.querySelector('.charterEmployee');
        let charterVehicleSelect = modal.querySelector('.charterVehicle');
        let charterSalary = modal.querySelector('.charterSalary');
        let charterTimeOfPart = modal.querySelectorAll('.timeOfPart');
        let searchBtn = modal.querySelector('.searchBtn');
        const searchShiftList = modal.querySelector('.searchShiftList');

        // クライアントの挙動を制御
        const clientViewActive = (projectRadio) => {
            // 既存・新規で表示の切り替え
            if(projectRadio.value == '0'){
                modal.classList.remove('create-add-client-active');
                createClientWrap.classList.remove('add-client');
                clientSelect.required = false;
                for(let j = 0; j < clientInput.length; j++){ //inputの必須項目変更
                    clientInput[j].required = false;
                }

                // 手当が非表示されていた場合、表示に変更
                if(modal.classList.contains('create-add-allowance-active')){
                    allowanceParent.classList.add('add-allowance');
                }
                if(modal.classList.contains('create-add-charter-active')){
                    charterWrap.style.display = 'flex';
                }
            }else{
                createClientWrap.classList.add('add-client');
                modal.classList.add('create-add-client-active');
                clientSelect.required = true; //初期値として必須項目に変更

                // 手当が表示されていた場合非表示に変更
                allowanceParent.classList.remove('add-allowance');
                charterWrap.style.display = 'none';
            }

            for(let i = 0; i < clientSwitch.length; i++){
                clientSwitch[i].addEventListener('change', () => {
                    if(clientSwitch[i].value == '0'){
                        clientExistingView.style.display = 'block';
                        clientCreateView.style.display = 'none';
                        clientSelect.required = true; //selectの必須項目変更
                        for(let j = 0; j < clientInput.length; j++){ //inputの必須項目変更
                            clientInput[j].required = false;
                        }
                    }else{
                        clientExistingView.style.display = 'none';
                        clientCreateView.style.display = 'flex';
                        clientSelect.required = false; //selectの必須項目変更
                        for(let j = 0; j < clientInput.length; j++){ //inputの必須項目変更
                            clientInput[j].required = true;
                        }
                    }
                })
            }
        }

        // 手当の挙動を制御
        const projectViewActive = (projectId) => {

            // 表示箇所をリセット
            allowanceCt.innerHTML = '';

            if(projectId != ''){
                fetchProjectAllowance(projectId);
            }


            // 取得したIDをもとにAjax通信でデータを取得
            function fetchProjectAllowance(id){
                fetch(`/fetch-data/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(allowances => { //取得したデータを表示
                    // 取得したデータがない場合
                    if (!allowances || allowances.length === 0) {
                        modal.classList.remove('create-add-allowance-active');
                        allowanceParent.classList.remove('add-allowance');
                        return;
                    }
                    allowances.forEach(allowance => {
                        modal.classList.add('create-add-allowance-active');
                        allowanceParent.classList.add('add-allowance');
                        let newDiv = document.createElement('div');
                        newDiv.innerHTML = `
                            <div class="check-box-item">
                                <label for="">
                                    <input type="checkbox" name="allowance[]" value="${allowance['id']}">
                                    ${allowance['name']}
                                </label>
                            </div>
                        `
                        allowanceCt.append(newDiv);
                    })
                })
                .catch(error => console.error('Error:', error));
            }
        }

        /**
         * チャーター
         */
        // シフト検索
        let date = modal.querySelector('.searchDate');
        function searchHandler() {
            let date = modal.querySelector('.searchDate').value;
            searchShift(date);
        }
        // シフトの検索
        const searchShift = (date) => {
            // リセット
            searchShiftList.innerHTML = '';
            if(date != ''){
                fetch(`/search-shift/${date}/${projectId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => { throw new Error(error.error); });
                    }
                    return response.json();
                })
                .then(shiftProjectVehicles => { //取得したデータを表示
                    // シフトがない場合
                    if(!shiftProjectVehicles || shiftProjectVehicles.length == 0){
                        searchShiftList.textContent = '検索条件のシフトはありません';
                        return;
                    }
                    // セレクトを作成
                    let newSelect = document.createElement('select');
                    newSelect.className = 'c-select charterProjectSelect';
                    newSelect.name = 'charter[shiftPvId]';
                    // オプションを作成
                    shiftProjectVehicles.forEach(shiftProjectVehicle => {
                        let newOption = document.createElement('option');
                        let timeOfPart = '';
                        if(shiftProjectVehicle['time_of_part' == 0]){
                            timeOfPart = '午前';
                        }else{
                            timeOfPart = '午後';
                        }
                        newOption.textContent = `${shiftProjectVehicle['shift']['date']} ${timeOfPart} ${shiftProjectVehicle['shift']['employee']['name']}`;
                        newOption.value = shiftProjectVehicle['id'];
                        // セレクトにオプションを追加
                        newSelect.appendChild(newOption);
                    });
                    // 親要素にセレクトを追加
                    searchShiftList.appendChild(newSelect);
                })
                .catch(error => console.error('Error:', error.message));
            }else{
                searchShiftList.textContent = '日付を選択してください';
            }
        }
        // formの送信の制御
        form.addEventListener('submit', function(event) {
            if(isCharter == 1){
                const select = modal.querySelector('.charterProjectSelect');
                if(charterSelect.classList.contains('open')){
                    if(select == null){
                        event.preventDefault();
                    }
                }
            }
        })
        const charterRelationActive = (isCharter) => {
            if(isCharter == 1){
                modal.classList.add('create-add-charter-active');
                charterWrap.style.display = 'flex';

                // 必須を付与
                datePicker.required = true;
                charterEmployeeSelect.required = true;
                // 新規か既存か未定の表示の切り替え
                charterSwitch.forEach(radio => {
                    radio.addEventListener('click', () => {
                        if(radio.value == 0){
                            charterCreate.style.display = 'flex';
                            charterSelect.style.display = 'none';
                            charterSelect.classList.remove('open');
                            // 必須を付与
                            datePicker.required = true;
                            charterEmployeeSelect.required = true;
                        }else if(radio.value == 1){
                            charterCreate.style.display = 'none';
                            charterSelect.style.display = 'flex';
                            charterSelect.classList.add('open');
                            // 必須を解除
                            datePicker.required = false;
                            charterEmployeeSelect.required = false;
                        }else{
                            charterCreate.style.display = 'none';
                            charterSelect.style.display = 'none';
                            charterSelect.classList.remove('open');
                            // 必須を解除
                            datePicker.required = false;
                            charterEmployeeSelect.required = false;
                        }
                    })
                });

                searchBtn.addEventListener('click', searchHandler);
            }else{
                modal.classList.remove('create-add-charter-active');
                charterWrap.style.display = 'none';
                // 必須を解除
                datePicker.required = false;
                charterEmployeeSelect.required = false;
            }
        }



        // モーダルが閉じた時にすべてのデータを初期値にする
        const CreateReturnInitialState = () => {
            const projectInput = document.getElementById('createProjectInput');
            const projectSelect = modal.querySelector('.select2-container');
            const vehicleInput = document.getElementById('createVehicleInput');
            const vehicleSelect = document.getElementById('createVehicleSelect');
            const retailInput = document.getElementById('createRetailInput');
            const salaryInput = document.getElementById('createSalaryInput');

            const projectRadio = document.querySelectorAll('.createProjectRadio');
            const vehicleRadio = document.querySelectorAll('.createVehicleRadio');

            // projectSelect.options[0].selected = true;
            // vehicleSelect.options[0].selected = true;
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
            modal.querySelector('.customName').value = '';

            $('.createProjectSelect').val(null).trigger('change');

            // クライアント
            modal.classList.remove('create-add-client-active'); //クライアントviewを非表示
            clientSwitch[0].checked = true; //既存案件のラジオをtrue
            clientExistingView.style.display = 'block'; //既存クライアントviewを表示
            clientCreateView.style.display = 'none'; //新規クライアントviewを非表示
            clientSelect.options[0].selected = true; //クライアントselectのoptionを0
            for(let i = 0; i < clientInput.length; i++){
                clientInput[i].value = '';
            }
            createClientWrap.classList.remove('add-client');

            // 手当
            allowanceCt.innerHTML = '';
            modal.classList.remove('create-add-allowance-active');
            allowanceParent.classList.remove('add-allowance');

            // チャーター
            modal.classList.remove('create-add-charter-active');
            datePicker.required = true;
            datePicker.value = '';
            charterEmployeeSelect.required = true;
            charterEmployeeSelect.selectedIndex = 0;
            charterVehicleSelect.selectedIndex = 0;
            charterTimeOfPart[0].checked = true;
            charterTimeOfPart[1].checked = false;
            charterSalary.value = '';
            charterWrap.style.display = 'none';
            searchShiftList.innerHTML = '';
            charterSwitch[0].checked = true;
            date.value = '';
            charterCreate.style.display = 'flex';
            charterSelect.style.display = 'none';
            searchBtn.removeEventListener('click', searchHandler);
            modal.querySelector('.ralatedCustomName').value = '';
        }
        // 開ける
        for(let i = 0; i < targetElem.length; i++){
            targetElem[i].addEventListener('click', () => {
                modal.style.display = "block";
                setValue(targetElem[i]);
                changeRadio(targetElem[i]);
                // displaySelectedOption(targetElem[i]);
                // 選択が変更されたときに選択されているオプションを表示
                projectSelect.addEventListener('change', () => {
                    displaySelectedOption(targetElem[i])
                });
            })
        }
        // 閉じる
        for(let i = 0; i < closeElem.length; i++){
            closeElem[i].addEventListener('click', () => {
                const confirmation = confirm('入力したデータは失われます。');
                if(confirmation){
                    modal.style.display = "none";
                    CreateReturnInitialState();
                }
            })
        }


    }
    if(modal != null){
        createModalActive();
    }

    /**
     * ********************************************************************
     * **************************   CSV   *********************************
     * ********************************************************************
     */
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
        const projectHeight = document.getElementById('projectHeight');

        let max = 0;
        for(let i = 0; i < setElem.length; i++){
            let height = setElem[i].clientHeight;
            if(max < height){
                max = height;
            }
        }

        if(max < 100){
            max = 100
        }

        for(let i = 0; i < setElem.length; i++){
            setElem[i].style.height = `${max}px`;
        }
        // pdfのフォームのinputに案件の高さ
        if(projectHeight != null){
            projectHeight.value = max;
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

        if(companyView != null){
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


    }
    companyClmCreate();

    /**
     * 編集ページ　設定モーダル
     */
    const settingModalActive = () => {
        const modal = document.getElementById('settingModal');
        const openBtn = document.querySelector('.settingModalOpen');
        const closeBtn = document.querySelectorAll('.settingModalClose');

        if(modal){
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
    settingModalActive();

    const employeeModalActive = () => {
        const modal = document.getElementById('employeeModal');
        const openBtn = document.querySelector('.employeeModalOpen');
        const closeBtn = document.querySelectorAll('.employeeModalClose');

        if(modal){
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
    employeeModalActive();

    /**
     * **************************************************************
     * *******************    従業員絞り込み    ***********************
     * **************************************************************
     */

    const employeeArrowActive = () => {
        const bulkChangeEmployeeCheckBox = document.querySelector('.bulkChangeEmployeeCheckBox');
        const employeeCheckBox = document.querySelectorAll('.employeeCheckBox');

        if(bulkChangeEmployeeCheckBox != null){
            // 一つでもチェックがついていなければ一括チェックボックスのチェックを外す
            const bulkCheckBoxFalseActive = (checkBox) => {
                if(!checkBox.checked){
                    bulkChangeEmployeeCheckBox.checked = false;
                }
            }
            for(let i = 0; i < employeeCheckBox.length; i++){
                // 初期読み込み時
                bulkCheckBoxFalseActive(employeeCheckBox[i])

                // 従業員チェックボックスがクリック時
                employeeCheckBox[i].addEventListener('click', () => {
                    bulkCheckBoxFalseActive(employeeCheckBox[i])

                    // 全てチェックがついていれば一括チェックボックスにもチェックを付与する
                    let isChecked = true;
                    for(let j = 0; j < employeeCheckBox.length; j++){
                        if(!employeeCheckBox[j].checked){
                            isChecked = false;
                        }
                    }
                    if(isChecked){
                        bulkChangeEmployeeCheckBox.checked = true;
                    }
                })
            }

            // 一括チェックボックスクリック時
            bulkChangeEmployeeCheckBox.addEventListener('click', () => {
                if(bulkChangeEmployeeCheckBox.checked){
                    checkBoxCheckedActive();
                }
            })
            // 全てにチェックを入れる
            const checkBoxCheckedActive = () => {
                for(let i = 0; i < employeeCheckBox.length; i++){
                    employeeCheckBox[i].checked = true;
                }
            }
        }
    }
    employeeArrowActive();


    /**
     * **************************************************************
     * *******************      シフトメモ      ***********************
     * **************************************************************
     */
    const shiftMemoActive = () => {
        const modal = document.getElementById('shiftMemoModal');
        const open = document.querySelectorAll('.shiftMemoOpen');
        const close = document.querySelectorAll('.shiftMemoClose');
        const shiftMemo = document.getElementById('shiftMemo');
        const saveBtn = document.getElementById('memoSaveBtn');
        const succsessBunner = document.getElementById('succsessBunner');
        const errorBunner = document.getElementById('errorBunner');

        let employeeId = null;

        if(modal != null){
            for(let i = 0; i < open.length; i++){
                open[i].addEventListener('click', () => {
                    modal.style.display = 'block';

                    // 従業員IDを取得
                    employeeId = open[i].getAttribute('data-employee-id');
                    // 従業員IDをもとにデータを取得
                    function fetchEmployee(id){
                        fetch(`/fetch-employee-data/${id}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(employee => { //取得したデータを表示
                            // テキストエリアにメモを表示
                            shiftMemo.value = employee['shift_memo'];
                        })
                        .catch(error => console.error('Error:', error));
                    }
                    fetchEmployee(employeeId);
                })
            }

            saveBtn.addEventListener('click', () => {
                succsessBunner.classList.remove('bunner-animation');
                errorBunner.classList.remove('bunner-animation');
                // メモの値を取得
                const memoData = shiftMemo.value;
                // json形式に変換
                const data = {
                    memo : memoData,
                    id : employeeId
                }
                fetch('/store-memo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    succsessBunner.classList.add('bunner-animation');
                })
                .catch((error) => {
                    errorBunner.classList.add('bunner-animation');
                });
            })


            // 閉じる処理
            for(let i = 0; i < close.length; i++){
                close[i].addEventListener('click', () => {
                    modal.style.display = 'none';
                    succsessBunner.classList.remove('bunner-animation');
                    errorBunner.classList.remove('bunner-animation');
                    shiftMemo.value = '';
                })
            }
        }
    }
    shiftMemoActive();
})




