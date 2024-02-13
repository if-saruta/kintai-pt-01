window.addEventListener('load', () => {

    // // 車両貸出形態に関するコード
    // const rentalVehicleAction = () => {
    //     const rentalSelect = document.querySelector('.js-rental-type-select');
    //     const rentalVehilce = document.querySelector('.js-rental-vehicle');

    //     // 編集画面読み込み時の挙動を制御
    //     if(rentalSelect.value == 1){
    //         rentalVehilce.style.display = 'flex';
    //     }

    //     // フォーカスアウトした時の挙動を制御
    //     rentalSelect.addEventListener('focusout', () => {
    //         if(rentalSelect.value == 1){
    //             rentalVehilce.style.display = 'flex';
    //         }else{
    //             rentalVehilce.style.display = 'none';
    //         }
    //     })
    // }
    // rentalVehicleAction();

    // // 案件別手当に関するコード
    // const allowanceByProject = () => {
    //     document.querySelectorAll('.add-allowance').forEach(function(btn) {
    //         btn.addEventListener('click', function() {
    //             // プロジェクトIDを取得
    //             var projectId = this.id.replace('add-allowance-btn-', '');

    //             // 新しい手当入力ブロックを作成
    //             var allowanceBlock = document.createElement('div');
    //             allowanceBlock.className = 'allowance-by-project__item__allowance-block';

    //             // 手当名の入力フィールド
    //             var allowanceName = `
    //                 <div class="allowance-info --allowance-name">
    //                     <label>手当名</label>
    //                     <input type="text" name="allowanceName[${projectId}][]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="手当名">
    //                 </div>`;

    //             // 金額の入力フィールド
    //             var allowanceAmount = `
    //                 <div class="allowance-info">
    //                     <label>金額</label>
    //                     <input type="text" name="allowanceAmount[${projectId}][]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="00">
    //                 </div>`;

    //             var deleteBtn = `
    //                 <div class="allowance-project-delete-btn delete-btn">
    //                     <span class="delete-btn__line"></span>
    //                 </div>`;

    //             // ブロックに手当名と金額の入力フィールドを追加
    //             allowanceBlock.innerHTML = allowanceName + allowanceAmount + deleteBtn;

    //             // 手当入力ブロックを追加する位置を特定するために、
    //             // クリックされたボタン（btn）の親要素を取得
    //             var parentDiv = btn.closest('.allowance-by-project__item');

    //             // parentDivの中で、新しい手当入力ブロックを挿入したい位置の直前にある要素を特定
    //             // この例では、クリックされたボタン自体の直前に挿入します。
    //             var insertBeforeThis = btn.closest('.add-allowance-btn');

    //             // insertBefore()を使用して新しい手当入力ブロックを挿入
    //             parentDiv.insertBefore(allowanceBlock, insertBeforeThis);

    //             allowanceProjectToDelete();
    //         });
    //     });
    // }
    // allowanceByProject();

    // const allowanceProjectToDelete = () => {
    //     // 'delete-btn'クラスを持つすべての要素に対して処理を行う
    //     document.querySelectorAll('.allowance-project-delete-btn').forEach(function (deleteButton) {
    //         // 各削除ボタンにクリックイベントリスナーを追加
    //         deleteButton.addEventListener('click', function () {
    //             // このボタンに最も近い 'allowance-by-project__item' クラスの親要素を見つける
    //             const allowanceBlock = this.closest('.allowance-by-project__item__allowance-block');

    //             if (allowanceBlock) {
    //                 // 該当する親要素が存在する場合、それをDOMから削除
    //                 allowanceBlock.remove();
    //             }
    //         });
    //     });
    // }
    // allowanceProjectToDelete();

    // const allowanceProjectTmpToDelete = () => {
    //     // 'delete-btn'クラスを持つすべての要素に対して処理を行う
    //     document.querySelectorAll('.allowance-project-delete-btn').forEach(function (deleteButton) {
    //         // 各削除ボタンにクリックイベントリスナーを追加
    //         deleteButton.addEventListener('click', function () {
    //             // このボタンに最も近い 'allowance-by-project__item' クラスの親要素を見つける
    //             const allowanceBlock = this.closest('.allowance-by-project__item__allowance-block');

    //             if(this.id){
    //                 var id = this.id.replace('delete-btn-', '');
    //             }

    //             if (allowanceBlock) {
    //                 // 編集ページの際にデータが登録されているDOMだった場合
    //                 if(id){
    //                     var container = document.getElementById('tmpAllowanceProjectDeleteId').parentNode;
    //                     var newInput = document.createElement('input');
    //                     newInput.type = 'hidden';
    //                     newInput.name = 'allowanceProjectDeleteId[]';
    //                     newInput.value = id;

    //                     // 新しいinput要素をコンテナに追加
    //                     container.appendChild(newInput);
    //                 }
    //             }
    //         });
    //     });
    // }
    // allowanceProjectTmpToDelete();


    // // その他手当に関するコード
    // const allowanceByOther = () => {
    //     const btn = document.getElementById('add-other-allowance')
    //     btn.addEventListener('click', function() {
    //         // 新しい手当入力ブロックを作成
    //         var newItem = document.createElement('div');
    //         newItem.className = 'item';

    //         // 手当名の入力フィールドを追加
    //         var allowanceNameInput = `
    //             <div class="item__allowance-input allowance-name">
    //                 <label>手当名</label>
    //                 <input type="text" name="allowanceOtherName[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    //             </div>`;

    //         // 金額の入力フィールドを追加
    //         var allowanceAmountInput = `
    //             <div class="item__allowance-input allowance-amount">
    //                 <label>金額</label>
    //                 <input type="text" name="allowanceOtherAmount[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    //             </div>`;

    //         var deleteBtn = `
    //             <div class="allowance-other-delete-btn delete-btn">
    //                 <span class="delete-btn__line"></span>
    //             </div>`;

    //         // newItemに手当名と金額の入力フィールドを追加
    //         newItem.innerHTML = allowanceNameInput + allowanceAmountInput + deleteBtn;

    //         // 手当入力ブロックを追加する位置を特定するために、
    //         // クリックされたボタン（btn）の親要素を取得
    //         var parentDiv = btn.closest('.allowance-by-other__inner');

    //         // parentDivの中で、新しい手当入力ブロックを挿入したい位置の直前にある要素を特定
    //         // この例では、クリックされたボタン自体の直前に挿入します。
    //         var insertBeforeThis = btn.closest('.btn');

    //         // insertBefore()を使用して新しい手当入力ブロックを挿入
    //         parentDiv.insertBefore(newItem, insertBeforeThis);

    //         allowanceOtherToDeleteByCreatePage();
    //     });
    // }
    // allowanceByOther();

    // const allowanceOtherToDeleteByCreatePage = () => {
    //     // 'delete-btn'クラスを持つすべての要素に対して処理を行う
    //     document.querySelectorAll('.allowance-other-delete-btn').forEach(function (deleteButton) {
    //         // 各削除ボタンにクリックイベントリスナーを追加
    //         deleteButton.addEventListener('click', function () {
    //             // このボタンに最も近い 'allowance-by-project__item' クラスの親要素を見つける
    //             const allowanceBlock = this.closest('.item');
    //             // 該当する親要素が存在する場合、それをDOMから削除
    //             if (allowanceBlock) {
    //                 allowanceBlock.remove();
    //             }
    //         });
    //     });
    // }
    // allowanceOtherToDeleteByCreatePage();

    // const allowanceOtherTmpToDelete = () => {
    //     // 'delete-btn'クラスを持つすべての要素に対して処理を行う
    //     document.querySelectorAll('.allowance-other-delete-btn').forEach(function (deleteButton) {
    //         // 各削除ボタンにクリックイベントリスナーを追加
    //         deleteButton.addEventListener('click', function () {
    //             if(this.id){
    //                 var id = this.id.replace('delete-btn-', '');
    //             }

    //             const allowanceBlock = this.closest('.item');

    //             if (allowanceBlock) {
    //                 // 編集ページの際にデータが登録されているDOMだった場合
    //                 if(id){
    //                     var container = document.getElementById('tmpAllowanceOtherDeleteId').parentNode;
    //                     var newInput = document.createElement('input');
    //                     newInput.type = 'hidden';
    //                     newInput.name = 'allowanceOtherDeleteId[]';
    //                     newInput.value = id;

    //                     // 新しいinput要素をコンテナに追加
    //                     container.appendChild(newInput);
    //                 }
    //             }
    //         });
    //     });
    // }
    // allowanceOtherTmpToDelete();

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
        // let projectId = addBtn.getAttribute('data-project-id');
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
    projectByAllowanceDelete();

    const otherAllowanceAdd = () => {
        const addBtn = document.getElementById('otherAllowanceAdd');
        const container = document.getElementById('otherAllowanceContainer');

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
    otherAllowanceAdd();

    const otherAllowanceDelete = () => {
        const container = document.getElementById('otherAllowanceContainer');
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
    otherAllowanceDelete();

})
