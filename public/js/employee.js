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
                        <input type="text" name="allowanceAmount[${projectId}][]" class="c-input" placeholder="1,000">
                    </div>
                    <i class="fa-solid fa-circle-minus delete-circle projectAllowanecDelete"></i>
                `;
                container[i].insertBefore(createElem, addBtn[i]);
            })
        }

    }
    projectByAllowance();

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
    projectByAllowanceDelete();

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
                        <input type="text" name="allowanceOtherAmount[]" class="c-input" placeholder="1,000">
                    </div>
                    <i class="fa-solid fa-circle-minus delete-circle otherAllowanceDelete"></i>
                `;
                container.insertBefore(createElem, addBtn);
            })
        }
    }
    otherAllowanceAdd();

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
    otherAllowanceDelete();

    // 貸出形態変更時の貸出車両のselelctの挙動を制御
    const vehicleSelectActive = () => {
        const observeSelect = document.getElementById('observeSelect');
        const controlSelect = document.getElementById('controlSelect');

        if(observeSelect != null){
            // select選択時
            observeSelect.addEventListener('change', () => {
                if(observeSelect.value == 1){
                    controlSelect.classList.remove('not-action');
                }else{
                    controlSelect.classList.add('not-action');
                }
            })
                    // 読み込み時
            if(observeSelect.value == 1){
                controlSelect.classList.remove('not-action');
            }else{
                controlSelect.classList.add('not-action');
            }
        }
    }
    vehicleSelectActive();

})
