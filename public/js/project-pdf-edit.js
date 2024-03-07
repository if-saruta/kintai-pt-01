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
        const taxCalc = (subTotal) => {
            const taxElem = document.querySelector('.taxElem');

            const tax = subTotal * 0.1;
            taxElem.value = Math.ceil(tax);

            return Math.ceil(tax);
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
                // console.log(taxValue);
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


        // 入力があった場合の関数発火プログラム
        const changeElem = document.querySelectorAll('.changeElem');
        for(let i = 0; i < changeElem.length; i++){
            changeElem[i].addEventListener('change', () => {
                rowCalc();
                const subTotal = amountSubTotal();
                const tax = taxCalc(subTotal);
                setTotalValue(subTotal, tax);
                commmaActive();
            })
        }
        const btns = document.querySelectorAll('.deleteRowBtn');
        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener('click', function() {
                rowCalc();
                const subTotal = amountSubTotal();
                const tax = taxCalc(subTotal);
                setTotalValue(subTotal, tax);
                commmaActive();
            });
        }
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
                <td class="table-item w-400"><input type="text" name="item[]" value="" class="input project-table-input changeElem project-edit-pdf-text-left"></td>
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
        // Remove both full-width and half-width commas
        var newValue = value.replace(/,|，/g, '');
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
