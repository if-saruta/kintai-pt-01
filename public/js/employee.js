window.addEventListener('load', () => {

    const widthSet = () => {
        const numberElements = document.querySelectorAll('.w-number');
        const nameElements = document.querySelectorAll('.w-name');
        const statusElements = document.querySelectorAll('.w-status');
        const affiliationElements = document.querySelectorAll('.w-Affiliation');

            // 最も長いテキストを持つ要素の幅を取得して設定する関数
        const setMaxWidth = (elements) => {
            let maxWidth = 0;

            // 最大幅を計算
            elements.forEach(element => {
                const width = element.offsetWidth;
                if (width > maxWidth) {
                    maxWidth = width;
                }
            });

            // 計算した最大幅を適用
            elements.forEach(element => {
                element.style.width = `${maxWidth}px`;
            });
        };

        // 各クラスに対して最大幅を設定
        setMaxWidth(numberElements);
        setMaxWidth(nameElements);
        setMaxWidth(statusElements);
        setMaxWidth(affiliationElements);

    }
    widthSet();

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

    const projectByAllowance = () => {
        const addBtn = document.querySelectorAll('.projectAllowanceAdd');
        const container = document.querySelectorAll('.projectAllowanceContainer');
        let projectId = null;

        for(let i = 0; i < addBtn.length; i++){
            addBtn[i].addEventListener('click', () => {
                projectId = addBtn[i].getAttribute('data-project-id');
                let createElem = document.createElement('div');
                createElem.classList.add('allowance-item');
                createElem.innerHTML = `
                    <div class="allowance__name">
                        <p class="">手当名</p>
                        <input type="text" name="allowanceName[${projectId}][]" class="c-input" placeholder="リーダー手当">
                    </div>
                    <div class="allowance__amount">
                        <p class="">手当金額</p>
                        <input type="text" name="allowanceAmount[${projectId}][]" class="c-input commaInput" placeholder="1,000">
                    </div>
                    <i class="fa-solid fa-circle-minus delete-circle projectAllowanecDelete"></i>
                `;
                container[i].insertBefore(createElem, addBtn[i]);
                commmaActive();
            })
        }

    }
    // projectByAllowance();

    const projectByAllowanceDelete = () => {
        let container = document.getElementById('parentProjectAllowance');
        if(container != null){
            container.addEventListener('click', (e) => {
                let btn = e.target;
                if(btn.classList.contains('projectAllowanecDelete')){
                    if(btn.classList.contains('tmpProjectAllowance')){
                        let id = btn.getAttribute('data-project-all-id');
                        var newInput = document.createElement('input');
                        newInput.type = 'hidden';
                        newInput.name = 'allowanceProjectDeleteId[]';
                        newInput.value = id;
                        container.appendChild(newInput);
                    }
                    let parent = btn.closest('.allowance-item');
                    parent.remove();
                }
            })
        }
    }
    // projectByAllowanceDelete();

    const otherAllowanceAdd = () => {
        const addBtn = document.getElementById('otherAllowanceAdd');
        const container = document.getElementById('otherAllowanceContainer');

        if(addBtn != null){
            addBtn.addEventListener('click', () => {
                let createElem = document.createElement('div');
                createElem.classList.add('input-item-box');
                createElem.innerHTML = `
                    <div class="input-item allowance-name">
                        <p class="">手当名</p>
                        <input type="text" name="allowanceOtherName[]" class="c-input" placeholder="リーダー手当">
                    </div>
                    <div class="input-item allowance-amount">
                        <p class="">手当金額</p>
                        <input type="text" name="allowanceOtherAmount[]" class="c-input commaInput" placeholder="1,000">
                    </div>
                    <i class="fa-solid fa-circle-minus delete-circle otherAllowanceDelete"></i>
                `;
                container.insertBefore(createElem, addBtn);
                commmaActive();
            })
        }
    }
    // otherAllowanceAdd();

    const otherAllowanceDelete = () => {
        const container = document.getElementById('otherAllowanceContainer');
        if(container != null){
            container.addEventListener('click', (e) => {
                let btn = e.target;
                if(btn.classList.contains('otherAllowanceDelete')){
                    if(btn.classList.contains('tmpOtherAllowance')){
                        let id = btn.getAttribute('data-other-all-id');
                        var newInput = document.createElement('input');
                        newInput.type = 'hidden';
                        newInput.name = 'allowanceOtherDeleteId[]';
                        newInput.value = id;
                        container.appendChild(newInput);
                    }
                    let parent = btn.closest('.input-item-box');
                    parent.remove();
                }
            })
        }
    }
    // otherAllowanceDelete();

    // 貸出形態変更時の貸出車両のselelctの挙動を制御
    const vehicleSelectActive = () => {
        const observeSelect = document.getElementById('observeSelect');
        const controlSelect = document.getElementById('controlSelect');

        const vehicleSelect = document.getElementById('vehicleSelect');
        const form = document.getElementById('form');
        const warningTxt = document.getElementById('vehicleWarningTxt');

        if(observeSelect != null){
            /**
             * 貸出車両のアクションを制御
             */
            // select選択時
            observeSelect.addEventListener('change', () => {
                if(observeSelect.value == 1){
                    controlSelect.classList.remove('--vehicle-not-action');
                }else{
                    controlSelect.classList.add('--vehicle-not-action');
                    vehicleSelect.selectedIndex = 0;
                    warningCheckAction(true);
                }
            })
                    // 読み込み時
            if(observeSelect.value == 1){
                controlSelect.classList.remove('--vehicle-not-action');
            }else{
                controlSelect.classList.add('--vehicle-not-action');
                vehicleSelect.selectedIndex = 0;
            }

            /**
             * 貸出車両の確認
             */
            vehicleSelect.addEventListener('change', function() {
                // 選択されているIDを取得
                var vehicleId = parseInt(this.value, 10); //文字列のためint型にキャスト
                // 配列にIDが含まれているか確認
                if(vehicleUsedArray.includes(vehicleId)){
                    warningCheckAction(false);
                } else {
                    warningCheckAction(true);
                }
            })

            const warningCheckAction = (isBool) => {
                if(isBool){
                    warningTxt.textContent = '';
                    form.dataset.valid = "true";
                }else{
                    warningTxt.textContent = '既に使用されているため、変更してください';
                    form.dataset.valid = "false";
                }
            }

            // フォーム送信時のイベント
            form.addEventListener('submit', function(event) {
                if (form.dataset.valid === "false") {
                    event.preventDefault(); // フォームの送信を阻止
                    alert('貸出車両が正しくありません。別の車両をを選択してください。');
                }
            });
        }

    }
    vehicleSelectActive();

    // 貸出車両の制御
    const checkVehicleUsed = () => {
        const vehicleSelect = document.getElementById('vehicleSelect');
        const form = document.getElementById('form');
        const warningTxt = document.getElementById('vehicleWarningTxt');

        vehicleSelect.addEventListener('change', function() {
            // 選択されているIDを取得
            var vehicleId = parseInt(this.value, 10); //文字列のためint型にキャスト
            // 配列にIDが含まれているか確認
            if(vehicleUsedArray.includes(vehicleId)){
                warningTxt.textContent = '既に使用されているため、変更してください';
                form.dataset.valid = "false";
            } else {
                warningTxt.textContent = '';
                form.dataset.valid = "true";
            }
        })

        // フォーム送信時のイベント
        form.addEventListener('submit', function(event) {
            if (form.dataset.valid === "false") {
                event.preventDefault(); // フォームの送信を阻止
                alert('貸出車両が正しくありません。別の車両をを選択してください。');
            }
        });
    }
    // checkVehicleUsed();

    // インボイス登録のアクションによって、登録番号の挙動を制御
    const invoiceInputControl = () => {
        const radio = document.querySelectorAll('.invoiceRadio');
        const registerInput = document.querySelector('.registerInputWrap');

        const inputControl = (radio, registerInputWrap) => {
            for(let i = 0; i < radio.length; i++){
                if(radio[i].checked){
                    if(radio[i].value == 1){
                        registerInput.classList.remove('register-input-open');
                    }else{
                        registerInput.classList.add('register-input-open');
                    }
                }
            }
        }
        // ロード時
        inputControl(radio, registerInput);
        // radioに変化があった時
        for(let i = 0; i < radio.length; i++){
            radio[i].addEventListener('click', () => {
                inputControl(radio, registerInput);
            })
        }
    }
    invoiceInputControl();



})
