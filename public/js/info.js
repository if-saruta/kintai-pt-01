window.addEventListener('DOMContentLoaded', () => {

    // 列の横幅を均等
    const widthSet = () => {
        const numberElem = document.querySelectorAll('.w-number');
        let maxWidthNumber = 0;

        for(let i = 0; i < numberElem.length; i++){
            if(maxWidthNumber < numberElem[i].clientWidth){
                maxWidthNumber = numberElem[i].clientWidth;
            }
        }
        for(let i = 0; i < numberElem.length; i++){
            numberElem[i].style.width = maxWidthNumber + 'px';
        }

        const nameElem = document.querySelectorAll('.w-name');
        let maxWidthName = 0;

        for(let i = 0; i < nameElem.length; i++){
            if(maxWidthName < nameElem[i].clientWidth){
                maxWidthName = nameElem[i].clientWidth;
            }
        }
        for(let i = 0; i < nameElem.length; i++){
            nameElem[i].style.width = maxWidthName + 'px';
        }
    }
    widthSet();

    const editViewActive = () => {
        let setTxtElem = document.querySelectorAll('.setTxtElem');

        const companyBox = document.querySelectorAll('.companyBox');
        const editBtn = document.querySelectorAll('.editBtn');
        const defaultView = document.getElementById('defaultView');
        const editView = document.getElementById('editView');
        const closeBtn = document.querySelector('.closeBtn');
        const addBtn = document.getElementById('addBtn');

        // 一覧リストの行をクリック時の挙動
        for(let i = 0; i < companyBox.length; i++){
            companyBox[i].addEventListener('click', () => {
                // 編集中時は動作を制御
                if(!companyBox[i].classList.contains('notClickElem')){
                    for(let j = 0; j < companyBox.length; j++){
                        if(i == j){
                            continue;
                        }
                        companyBox[j].classList.remove('open');
                    }
                    if(companyBox[i].classList.contains('open')){
                        companyBox[i].classList.remove('open');
                    }else{
                        companyBox[i].classList.add('open');
                    }
                }

                clickSetText(companyBox[i]);
                clickSetTextByClient(companyBox[i]);
            })
        }
        // 編集ボタンクリック時の挙動
        for(let i = 0; i < editBtn.length; i++){
            editBtn[i].addEventListener('click', () => {
                // 画面の切り替え
                defaultView.style.display = 'none';
                editView.style.display = 'flex';

                editClickSetValue(editBtn[i]);
                editBtnClickStyleActive(editBtn[i])
            })
        }
        // 閉じるボタンクリック
        closeBtn.addEventListener('click', () => {
            defaultView.style.display = 'flex';
            editView.style.display = 'none';
            resetStyle();
            resetSetTxt();
        })
        if(addBtn != null){
            addBtn.addEventListener('click', () => {
                resetStyle();
                resetSetTxt();
            })
        }

        // クリック時データをテキストにセット
        const clickSetText = (clickElem) => {
            for(let j = 0; j < setTxtElem.length; j++){
                setTxtElem[j].innerHTML = '--------';
            }

            const dataHasElem = clickElem.querySelector('.dataHasElem');
            if(clickElem.classList.contains('open')){
                if(dataHasElem !== null){
                    var data = JSON.parse(dataHasElem.getAttribute('data-info'));
                    data.forEach((element, index) => {
                        if(setTxtElem.length != 0){
                            setTxtElem[index].innerHTML = element;
                        }
                    });
                }
            }
        }

        // 編集ボタンクリック時データセット
        const editClickSetValue = (btn) => {
            const dataHasElem = btn.querySelector('.dataHasEditElem');
            let setValueElem = document.querySelectorAll('.setValueElem');
            var data = JSON.parse(dataHasElem.getAttribute('data-info'));
            // データをセット
            data.forEach((element, index) => {
                setValueElem[index].value = element;
            });

            const selectElem = document.getElementById('setSelectValueElem');
            if(selectElem !== null){
                for(let i = 0; i < selectElem.options.length; i++){
                    if(selectElem.options[i].value == data[2]){
                        selectElem.selectedIndex = i;
                        break;
                    }
                }
            }

        }

        // 編集ボタンクリック時スタイルの挙動を制御
        const editBtnClickStyleActive = (btn) => {

            // 編集時常時open
            btn.classList.add('edit-btn-active');
            btn.querySelector('.edit-btn-txt').innerHTML = '編集中';

            for(let i = 0; i < companyBox.length; i++){
                // すべての行を黒くする
                companyBox[i].classList.add('after-element');
                // ホバーアクションを制御
                companyBox[i].classList.add('notClickElem');
            }

            // クリックした列の背景色を設定
            var parentElem = btn.parentElement;
            parentElem.classList.remove('after-element');

            addBtn.classList.add('disabled');
            addBtn.disabled = true;
        }

        // スタイルを初期状態に戻す
        const resetStyle = () => {
            for(let i = 0; i < companyBox.length; i++){
                companyBox[i].classList.remove('after-element');
                // ホバーアクションを制御
                companyBox[i].classList.remove('notClickElem');
                companyBox[i].classList.remove('open');
                editBtn[i].classList.remove('edit-btn-active');
                editBtn[i].querySelector('.edit-btn-txt').innerHTML = '編集';
            }
            addBtn.classList.remove('disabled');
            addBtn.disabled = false;
        }

        const resetSetTxt = () => {
            for(let j = 0; j < setTxtElem.length; j++){
                setTxtElem[j].innerHTML = '--------';
            }
        }


        const clickSetTextByClient = (clickElem) => {
            const countBox = document.querySelector('.countBox');
            const clientNameTxtElem = document.querySelector('.setTxtClientElem');
            if(countBox !== null){
                // 初期化
                clientNameTxtElem.innerHTML = '--------';
                countBox.innerHTML = '';
                const projectRow = document.querySelectorAll('.infoRowProject');
                for(let j = 0; j < projectRow.length; j++){
                    projectRow[j].remove();
                }

                if(clickElem.classList.contains('open')){
                    // クライアント名をセット
                    const dataHasClientName = clickElem.querySelector('.dataHasClientName');
                    clientNameTxtElem.innerHTML = dataHasClientName.getAttribute('data-client-name');
                    // 案件数をセット
                    const dataHasProjectName = clickElem.querySelectorAll('.dataHasProjectName');
                    countBox.innerHTML = dataHasProjectName.length;

                    // 案件名をセット
                    for(let j = 0; j < dataHasProjectName.length; j++){
                        var dataProjectName = dataHasProjectName[j].getAttribute('data-project-name');
                        var infoRow = document.createElement('div');
                        infoRow.classList.add('info-row');
                        infoRow.classList.add('infoRowProject');
                        infoRow.innerHTML = `
                        <p class="info-row__head">案件名</p>
                            <div class="info-row__data">
                                <p class="setRegisterNumber">${dataProjectName}</p>
                            </div>
                        `;
                        document.getElementById('defaultViewInner').appendChild(infoRow);
                    }
                }
            }
        }

    }
    editViewActive();

    const createViewActive = () => {
        const defaultView = document.getElementById('defaultView');
        const editView = document.getElementById('editView');
        const createView = document.getElementById('createView');
        const addBtn = document.getElementById('addBtn');
        const closeBtn = document.querySelector('.createCloseBtn');

        if(addBtn != null){
            addBtn.addEventListener('click', () => {
                defaultView.style.display = 'none';
                editView.style.display = 'none';
                createView.style.display = 'flex';
                hoverStyleActive();
            })
        }

        closeBtn.addEventListener('click', () => {
            defaultView.style.display = 'flex';
            createView.style.display = 'none';
            resetStyle();
        })

        // ホバー時のスタイルを設定
        const hoverStyleActive = () => {
            const companyBox = document.querySelectorAll('.companyBox');
            for(let i = 0; i < companyBox.length; i++){
                companyBox[i].classList.add('after-element');
                // ホバーアクションを制御
                companyBox[i].classList.add('notHoverElem');
            }
        }
        const resetStyle = () => {
            const companyBox = document.querySelectorAll('.companyBox');
            for(let i = 0; i < companyBox.length; i++){
                companyBox[i].classList.remove('after-element');
                // ホバーアクションを制御
                companyBox[i].classList.remove('notHoverElem');
            }
        }

    }
    createViewActive();
})
