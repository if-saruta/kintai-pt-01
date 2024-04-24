window.addEventListener('DOMContentLoaded', () => {

    // 請求書番号の連動
    const invoiceNumberChange = () => {
        const getInput = document.querySelector('.invoice-number-input');
        const setInput = document.querySelectorAll('.invoice-number');

        getInput.addEventListener('change', () => {
            const getData = document.querySelector('.invoice-number-input').value;
            for(let i = 0; i < setInput.length; i++){
                setInput[i].value = getData;
            }
        })
    }
    // invoiceNumberChange();

    // モーダルの挙動を制御
    const bankModalActive = () => {
        const modal = document.getElementById('bankModal');
        const radio = document.querySelectorAll('.bank-switch-radio');
        const activeItems = document.querySelectorAll('.bank-switch-active');
        const setValueArea = document.getElementById('bankArea');
        const btn = document.getElementById('bankModalSave');

        setValueArea.addEventListener('click', () => {
            // モーダル表示
            modal.style.display = 'block';
            // inputとselectの切り替え
            for(let i = 0; i < radio.length; i++){
                radio[i].addEventListener('click', () => {
                    for(let j = 0; j < activeItems.length; j++){
                        activeItems[j].style.display = 'none';
                    }
                    activeItems[i].style.display = 'block';
                })
            }
        })

        // selectで選択された情報を取得
        const bankSelect = document.getElementById('bankSelect');
        // 銀行情報を格納する変数
        let bankName = null;
        let accountHolder = null;
        bankSelect.addEventListener('change', function() {
            // 現在選択されているoptionを取得
            const selectedOption = this.options[this.selectedIndex];
            bankName = selectedOption.getAttribute('data-bank-name');
            accountHolder = selectedOption.getAttribute('data-account-holder');
        });

        btn.addEventListener('click', () => {
            // radioによってセットする情報を分岐
            if(radio[0].checked){
                document.querySelector('.setBankName').value = bankName;
                document.querySelector('.setAccountHolder').value = accountHolder;
            }else{
                document.querySelector('.setBankName').value = document.querySelector('.getBankNameByInput').value;
                document.querySelector('.setAccountHolder').value = document.querySelector('.getAccountHolderByInput').value;
            }
            modal.style.display = 'none';
        })

    }
    bankModalActive();


    /**
     * ******************************************************************************
     * ******************************************************************************
     * *****************　ドライバー作成請求書　*****************************************
     */

    const salaryCalc = () => {
        const changeElement = document.querySelectorAll('.changeElement');
        const salarySubTotal = document.querySelector('.salarySubTotal');

        const salaryItemCalc = () => {
            const num = document.querySelectorAll('.salaryNum');
            const unit = document.querySelectorAll('.salaryUnit');
            const salary = document.querySelectorAll('.salaryAmount');

            for(let i = 0; i < salary.length; i++){
                salary[i].value = addCommas(num[i].value * removeCommasAndCastToInt(unit[i].value));
            }
        }

        // 給与小計
        const salarySubTotalCalc = () => {
            const salary = document.querySelectorAll('.salaryAmount');
            let total = 0;

            for(let i = 0; i < salary.length; i++){
                const salaryValue = removeCommasAndCastToInt(salary[i].value);
                if (!isNaN(salaryValue)) {
                    total += salaryValue;
                }
            }
            salarySubTotal.value = addCommas(total);
            return total;
        }

        // 給与消費税
        const salaryTaxCalc = (salarySubTotal) => {
            const getTaxElement = document.getElementById('salaryTax')
            let tax = 0;
            if(getTaxElement){
                tax = salarySubTotal * 0.1;
                getTaxElement.value = Math.round(tax);
            }
            return Math.round(tax);
        }

        // 高速代他計算
        const etcTotalCalc = () => {
            const etcElement = document.querySelectorAll('.etcElement');
            const etcTotalElement = document.querySelector('.etcTotal');
            let tmpTotal = 0;

            for(let i = 0; i < etcElement.length; i++){
                const etcValue = removeCommasAndCastToInt(etcElement[i].value);
                if(!isNaN(etcValue)){
                    tmpTotal += etcValue;
                }
            }
            etcTotalElement.value = addCommas(tmpTotal);
            return tmpTotal;
        }

        const salaryTotalCalc = (salarySub, salaryTax, etc) => {
            const salaryTotalElement = document.querySelector('.salaryTotal')
            const salaryTotalTmp = salarySub + salaryTax + etc;

            salaryTotalElement.value = addCommas(salaryTotalTmp);
        }

        const costItemByDriverCalc = () => {
            const num = document.querySelectorAll('.costNumByDriver');
            const unit = document.querySelectorAll('.costUnitByDriver');
            const total = document.querySelectorAll('.costTotalByDriver');

            for(let i = 0; i < total.length; i++){
                total[i].value = addCommas(num[i].value * removeCommasAndCastToInt(unit[i].value));
            }
        }

        const costAllTotal = () => {
            const total = document.querySelectorAll('.costTotalByDriver');
            const totalAll = document.querySelector('.costAllTotal');
            let tmp = 0;

            for(let i = 0; i < total.length; i++){
                const totalValue = removeCommasAndCastToInt(total[i].value)
                if(!isNaN(totalValue)) {
                    tmp += totalValue;
                }
            }

            totalAll.value = addCommas(tmp);
        }

        // 全部の合計金額の計算
        const lastCalc = () => {
            const allCalcTotal = document.querySelector('.allCalcTotal');
            const allCalcTotalView = document.querySelector('.allCalcTotalView');

            const salaryTotal = document.querySelector('.salaryTotal');
            const costAllTotal = document.querySelector('.costAllTotal');

            const salaryAmount = removeCommasAndCastToInt(salaryTotal.value);
            const costAmount = removeCommasAndCastToInt(costAllTotal.value);

            allCalcTotal.value = addCommas(salaryAmount - costAmount);
            allCalcTotalView.textContent = '¥' + addCommas((salaryAmount - costAmount));
        }


        for(let i = 0; i < changeElement.length; i++){
            changeElement[i].addEventListener('change', () => {
                salaryItemCalc();
                costItemByDriverCalc();
                const resultSalarySubTotal = salarySubTotalCalc();
                const resultSalaryTax =  salaryTaxCalc(resultSalarySubTotal);
                const resultEtc = etcTotalCalc();
                salaryTotalCalc(resultSalarySubTotal, resultSalaryTax, resultEtc);
                costAllTotal();
                lastCalc();
            })
        }

        salaryItemCalc();
        costItemByDriverCalc();
        const resultSalarySubTotal = salarySubTotalCalc();
        const resultSalaryTax =  salaryTaxCalc(resultSalarySubTotal);
        const resultEtc = etcTotalCalc();
        salaryTotalCalc(resultSalarySubTotal, resultSalaryTax, resultEtc);
        costAllTotal();
        lastCalc();

        const btns = document.querySelectorAll('.deleteRowBtn');
        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener('click', function() {
                // クリックされたボタンの親のtr要素を探す
                let tr = this.closest('tr');

                // tr要素が見つかった場合、それを削除
                if (tr) {
                    tr.remove();
                }

                salaryItemCalc();
                costItemByDriverCalc();
                const resultSalarySubTotal = salarySubTotalCalc();
                const resultSalaryTax =  salaryTaxCalc(resultSalarySubTotal);
                const resultEtc = etcTotalCalc();
                salaryTotalCalc(resultSalarySubTotal, resultSalaryTax, resultEtc);
                costAllTotal();
                lastCalc();
            });
        }

    }
    salaryCalc();

    const costCalc = () => {
        const changeElem = document.querySelectorAll('.changeElement');
        const costSubTotal = document.querySelector('.costSubTotal');

        const costItemCalc = () => {
            const elem = document.querySelectorAll('.costElem');
            const num = document.querySelectorAll('.costNum');
            const unit = document.querySelectorAll('.costUnit');

            for(let i = 0; i < elem.length; i++){
                elem[i].value = num[i].value * removeCommasAndCastToInt(unit[i].value);
            }
        }

        const costSubTotalCalc = () => {
            const elem = document.querySelectorAll('.costElem');
            const costSubTotalByTaxTable = document.querySelector('.targetCost');
            const taxCheckBox = document.querySelectorAll('.row-check-box');

            let total = 0;

            for(let i = 0; i < elem.length; i++){
                const elemValue = removeCommasAndCastToInt(elem[i].value)
                if(!isNaN(elemValue)) {
                    total += elemValue;
                }
            }
            let targetAmountTotal = 0;
            for(let i = 0; i < taxCheckBox.length; i++){
                if(taxCheckBox[i].checked){
                    let trParent = taxCheckBox[i].parentNode.parentNode; //親要素の行を取得
                    let targetAmount = removeCommasAndCastToInt(trParent.querySelector('.costElem').value); //金額のinputの値を取得
                    if(!isNaN(targetAmount)){ //カンマを除去した値が数値か判断
                        targetAmountTotal += targetAmount;
                    }
                }
            }

            costSubTotal.value = total;
            costSubTotalByTaxTable.value= targetAmountTotal;
            return total;
        }

        const taxCalc = (total) => {
            const costTaxElem = document.querySelector('.costTaxElem');
            const costTaxByTaxTable = document.querySelector('.targetCostTax');

            const taxCheckBox = document.querySelectorAll('.row-check-box');
            let targetAmountTotal = 0;
            for(let i = 0; i < taxCheckBox.length; i++){
                if(taxCheckBox[i].checked){
                    let trParent = taxCheckBox[i].parentNode.parentNode; //親要素の行を取得
                    let targetAmount = removeCommasAndCastToInt(trParent.querySelector('.costElem').value); //金額のinputの値を取得
                    if(!isNaN(targetAmount)){ //カンマを除去した値が数値か判断
                        targetAmountTotal += targetAmount;
                    }
                }
            }
            const tax = targetAmountTotal * 0.1;

            costTaxElem.value = Math.round(tax);
            costTaxByTaxTable.value = Math.round(tax);

            return Math.round(tax);
        }

        const costTotalCalc = (subTotal, tax) => {
            const totalElem = document.querySelector('.costTotalElem');
            const totalView = document.querySelector('.costTotalView');
            const totalByDriver = document.querySelector('.costTotalByDriver');
            const unit = document.querySelector('.costUnitByDriver-C');

            let totalValue = subTotal + tax;

            totalElem.value = totalValue;
            totalView.textContent = '¥' + addCommas(totalValue);
            totalByDriver.value = addCommas(totalValue);
            unit.value = totalValue;
        }

        // 連携させるために記述
        const costAllTotal = () => {
            const total = document.querySelectorAll('.costTotalByDriver');
            const totalAll = document.querySelector('.costAllTotal');
            let tmp = 0;

            for(let i = 0; i < total.length; i++){
                const totalValue = removeCommasAndCastToInt(total[i].value)
                if(!isNaN(totalValue)) {
                    tmp += totalValue;
                }
            }

            totalAll.value = tmp;
        }

        // 全部の合計金額の計算
        const lastCalc = () => {
            const allCalcTotal = document.querySelector('.allCalcTotal');
            const allCalcTotalView = document.querySelector('.allCalcTotalView');

            const salaryTotal = document.querySelector('.salaryTotal');
            const costAllTotal = document.querySelector('.costAllTotal');

            const salaryAmount = removeCommasAndCastToInt(salaryTotal.value);
            const costAmount = removeCommasAndCastToInt(costAllTotal.value);

            allCalcTotal.value = salaryAmount - costAmount;
            allCalcTotalView.textContent = '¥' + addCommas(salaryAmount - costAmount);
        }

        for(let i = 0; i < changeElem.length; i++){
            changeElem[i].addEventListener('change', () => {
                if(changeElem[i].classList.contains('costChangeElem')){
                    costItemCalc();
                    const resultSubTotal = costSubTotalCalc();
                    const resultTax = taxCalc(resultSubTotal);
                    costTotalCalc(resultSubTotal, resultTax);
                    costAllTotal();
                    lastCalc();
                    commmaActive();
                }
            })
        }

        const btns = document.querySelectorAll('.deleteRowBtn');

        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener('click', function() {
                // クリックされたボタンの親のtr要素を探す
                let tr = this.closest('tr');

                // tr要素が見つかった場合、それを削除
                if (tr) {
                    tr.remove();
                }

                costItemCalc();
                const resultSubTotal = costSubTotalCalc();
                const resultTax = taxCalc(resultSubTotal);
                costTotalCalc(resultSubTotal, resultTax);
                costAllTotal();
                lastCalc();
                commmaActive();
            });
        }
    }
    costCalc();

    const checkBoxActiveTaxCalc = () => {
        const taxCheckBox = document.querySelectorAll('.row-check-box');
        const costTaxElem = document.querySelector('.costTaxElem');
        const costSubTotal = document.querySelector('.costSubTotal');
        const costTotalElem = document.querySelector('.costTotalElem');
        const costTotalView = document.querySelector('.costTotalView');
        // 10%対象テーブルの要素
        const targetCost = document.querySelector('.targetCost');
        const targetCostTax = document.querySelector('.targetCostTax');
        const notCostAmount = document.querySelector('.notCostAmount');
        // 費用総裁テーブルの要素
        const costTotalByDriver = document.querySelector('.costInvoiceTotal');
        const costUnitByDriver = document.querySelector('.costInvoiceUnit');

        for(let i = 0; i < taxCheckBox.length; i++){
            taxCheckBox[i].addEventListener('change', () => {
                let targetAmountTotal = 0; //税率の対象の変数
                for(let j = 0; j < taxCheckBox.length; j++){
                    if(taxCheckBox[j].checked){ //チェックがついてる行
                        let trParent = taxCheckBox[j].parentNode.parentNode; //親要素の行を取得
                        let targetAmount = removeCommasAndCastToInt(trParent.querySelector('.costElem').value); //金額のinputの値を取得
                        if(!isNaN(targetAmount)){ //カンマを除去した値が数値か判断
                            targetAmountTotal += targetAmount;
                        }
                    }
                }

                // 費用メインテーブルに値をセット
                const tax = targetAmountTotal * 0.1;
                costTaxElem.value = addCommas(Math.round(tax));
                costTotalElem.value = addCommas(removeCommasAndCastToInt(costSubTotal.value) + Math.round(tax));
                costTotalView.textContent = '¥' + addCommas(removeCommasAndCastToInt(costSubTotal.value) + Math.round(tax));

                // 10%対象テーブルに値をセット
                targetCost.value = addCommas(targetAmountTotal);
                targetCostTax.value = addCommas(Math.round(tax));
                notCostAmount.value = addCommas(removeCommasAndCastToInt(costSubTotal.value) - targetAmountTotal);

                // 給与テーブルの相殺テーブルに値をセット
                costTotalByDriver.value = addCommas(removeCommasAndCastToInt(costSubTotal.value) + Math.round(tax));
                costUnitByDriver.value = addCommas(removeCommasAndCastToInt(costSubTotal.value) + Math.round(tax));

                checkBoxActiveTotalCalc();
            })
        }
    }
    checkBoxActiveTaxCalc();

    const taxTableActiveTotalCalc = () => {
        // 10%対象テーブル
        const targetCost = document.querySelector('.targetCost');
        const targetCostTax = document.querySelector('.targetCostTax');
        // ドライバー価格メインテーブル
        const costInvoiceUnit = document.querySelector('.costInvoiceUnit');
        const costInvoiceTotal = document.querySelector('.costInvoiceTotal');
        // 費用メインテーブル
        const costTaxElem = document.querySelector('.costTaxElem');
        const costSubTotal = document.querySelector('.costSubTotal');
        const costTotalElem = document.querySelector('.costTotalElem');
        const costTotalView = document.querySelector('.costTotalView');

        // 10%対象が編集された場合発火
        targetCost.addEventListener('change', () => {
            // 変更された値を取得
            let changeTargetCost = targetCost.value;
            // 変更された値から消費税を計算
            let changeTax = Math.round(removeCommasAndCastToInt(changeTargetCost) * 0.1);
            // 消費税をセット
            targetCostTax.value = addCommas(changeTax); //10%対象消費税
            costTaxElem.value = addCommas(changeTax); //メインテーブル
            // 小計の値を取得
            let costSubTotalValue = removeCommasAndCastToInt(costSubTotal.value);
            // 合計金額の計算
            let calcTotalCost = costSubTotalValue + changeTax;
            // 合計金額をセット
            costTotalElem.value = addCommas(calcTotalCost);
            costTotalView.textContent = '¥' + addCommas(calcTotalCost);
            // ドライバー価格メインテーブルの費用相殺テーブルに値をセット
            costInvoiceUnit.value = addCommas(calcTotalCost);
            costInvoiceTotal.value = addCommas(calcTotalCost);
            checkBoxActiveTotalCalc();
        })
    }
    taxTableActiveTotalCalc();

    const checkBoxActiveTotalCalc = () => {
        const costTotalByDriver = document.querySelectorAll('.costTotalByDriver');
        const costAllTotal = document.querySelector('.costAllTotal');
        const salaryTotal = document.querySelector('.salaryTotal');
        const allCalcTotal = document.querySelector('.allCalcTotal');
        const totalView = document.querySelector('.allCalcTotalView');

        let total = 0;
        for(let i = 0; i < costTotalByDriver.length; i++){
            total += removeCommasAndCastToInt(costTotalByDriver[i].value);
        }
        costAllTotal.value = addCommas(total);
        let allTotal = removeCommasAndCastToInt(salaryTotal.value) - total;
        allCalcTotal.value = addCommas(allTotal);
        totalView.textContent = '¥' + addCommas(allTotal);
    }


    const deleteBtn = () => {
        const btns = document.querySelectorAll('.deleteRowBtn');

        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener('click', function() {
                // クリックされたボタンの親のtr要素を探す
                let tr = this.closest('tr');

                // tr要素が見つかった場合、それを削除
                if (tr) {
                    tr.remove();
                    commmaActive()
                }
            });
        }
    };
    // deleteBtn();


    // 行を追加
    // 給与テーブル行追加
    const salaryTableAddRow = () => {
        const btn = document.querySelector('.salaryAddBtn');
        const table = document.querySelector('.salaryTopTable');

        btn.addEventListener('click', () => {
            // 新しいtr要素を作成
            const newRow = document.createElement('tr');
            newRow.classList.add('salaryBasicRow');

            // 必要なtd要素を新しいtr要素に追加
            newRow.innerHTML = `
                <td class="top-table-data w-70"><input type="text" name="salaryNo[]" class="input table-input changeElement"></td>
                <td class="top-table-data w-70"><input type="text" name="salaryMonth[]" class="input table-input changeElement"></td>
                <td class="top-table-data w-260"><input type="text" name="salaryProject[]" class="input table-input changeElement"></td>
                <td class="top-table-data w-70"><input type="text" name="salaryEtc[]" class="input table-input changeElement etcElement"></td>
                <td class="top-table-data w-70"><input type="text" name="salaryCount[]" class="input table-input changeElement salaryNum"></td>
                <td class="top-table-data w-70"><input type="text" name="salaryUntil[]" class="input table-input amount changeElement salaryUnit commaInput"></td>
                <td class="top-table-data w-70"><input type="text" name="salaryAmount[]" class="input table-input amount changeElement salaryAmount commaInput"></td>
                <div class="salaryRowDelete deleteRowBtn"><span class="deleteRowBtn__line"></span></div>
            `;

            const SalaryRows = table.querySelectorAll('.salaryBasicRow');
            const lastSalaryRow = SalaryRows[SalaryRows.length - 1];
             // 新しい行を最後のsalaryBasicRowの後に挿入
            lastSalaryRow.parentNode.insertBefore(newRow, lastSalaryRow.nextSibling);

            salaryCalc();
            costCalc();
            deleteBtn();
            commmaActive();
        })
    }
    salaryTableAddRow();

    // 給与テーブル相殺テーブルの行追加
    const salaryCostTableAddRow = () => {
        const btn = document.querySelector('.salaryCostAddBtn');
        const table = document.querySelector('.salaryCostTable');

        btn.addEventListener('click', () => {
            const newRow = document.createElement('tr');
            newRow.classList.add('salaryCostBasicRow');

            newRow.innerHTML = `
                <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                <td class="top-table-data w-330"><input type="text" name="salaryCostName[]" class="input table-input changeElement"></td>
                <td class="top-table-data w-70"><input type="text" name="salaryCostNum[]" class="input table-input changeElement costNumByDriver"></td>
                <td class="top-table-data w-70"><input type="text" name="salaryCostUntil[]" class="input table-input changeElement amount costUnitByDriver commaInput"></td>
                <td class="top-table-data w-100"><input type="text" name="salaryCostAmount[]" class="input table-input changeElement amount costTotalByDriver costInvoiceTotal commaInput">                <div class="salaryRowDelete deleteRowBtn"><span class="deleteRowBtn__line"></span></div></td>
            `;

            const salaryCostRows = table.querySelectorAll('.salaryCostBasicRow');
            const lastRow = salaryCostRows[salaryCostRows.length - 1];

            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);

            salaryCalc();
            costCalc();
            commmaActive();
        })

    }
    salaryCostTableAddRow();

    // 費用テーブル行追加
    const costTableAddRow = () => {
        const btn = document.querySelector('.costAddBtn');
        const table = document.querySelector('.costTable');

        btn.addEventListener('click', () => {
            const newRow = document.createElement('tr');
            newRow.classList.add('costBasicRow');

            newRow.innerHTML = `
                <td class="table-item w-400"><input type="text" name="costItem[]" value="" class="input table-input changeElement costChangeElem"><input checked type="checkbox" class="row-check-box"></td>
                <td class="table-data w-100"><input type="text" name="costNum[]" value="" class="input table-input changeElement costChangeElem costNum"></td>
                <td class="table-data w-100"><input type="text" name="costUntil[]" value="" class="input table-input cost-amount changeElement costChangeElem costUnit commaInput"></td>
                <td class="table-data w-110"><input type="text" name="costAmount[]" value="" class="input table-input cost-amount changeElement costChangeElem costElem commaInput"><div class="salaryRowDelete deleteRowBtn"><span class="deleteRowBtn__line"><span/><div/></td>
            `;

            const costRows = table.querySelectorAll('.costBasicRow');
            const lastRow = costRows[costRows.length - 1];

            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);

            salaryCalc();
            costCalc();
            commmaActive();
            checkBoxActiveTaxCalc();
        })
    }
    costTableAddRow();

    function removeCommasAndCastToInt(value) {
        // 確認または変換されたvalueが常に文字列であることを保証
        var stringValue = String(value);
        // Remove both full-width and half-width commas
        var newValue = stringValue.replace(/,|，/g, '');
        if(newValue == ''){
            newValue = 0;
        }
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

    // 会社情報の挙動
    const companyChangeActive = () => {
        const select = document.querySelector('.companySelect');
        const salaryTextarea = document.querySelector('.salaryCompanyTextarea');
        const costTextarea = document.querySelector('.costCompanyTextarea');
        const bankTextarea = document.querySelector('.bankTextarea');

        select.addEventListener('change', function() {
            // 現在選択されているoptionを取得
            const selectedOption = this.options[this.selectedIndex];
            companyName = selectedOption.getAttribute('data-company-name');
            post_code = selectedOption.getAttribute('data-company-post-code');
            address = selectedOption.getAttribute('data-company-address');
            building_name = selectedOption.getAttribute('data-company-building-name');
            phone = selectedOption.getAttribute('data-company-phone');
            fax = selectedOption.getAttribute('data-company-fax');
            number = selectedOption.getAttribute('data-company-register-number');
            bank_name = selectedOption.getAttribute('data-bank-name');
            holder_name = selectedOption.getAttribute('data-account-holder-name');

            // building_nameが空でなければ、addressの後に'\n'とbuilding_nameを追加
            let fullAddress = building_name ? address + '\n' + building_name : address;

            // 給与請求書
            salaryTextarea.value = companyName + "　" + '御中' + '\n' + '〒' + post_code + '\n' + address + '\n' + building_name;
            // 費用請求書関連
            costTextarea.value = companyName + '\n' + '〒' + post_code + '\n' + fullAddress + '\n' + 'TEL : ' + phone + '\n' + 'FAX : ' + fax + '\n' + '登録番号 : ' + number;
            bankTextarea.value = bank_name + "　　　" + holder_name;
        });
    }
    companyChangeActive();

    const colorChangeActive = () => {
        const elem = document.querySelectorAll('.colorChangeElem');
        const colorSelect = document.querySelector('.colorSelect');
        const sentColor = document.querySelector('.sentColor');

        colorSelect.addEventListener('change', () => {
            for(let i = 0; i < elem.length; i++){
                elem[i].style.backgroundColor = colorSelect.value;
            }
            sentColor.value = colorSelect.value;
        })

    }
    colorChangeActive();
})
