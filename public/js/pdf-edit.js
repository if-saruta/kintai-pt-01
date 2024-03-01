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
                salary[i].value = num[i].value * unit[i].value;
            }
        }

        // 給与小計
        const salarySubTotalCalc = () => {
            const salary = document.querySelectorAll('.salaryAmount');
            let total = 0;

            for(let i = 0; i < salary.length; i++){
                const salaryValue = parseInt(salary[i].value, 10);
                if (!isNaN(salaryValue)) {
                    total += salaryValue;
                }
            }
            salarySubTotal.value = total;
            return total;
        }

        // 給与消費税
        const salaryTaxCalc = (salarySubTotal) => {
            const getTaxElement = document.getElementById('salaryTax')
            let tax = 0;
            if(getTaxElement){
                tax = salarySubTotal * 0.1;
                getTaxElement.value = Math.ceil(tax);
            }
            return Math.ceil(tax);
        }

        // 高速代他計算
        const etcTotalCalc = () => {
            const etcElement = document.querySelectorAll('.etcElement');
            const etcTotalElement = document.querySelector('.etcTotal');
            let tmpTotal = 0;

            for(let i = 0; i < etcElement.length; i++){
                const etcValue = parseInt(etcElement[i].value, 10);
                if(!isNaN(etcValue)){
                    tmpTotal += etcValue;
                }
            }
            etcTotalElement.value = tmpTotal;
            return tmpTotal;
        }

        const salaryTotalCalc = (salarySub, salaryTax, etc) => {
            const salaryTotalElement = document.querySelector('.salaryTotal')
            const salaryTotalTmp = salarySub + salaryTax + etc;

            salaryTotalElement.value = salaryTotalTmp;
        }

        const costItemByDriverCalc = () => {
            const num = document.querySelectorAll('.costNumByDriver');
            const unit = document.querySelectorAll('.costUnitByDriver');
            const total = document.querySelectorAll('.costTotalByDriver');

            for(let i = 0; i < total.length; i++){
                total[i].value = num[i].value * unit[i].value;
            }
        }

        const costAllTotal = () => {
            const total = document.querySelectorAll('.costTotalByDriver');
            const totalAll = document.querySelector('.costAllTotal');
            let tmp = 0;

            for(let i = 0; i < total.length; i++){
                const totalValue = parseInt(total[i].value, 10)
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

            const salaryAmount = parseInt(salaryTotal.value, 10);
            const costAmount = parseInt(costAllTotal.value, 10);

            allCalcTotal.value = salaryAmount - costAmount;
            allCalcTotalView.textContent = '¥' + (salaryAmount - costAmount);
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
                elem[i].value = num[i].value * unit[i].value;
            }
        }

        const costSubTotalCalc = () => {
            const elem = document.querySelectorAll('.costElem');
            let total = 0;

            for(let i = 0; i < elem.length; i++){
                const elemValue = parseInt(elem[i].value, 10)
                if(!isNaN(elemValue)) {
                    total += elemValue;
                }
            }
            costSubTotal.value = total;
            return total;
        }

        const taxCalc = (total) => {
            const costTaxElem = document.querySelector('.costTaxElem');

            const tax = total * 0.1;

            costTaxElem.value = Math.ceil(tax);

            return Math.ceil(tax);
        }

        const costTotalCalc = (subTotal, tax) => {
            const totalElem = document.querySelector('.costTotalElem');
            const totalView = document.querySelector('.costTotalView');
            const totalByDriver = document.querySelector('.costTotalByDriver');
            const unit = document.querySelector('.costUnitByDriver-C');

            let totalValue = subTotal + tax;

            totalElem.value = totalValue;
            totalView.textContent = '¥' + totalValue;
            totalByDriver.value = totalValue;
            unit.value = totalValue;
        }

        // 連携させるために記述
        const costAllTotal = () => {
            const total = document.querySelectorAll('.costTotalByDriver');
            const totalAll = document.querySelector('.costAllTotal');
            let tmp = 0;

            for(let i = 0; i < total.length; i++){
                const totalValue = parseInt(total[i].value, 10)
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

            const salaryAmount = parseInt(salaryTotal.value, 10);
            const costAmount = parseInt(costAllTotal.value, 10);

            allCalcTotal.value = salaryAmount - costAmount;
            allCalcTotalView.textContent = '¥' + (salaryAmount - costAmount);
        }

        for(let i = 0; i < changeElem.length; i++){
            changeElem[i].addEventListener('change', () => {
                costItemCalc();
                const resultSubTotal = costSubTotalCalc();
                const resultTax = taxCalc(resultSubTotal);
                costTotalCalc(resultSubTotal, resultTax);
                costAllTotal();
                lastCalc();
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
            });
        }
    }
    costCalc();

    const deleteBtn = () => {
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
                <td class="top-table-data w-70"><input type="text" name="salaryUntil[]" class="input table-input changeElement salaryUnit"></td>
                <td class="top-table-data w-70"><input type="number" name="salaryAmount[]" class="input table-input changeElement salaryAmount"></td>
                <div class="salaryRowDelete deleteRowBtn"><span class="deleteRowBtn__line"><span/><span class="deleteRowBtn__line"><span/><div/>
            `;

            const SalaryRows = table.querySelectorAll('.salaryBasicRow');
            const lastSalaryRow = SalaryRows[SalaryRows.length - 1];
             // 新しい行を最後のsalaryBasicRowの後に挿入
            lastSalaryRow.parentNode.insertBefore(newRow, lastSalaryRow.nextSibling);

            salaryCalc();
            costCalc();
            deleteBtn();
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
                <td class="top-table-data w-70"><input type="text" name="salaryCostUntil[]" class="input table-input changeElement costUnitByDriver"></td>
                <td class="top-table-data w-100"><input type="text" name="salaryCostAmount[]" class="input table-input changeElement costTotalByDriver"></td>
                <div class="salaryRowDelete deleteRowBtn"><span class="deleteRowBtn__line"><span/><span class="deleteRowBtn__line"><span/><div/>
            `;

            const salaryCostRows = table.querySelectorAll('.salaryCostBasicRow');
            const lastRow = salaryCostRows[salaryCostRows.length - 1];

            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);

            salaryCalc();
            costCalc();
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
                <td class="table-item w-400"><input type="text" name="costItem[]" value="" class="input table-input changeElement"></td>
                <td class="table-data w-100"><input type="text" name="costNum[]" value="" class="input table-input changeElement costNum"></td>
                <td class="table-data w-100"><input type="text" name="costUntil[]" value="" class="input table-input changeElement costUnit"></td>
                <td class="table-data w-110"><input type="text" name="costAmount[]" value="" class="input table-input changeElement costElem"></td>
                <div class="salaryRowDelete deleteRowBtn"><span class="deleteRowBtn__line"><span/><span class="deleteRowBtn__line"><span/><div/>
            `;

            const costRows = table.querySelectorAll('.costBasicRow');
            const lastRow = costRows[costRows.length - 1];

            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);

            salaryCalc();
            costCalc();
        })
    }
    costTableAddRow();

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
            phone = selectedOption.getAttribute('data-company-phone');
            fax = selectedOption.getAttribute('data-company-fax');
            number = selectedOption.getAttribute('data-company-register-number');
            bank_name = selectedOption.getAttribute('data-bank-name');
            holder_name = selectedOption.getAttribute('data-account-holder-name');

            salaryTextarea.value = companyName + "　" + '御中' + '\n' + '〒' + post_code + '\n' + address;
            costTextarea.value = companyName + '\n' + '〒' + post_code + '\n' + address + '\n' + 'TEL : ' + phone + '\n' + 'FAX : ' + fax + '\n' + '登録番号 : ' + number;
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
