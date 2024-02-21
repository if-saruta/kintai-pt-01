window.addEventListener('DOMContentLoaded', () => {

    // テーブル計算
    const calc = () => {

        // 行ごとの計算
        const rowCalc = () => {
            const cnt = document.querySelectorAll('.cntElem');
            const until = document.querySelectorAll('.untilElem');
            const amount = document.querySelectorAll('.amountElem');

            for(let i = 0; i < cnt.length; i++){
                amount[i].value = cnt[i].value * until[i].value;
            }
        }
        // 小計の計算
        const amountSubTotal = () => {
            const amount = document.querySelectorAll('.amountElem');
            const amountSubTotal = document.querySelector('.amountSubTotal');
            let total = 0;
            for(let i = 0; i < amount.length; i++){
                const amountValue = parseInt(amount[i].value, 10);
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
            setAmount.textContent = '¥' + tmp;
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
                tax.value = targetAmount.value * 0.1;
                // 対象外の計算
                notTargetAmount.value = amountSubTotal.value - targetAmount.value;

                const taxValue = targetAmount.value * 0.1;
                // メインテーブルに反映
                taxElem.value = taxValue;
                totalElem.value = parseInt(amountSubTotal.value, 10) + parseInt(taxValue, 10);
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
            })
        }
        const btns = document.querySelectorAll('.deleteRowBtn');
        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener('click', function() {
                rowCalc();
                const subTotal = amountSubTotal();
                const tax = taxCalc(subTotal);
                setTotalValue(subTotal, tax);
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
                <td class="table-data w-100"><input type="number" name="number[]" value="" class="input project-table-input changeElem cntElem project-edit-pdf-text-right"></td>
                <td class="table-data w-100"><input type="number" name="until[]" value="" class="input project-table-input changeElem untilElem project-edit-pdf-text-right"></td>
                <td class="table-data w-110"><input type="number" name="amount[]" value="" class="input project-table-input changeElem amountElem project-edit-pdf-text-right"></td>
                <div class="salaryRowDelete deleteRowBtn"><span class="deleteRowBtn__line"><span/><span class="deleteRowBtn__line"><span/><div/>
            `;
            //最後の行を取得
            const tableRow = document.querySelectorAll('.tableRow');
            const lastRow = tableRow[tableRow.length - 1];
            // 最後の行の下に行を追加
            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);

            deleteRow();
            calc();
        })
    }
    addRow();
})
