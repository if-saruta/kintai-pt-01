window.addEventListener('DOMContentLoaded', () => {

    // テーブル計算
    const calc = () => {

        // 行ごとの計算
        const rowCalc = () => {
            const cnt = document.querySelectorAll('.cntElem');
            const until = document.querySelectorAll('.untilElem');
            const amount = document.querySelectorAll('.amountElem');

            for(let i = 0; i < cnt.length; i++){
                amount[i].value = cnt[i].value * removeCommasAndCastToInt(until[i].value);
            }
        }
        // 小計の計算
        const amountSubTotal = () => {
            const amount = document.querySelectorAll('.amountElem');
            const amountSubTotal = document.querySelector('.amountSubTotal');
            let total = 0;
            for(let i = 0; i < amount.length; i++){
                const amountValue = removeCommasAndCastToInt(amount[i].value);
                if (!isNaN(amountValue)) {
                    total += amountValue;
                }
            }
            amountSubTotal.value = total;
            return total;
        }
        // 消費税の計算
        const taxCalc = () => {
            const taxElem = document.querySelector('.taxElem');

            const taxCheckBox = document.querySelectorAll('.row-check-box');
            let targetAmountTotal = 0; //税率の対象の変数
            // checkBoxの確認
            for(let i = 0; i < taxCheckBox.length; i++){
                if(taxCheckBox[i].checked){ //チェックがついてる行
                    let trParent = taxCheckBox[i].parentNode.parentNode; //親要素の行を取得
                    let targetAmount = removeCommasAndCastToInt(trParent.querySelector('.amountElem').value); //金額のinputの値を取得
                    if(!isNaN(targetAmount)){ //カンマを除去した値が数値か判断
                        targetAmountTotal += targetAmount;
                    }
                }
            }
            const tax = targetAmountTotal * 0.1;
            taxElem.value = Math.round(tax);

            return Math.round(tax);
        }
        // 合計の計算
        const setTotalValue = (subTotal, tax) => {
            const totalElem = document.querySelector('.totalElem');
            const setAmount = document.querySelector('.amount-fee');

            totalElem.value = subTotal + tax;
            let tmp = subTotal + tax;
            setAmount.textContent = '¥' + addCommas(tmp);
            commmaActive();
        }

        // 消費税対象の計算
        const targetAmountTaxCalc = () => {
            const targetAmount = document.querySelector('.targetAmount');
            const tax = document.querySelector('.targetAmountTax');
            const notTargetAmount = document.querySelector('.notTargetAmount');

            targetAmount.addEventListener('change', () => {
                const amountSubTotal = document.querySelector('.amountSubTotal');
                const taxElem = document.querySelector('.taxElem');
                const totalElem = document.querySelector('.totalElem');

                // 消費税の計算
                tax.value = removeCommasAndCastToInt(targetAmount.value) * 0.1;
                // 対象外の計算
                notTargetAmount.value = removeCommasAndCastToInt(amountSubTotal.value) - removeCommasAndCastToInt(targetAmount.value);

                let taxValue = removeCommasAndCastToInt(targetAmount.value) * 0.1;

                if(isNaN(taxValue)){
                    taxValue = 0;
                }
                // メインテーブルに反映
                taxElem.value = taxValue;
                totalElem.value = removeCommasAndCastToInt(amountSubTotal.value) + taxValue;
                commmaActive();
            })
        }
        targetAmountTaxCalc();

        const mainTableChangeTaxCalc = (subtotalValue,taxValue) => {
            const targetAmount = document.querySelector('.targetAmount');
            const tax = document.querySelector('.targetAmountTax');
            const notTargetAmount = document.querySelector('.notTargetAmount');

            targetAmount.value = subtotalValue;
            tax.value = taxValue;
            commmaActive();
        }


        // 入力があった場合の関数発火プログラム
        const changeElem = document.querySelectorAll('.changeElem');
        for(let i = 0; i < changeElem.length; i++){
            changeElem[i].addEventListener('change', () => {
                rowCalc();
                const subTotal = amountSubTotal();
                const tax = taxCalc();
                setTotalValue(subTotal, tax);
                commmaActive();
                mainTableChangeTaxCalc(subTotal,tax);
            })
        }
        // 行が削除された時の関数発火
        const btns = document.querySelectorAll('.deleteRowBtn');
        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener('click', function() {
                rowCalc();
                const subTotal = amountSubTotal();
                const tax = taxCalc();
                setTotalValue(subTotal, tax);
                commmaActive();
            });
        }
        // checkboxにactionがあった時の税金のプログラム
        const checkBoxActiveTaxCalc = () => {
            const taxCheckBox = document.querySelectorAll('.row-check-box');
            const taxElem = document.querySelector('.taxElem');
            const totalElem = document.querySelector('.totalElem');
            const subTotalElem = document.querySelector('.amountSubTotal');

            for(let i = 0; i < taxCheckBox.length; i++){
                taxCheckBox[i].addEventListener('change', () => {
                    // メインテーブルの変更
                    let targetAmountTotal = 0; //税率の対象の変数
                    for(let j = 0; j < taxCheckBox.length; j++){
                        if(taxCheckBox[j].checked){ //チェックがついてる行
                            let trParent = taxCheckBox[j].parentNode.parentNode; //親要素の行を取得
                            let targetAmount = removeCommasAndCastToInt(trParent.querySelector('.amountElem').value); //金額のinputの値を取得
                            if(!isNaN(targetAmount)){ //カンマを除去した値が数値か判断
                                targetAmountTotal += targetAmount;
                            }
                        }
                    }
                    const tax = targetAmountTotal * 0.1;
                    taxElem.value = addCommas(Math.round(tax));
                    totalElem.value = addCommas(removeCommasAndCastToInt(subTotalElem.value) + tax);

                    // 税金対象テーブルの変更
                    const targetAmount = document.querySelector('.targetAmount');
                    const targetTax = document.querySelector('.targetAmountTax');
                    const notTargetAmount = document.querySelector('.notTargetAmount');

                    targetAmount.value = addCommas(targetAmountTotal); //10%対象の変更
                    targetTax.value = addCommas(tax); //税金の変更
                    notTargetAmount.value = addCommas(removeCommasAndCastToInt(subTotalElem.value) - targetAmountTotal);
                })
            }
        }
        checkBoxActiveTaxCalc();
    }
    calc();


    // 追加行の削除
    const deleteRow = () => {
        const btns = document.querySelectorAll('.deleteRowBtn');

        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener('click', function() {
                // クリックされたボタンの親のtr要素を探す
                let tr = this.closest('tr');

                // tr要素が見つかった場合、それを削除
                if (tr) {
                    tr.remove();
                }
                commmaActive();
            });
        }
    }

    // 行を追加
    const addRow = () => {
        const btn = document.querySelector('.project-edit-plus-btn');

        btn.addEventListener('click', () => {
            // 新しいtr要素を作成
            const newRow = document.createElement('tr');
            newRow.classList.add('tableRow');

            newRow.innerHTML = `
                <td class="table-item w-400"><input type="text" name="item[]" value="" class="input project-table-input changeElem project-edit-pdf-text-left"> <input checked type="checkbox" class="row-check-box"></td>
                <td class="table-data w-100"><input type="text" name="number[]" value="" class="input project-table-input changeElem cntElem project-edit-pdf-text-right"></td>
                <td class="table-data w-100"><input type="text" name="until[]" value="" class="input project-table-input changeElem untilElem project-edit-pdf-text-right commaInput"></td>
                <td class="table-data w-110"><input type="text" name="amount[]" value="" class="input project-table-input changeElem amountElem project-edit-pdf-text-right commaInput"></td>
                <div class="salaryRowDelete deleteRowBtn"><span class="deleteRowBtn__line"><span/><span class="deleteRowBtn__line"><span/><div/>
            `;
            //最後の行を取得
            const tableRow = document.querySelectorAll('.tableRow');
            const lastRow = tableRow[tableRow.length - 1];
            // 最後の行の下に行を追加
            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);

            deleteRow();
            calc();
            commmaActive();
        })
    }
    addRow();

    function removeCommasAndCastToInt(value) {
        // 確認または変換されたvalueが常に文字列であることを保証
        var stringValue = String(value);
        // Remove both full-width and half-width commas
        var newValue = stringValue.replace(/,|，/g, '');
        // Cast to int
        return parseInt(newValue, 10);
    }

    function addCommas(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // カンマの制御
    const commmaActive = () => {
        const inputElem = document.querySelectorAll('.commaInput');

        // イベントが発火した時にカンマの挙動を制御
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

        // 読み込み時にカンマの挙動を制御
        for(let i = 0; i < inputElem.length; i++){
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
    commmaActive();
})
